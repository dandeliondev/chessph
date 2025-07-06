<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class RatingController
 *
 * Handles display and import of NCFP chess player ratings.
 */
class RatingController extends Controller
{
    /**
     * Display the profile view for a single user.
     *
     * @param  int  $id  The User model ID
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Retrieve the User by ID or fail with 404
        $user = User::findOrFail($id);

        // Render the 'user.profile' view with the user data
        return view('user.profile', ['user' => $user]);
    }

    /**
     * Main entry point to display rating listings.
     *
     * Builds different views depending on URI segments:
     *  - Homepage overview (no segments)
     *  - Paginated listing (segment 2 != 'top100')
     *  - Top 100 filters (segment 2 == 'top100')
     *
     * @param  Request  $request  HTTP request with filters and segments
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Build navigation menu links
        $nav = $this->nav();

        // Reconstruct URI segments as a string for view use
        $segments = implode('/', $request->segments()) . '/';

        // Initialize arrays for meta keywords and names
        $names = [];
        $keywords = ['ncfp', 'rating', 'national chess federation of philippines', 'chess', 'philippines', 'top players'];

        // Allowed FIDE titles for filtering
        $titles = ['un', 'agm', 'nm', 'cm', 'fm', 'im', 'gm', 'wfm', 'wcm', 'wim', 'wgm'];

        // Secret Easter egg identifiers
        $revealer_arr = ['Danilo de Luna', 'D00497'];

        // If the search matches a secret code, redirect away
        if (in_array($request->input('search'), $revealer_arr)) {
            return redirect()->away(
                'https://danideluna.dev?Puzzle-Solved!-You-found-the-Hermit!-Cheers-to-your-sharp-mind!'
            );
        }

        // Gather query-string parameters with defaults
        $qs = [
            'search'     => $request->input('search', ''),
            'sort_by'    => $request->input('sort_by', 'lastname'),
            'order'      => $request->input('order', 'asc'),
            'age_from'   => $request->input('age_from', 0),
            'age_option' => $request->input('age_option', 'any'),
            'age_to'     => $request->input('age_to', 20),
            'age_basis'  => $request->input('age_basis', 'birthyear'),
            'gender'     => $request->input('gender', 'all'),
            'title'      => $request->input('title', $titles),
        ];

        // Base query builder for cph_ratings table
        $users = DB::table('cph_ratings')
            ->select(DB::raw("
                *,
                standard - standard_prev AS increase,
                YEAR(CURDATE()) - YEAR(birthdate) AS age,
                DATE_FORMAT(
                    FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(`birthDate`)),
                    '%Y'
                ) + 0 AS age2
            "));

        // Determine which view logic to execute based on URI
        if ($request->segment(1) === '') {
            // --- HOMEPAGE OVERVIEW ---

            $header   = 'NCFP Rating';
            $paginate = false;
            $filter   = true;

            // Prepare multiple top lists: overall, women, juniors, etc.
            $categories = [
                'top_gainers', 'top_all', 'top_women',
                'top_nm', 'top_untitled', 'top_juniors',
                'top_kiddies', 'top_title'
            ];
            $lists = [];

            foreach ($categories as $cat) {
                // Clone query for each category
                $$cat = DB::table('cph_ratings')
                    ->select(DB::raw("
                        *,
                        standard - standard_prev AS increase,
                        YEAR(CURDATE()) - YEAR(birthdate) AS age,
                        DATE_FORMAT(
                            FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(`birthDate`)),
                            '%Y'
                        ) + 0 AS age2
                    "))
                    ->where('federation', 'PHI');

                // Additional filters per category
                if ($cat === 'top_women') {
                    $$cat->where('gender', 'f');
                }
                if ($cat === 'top_nm') {
                    $$cat->where('title', 'nm');
                }
                if ($cat === 'top_untitled') {
                    $$cat->whereNull('title');
                }
                if ($cat === 'top_juniors') {
                    $$cat->whereRaw('YEAR(CURDATE()) - YEAR(birthdate) BETWEEN 13 AND 20');
                }
                if ($cat === 'top_kiddies') {
                    $$cat->whereRaw('YEAR(CURDATE()) - YEAR(birthdate) <= 12');
                }
                if ($cat === 'top_title') {
                    $$cat->whereRaw('title <> title_prev');
                }

                // Order and limit for each
                $$cat->orderBy($cat === 'top_gainers' ? 'increase' : 'standard', 'desc')
                    ->limit($cat === 'top_title' ? 100 : 10);

                // Execute and store
                $lists[$cat] = $$cat->get();
            }

            // Build display structure for the view
            $displayLists = [
                ['header' => 'Top 10 Overall',               'list' => $lists['top_all']],
                ['header' => 'Top 10 Women',                 'list' => $lists['top_women']],
                ['header' => 'Top 10 National Masters',      'list' => $lists['top_nm']],
                ['header' => 'Top 10 Non-Masters',           'list' => $lists['top_untitled']],
                ['header' => 'Top 10 Juniors',               'list' => $lists['top_juniors']],
                ['header' => 'Top 10 Kiddies',               'list' => $lists['top_kiddies']],
                ['header' => 'Latest Title Awardee',         'list' => $lists['top_title']],
                ['header' => 'Top 10 Highest Rating Increase','list' => $lists['top_gainers']],
            ];

            // Collect names/keywords for meta tags
            $rank = 1;
            foreach ($lists['top_all'] as $row) {
                $keywords[] = strtolower("{$row->lastname} {$row->firstname}");
                $names[]    = "{$rank}. " . ucwords(strtolower($row->lastname . ' ' . $row->firstname));
                $rank++;
            }

            $lists = $displayLists;

        } elseif ($request->segment(2) !== 'top100') {
            // --- PAGINATED FULL LIST ---

            $paginate = true;
            $filter   = true;
            $header   = 'NCFP Rating List';

            // Apply sorting
            $users->orderBy($qs['sort_by'], $qs['order']);

            // Apply search filter if provided
            if (trim($qs['search']) !== '') {
                $term = $qs['search'];
                $users->whereRaw(DB::raw("
                    (
                        ncfp_id LIKE '%{$term}%'
                        OR firstname LIKE '%{$term}%'
                        OR lastname LIKE '%{$term}%'
                        OR CONCAT(firstname, ' ', lastname) LIKE '%{$term}%'
                        OR CONCAT(lastname, ', ', firstname) LIKE '%{$term}%'
                    )
                "));
            }

            // Gender filter
            if ($qs['gender'] === 'f') {
                $users->where('gender', 'f');
            }

            // Title filter
            if (!empty($qs['title'])) {
                if (in_array('un', $qs['title'])) {
                    // 'un' stands for untitled (NULL title)
                    if (count($qs['title']) > 1) {
                        $in = "'" . implode("','", $qs['title']) . "'";
                        $users->whereRaw("(title IN ({$in}) OR title IS NULL)");
                    } else {
                        $users->whereNull('title');
                    }
                } else {
                    $users->whereIn('title', $qs['title']);
                }
            }

            // Age range filter if selected
            if ($qs['age_option'] === 'range') {
                $ageField = $qs['age_basis'] === 'birthdate'
                    ? "DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)),'%Y')+0"
                    : "YEAR(CURDATE()) - YEAR(birthdate)";

                $users->whereRaw("{$ageField} BETWEEN {$qs['age_from']} AND {$qs['age_to']}");
            }

            // Always limit to Philippines federation
            $users->where('federation', 'PHI');

            // Execute paginated query
            $list = $users->paginate(100);

            // Optionally append the range of last names to the header
            if (!$request->input('search')) {
                $first = $list->first();
                $last  = $list->last();
                $header .= $first && $last
                    ? " ({$first->lastname} - {$last->lastname})"
                    : '';
            }

            // Meta data
            foreach ($list as $row) {
                $keywords[] = strtolower("{$row->lastname} {$row->firstname}");
                $names[]    = strtolower("{$row->lastname} {$row->firstname}");
            }

            // Single list for view
            $lists = [
                ['header' => '', 'list' => $list],
            ];

        } else {
            // --- TOP 100 SPECIAL FILTERS ---

            $paginate = false;
            $filter   = false;
            $header   = 'NCFP Rating Top 100';

            // Always sort by standard rating descending
            $users->orderBy('standard', 'desc');

            // Gender filter segment
            $genderSeg = $request->segment(3);
            if ($genderSeg === 'women') {
                $users->where('gender', 'f');
                $header .= ' Women';
            } elseif ($genderSeg === 'men') {
                $users->whereNull('gender');
                $header .= ' Men';
            }

            // Age or title filters segment
            $filterType = $request->segment(4);
            $value      = $request->segment(5);
            switch ($filterType) {
                case 'under':
                    $users->whereRaw("YEAR(CURDATE()) - YEAR(birthdate) <= {$value}");
                    $header .= " ({$value} and below)";
                    break;
                case 'above':
                    $users->whereRaw("YEAR(CURDATE()) - YEAR(birthdate) >= {$value}");
                    $header .= " ({$value} and above)";
                    break;
                case 'national-master':
                    $users->whereIn('title', ['nm', 'wnm']);
                    $header .= ' National Master';
                    break;
                case 'non-master':
                    $users->whereNull('title');
                    $header .= ' Non-Master';
                    break;
            }

            // Federation and limit
            $users->where('federation', 'PHI')->limit(100);
            $list = $users->get();

            // Collect meta names
            $rank = 1;
            foreach ($list as $row) {
                $keywords[] = strtolower("{$row->lastname} {$row->firstname}");
                $names[]    = "{$rank}. " . ucwords(strtolower($row->lastname . ' ' . $row->firstname));
                $rank++;
            }

            $lists = [
                ['header' => '', 'list' => $list],
            ];
        }

        // Trim meta arrays to top 10
        $keywords = array_slice($keywords, 0, 10);
        $names    = array_slice($names, 0, 10);

        // Prepare view data
        $subheader         = 'Based from December 2023 release.';
        $meta_description  = "{$header} " . implode(', ', $names);
        $meta_keywords     = implode(',', $keywords);
        $title             = ucwords(strtolower($header));

        return view('rating.ncfprating', [
            'paginate'         => $paginate,
            'nav'              => $nav,
            'qs'               => $qs,
            'filter'           => $filter,
            'lists'            => $lists,
            'page'             => $request->input('page', '1'),
            'segments'         => $segments,
            'header'           => $header,
            'title'            => $title,
            'subheader'        => $subheader,
            'meta_description' => $meta_description,
            'meta_keywords'    => $meta_keywords,
            'title_colors'     => $this->title_colors(),
        ]);
    }

    /**
     * Build the Top-100 navigation menu.
     *
     * @return array
     */
    public function nav()
    {
        return [
            'top100'  => [
                'Overall'               => 'ncfp/top100/all',
                'National Masters'      => 'ncfp/top100/all/national-master',
                // ... other age-based links
            ],
            'top100m' => [
                'Overall'    => 'ncfp/top100/men',
                // ... men-specific links
            ],
            'top100w' => [
                'Overall'    => 'ncfp/top100/women',
                // ... women-specific links
            ],
        ];
    }

    /**
     * Return color mappings for each chess title.
     *
     * @return array
     */
    private function title_colors()
    {
        return [
            'nm'  => 'green',
            'cm'  => 'olive',
            'fm'  => 'yellow',
            'im'  => 'orange',
            'gm'  => 'red',
            'wnm' => 'teal',
            'wcm' => 'blue',
            'wfm' => 'pink',
            'wim' => 'purple',
            'wgm' => 'violet',
        ];
    }

    /**
     * Import and update ratings from a CSV file.
     *
     * Processes the CSV, sanitizes data, and inserts or updates records in cph_ratings.
     *
     * @return void
     */
    public function store_ratings()
    {
        // Allow long execution for large CSV imports
        ini_set('max_execution_time', 3600);

        // Path to the December 2023 ratings CSV
        $filePath = Storage::disk('local')->path('rating_dec_2023.csv');
        $file     = fopen($filePath, 'r');
        $ctr      = 0;

        while (($row = fgetcsv($file, 200, ",")) !== false) {
            // Skip header row
            if ($ctr++ === 0) {
                continue;
            }

            // Define CSV column indices
            $cols = [
                'ncfp_id'   => 0,
                'lastname'  => 1,
                'firstname' => 2,
                'gender'    => 3,
                'federation'=> 4,
                'fide_id'   => 5,
                'title'     => 9,
                'standard'  => 10,
                'rapid'     => 14,
                'blitz'     => 18,
                'f960'      => 22,
                'bdate'     => 27,
                'status'    => 29,
            ];

            // Sanitize and parse each field (gender, birthdate, status, numeric ratings, etc.)
            // ... (sanitization logic here)

            // Check if record exists; if not, insert; otherwise update
            $exists = DB::table('cph_ratings')->where('ncfp_id', $row[$cols['ncfp_id']])->exists();
            if (! $exists) {
                DB::table('cph_ratings')->insert([
                    // mapping of sanitized data to table columns
                ]);
            } else {
                DB::table('cph_ratings')
                    ->where('ncfp_id', $row[$cols['ncfp_id']])
                    ->update([
                        // mapping of sanitized data to table columns
                    ]);
            }
        }

        fclose($file);
    }

    /**
     * Validate a date string against a given format.
     *
     * @param  string  $date    Date string (e.g. 'YYYY-MM-DD')
     * @param  string  $format  The expected date format
     * @return bool
     */
    private function validateDate($date, $format = 'Y-m-d')
    {
        $parts = explode('-', $date);
        return checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0]);
    }

    /**
     * Simple sanitizer to trim and normalize whitespace.
     *
     * @param  string|null  $str
     * @return string|null
     */
    public function sanitize($str)
    {
        if ($str === null) {
            return null;
        }
        // Replace non-breaking spaces and trim
        return str_replace("\xA0", ' ', trim($str));
    }
}
