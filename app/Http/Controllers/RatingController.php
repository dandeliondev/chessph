<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

class RatingController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int $id
     * @return View
     */
    public function show($id)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }

    public function index(Request $request)
    {
        //$current_segment = $this->current_segment();

        $nav = $this->nav($request);
        $segments = '';
        foreach ($request->segments() as $segment) {
            $segments .= $segment . '/';
        }

        $names = [];
        $keywords[] = 'ncfp';
        $keywords[] = 'rating';
        $keywords[] = 'national chess federation of philippines';
        $keywords[] = 'chess';
        $keywords[] = 'philippines';
        $keywords[] = 'top players';

        $titles = ['un', 'agm', 'nm', 'cm', 'fm', 'im', 'gm', 'wfm', 'wcm', 'wim', 'wgm'];
        $qs = [
            'search' => $request->input('search') ?? '',
            'sort_by' => $request->input('sort_by') ?? 'lastname',
            'order' => $request->input('order') ?? 'asc',
            'age_from' => $request->input('age_from') ?? 0,
            'age_option' => $request->input('age_option') ?? 'any',
            'age_to' => $request->input('age_to') ?? 20,
            'age_basis' => $request->input('age_basis') ?? 'birthyear',
            'gender' => $request->input('gender') ?? 'all',
            'title' => $request->input('title') ?? $titles,
        ];
        $users = DB::table('cph_ratings');
        $users->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
        //$users->crossJoin(DB::raw('(select @rownum := 0) r'));

        if ($request->segment(1) == '') {
            $header = 'NCFP Rating';
            $paginate = false;
            $filter = true;

            $top_gainers = DB::table('cph_ratings');
            $top_all = DB::table('cph_ratings');
            $top_women = DB::table('cph_ratings');
            $top_nm = DB::table('cph_ratings');
            $top_untitled = DB::table('cph_ratings');
            $top_juniors = DB::table('cph_ratings');
            $top_kiddies = DB::table('cph_ratings');
            $top_title = DB::table('cph_ratings');

            $top_gainers->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
            $top_all->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
            $top_women->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
            $top_nm->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
            $top_untitled->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
            $top_juniors->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
            $top_kiddies->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
            $top_title->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));

            $top_gainers->where('federation','PHI');
            $top_all->where('federation','PHI');
            $top_women->where('federation','PHI');
            $top_nm->where('federation','PHI');
            $top_untitled->where('federation','PHI');
            $top_juniors->where('federation','PHI');
            $top_kiddies->where('federation','PHI');
            $top_title->where('federation','PHI');

            $top_women->where('gender', 'f');
            $top_nm->where('title', 'nm');
            $top_untitled->where('title', null);

            $top_juniors->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '>=', 13);
            $top_juniors->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', 20);

            $top_kiddies->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', 12);

            $top_title->whereRaw(DB::raw('title <> title_prev'));
            //$top_title->whereRaw('title <> title_prev OR (title != null and title_prev = null)');

            $top_gainers->orderBy('increase', 'desc');
            $top_all->orderBy('standard', 'desc');
            $top_women->orderBy('standard', 'desc');
            $top_nm->orderBy('standard', 'desc');
            $top_untitled->orderBy('standard', 'desc');
            $top_juniors->orderBy('standard', 'desc');
            $top_kiddies->orderBy('standard', 'desc');
            $top_title->orderBy('standard', 'desc');

            $top_gainers->limit(10);
            $top_all->limit(10);
            $top_women->limit(10);
            $top_nm->limit(10);
            $top_untitled->limit(10);
            $top_juniors->limit(10);
            $top_kiddies->limit(10);
            $top_title->limit(100);


            $list_top_gainers = $top_gainers->get();
            $list_top_all = $top_all->get();
            $list_top_women = $top_women->get();
            $list_top_nm = $top_nm->get();
            $list_top_untitled = $top_untitled->get();
            $list_top_juniors = $top_juniors->get();
            $list_top_kiddies = $top_kiddies->get();
            $list_top_title = $top_title->get();




            $rank = 1;
            foreach ($list_top_all as $row) {
                $keywords[] = strtolower($row->lastname . ' ' . $row->firstname);
                $names[] = $rank . '. ' . ucwords(strtolower($row->lastname)) . ' ' . ucwords(strtolower($row->firstname));
                $rank++;
            }

            $lists = [
                0 => [
                    'header' => 'Top 10 Overall',
                    'subheader' => '',
                    'list' => $list_top_all,
                ],
                1 => [
                    'header' => 'Top 10 Women',
                    'subheader' => '',
                    'list' => $list_top_women,
                ],
                2 => [
                    'header' => 'Top 10 National Masters',
                    'subheader' => '',
                    'list' => $list_top_nm,
                ],
                3 => [
                    'header' => 'Top 10 Non-Masters',
                    'subheader' => '',
                    'list' => $list_top_untitled,
                ],
                4 => [
                    'header' => 'Top 10 Juniors',
                    'subheader' => '',
                    'list' => $list_top_juniors,
                ],
                5 => [
                    'header' => 'Top 10 Kiddies',
                    'subheader' => '',
                    'list' => $list_top_kiddies,
                ],
                6 => [
                    'header' => 'Latest Title Awardee',
                    'subheader' => '',
                    'list' => $list_top_title,
                ],
                7 => [
                    'header' => 'Top 10 Highest Rating Increase',
                    'subheader' => '',
                    'list' => $list_top_gainers,
                ]
            ];

        } elseif ($request->segment(2) != 'top100') {
            $paginate = true;
            $filter = true;
            $header = 'NCFP Rating List';

            $users->orderBy($qs['sort_by'], $qs['order']);

            if (trim($qs['search']) !== '') {
                $users->whereRaw(DB::raw("(ncfp_id LIKE '%{$qs['search']}%'  OR firstname LIKE '%{$qs['search']}%' OR lastname LIKE '%{$qs['search']}%' OR CONCAT(firstname, ' ', lastname) LIKE '%{$qs['search']}%' OR CONCAT(lastname, ' ', firstname) LIKE '%{$qs['search']}%' OR CONCAT(lastname, ', ', firstname) LIKE '%{$qs['search']}%')"));

            }

            if ($qs['gender'] !== 'all') {
                if ($qs['gender'] == 'f') {
                    $users->where('gender', 'f');
                }
            }

            if (count($qs['title'])) {
                if (in_array('un', $qs['title'])) {
                    if (count($qs['title']) > 1) {
                        $titles_in = "'" . implode("','", $qs['title']) . "'";
                        $users->whereRaw(DB::raw("(title IN ({$titles_in})  OR title IS Null)"));
                    } else {
                        $users->where('title', null);
                    }

                } else {
                    $users->whereIn('title', $qs['title']);
                }
            }
            if ($qs['age_option'] === 'range') {
                if ($qs['age_basis'] === 'birthdate') {

                    $users->where(DB::raw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0'),
                        '>=', $qs['age_from']);
                    $users->where(DB::raw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0'),
                        '<=', $qs['age_to']);

                } else {
                    $users->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '>=', $qs['age_from']);
                    $users->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', $qs['age_to']);
                }
            }

//            echo $users->toSql();
//            exit();
            $users->where('federation','PHI');

            $list = $users->paginate(100);

            if ($request->input('search')) {

            } else {
                $lastKey = $list->keys()->last();
                if (isset($list{'0'})) {
                    $header .= strtolower(' (' . $list{'0'}->lastname . ' - ' . $list{$lastKey}->lastname . ')');
                }

            }
            foreach ($list as $row) {
                $keywords[] = strtolower($row->lastname . ' ' . $row->firstname);
                $names[] = strtolower($row->lastname . ' ' . $row->firstname);
            }

            $lists = [
                0 => [
                    'header' => '',
                    'subheader' => '',
                    'list' => $list,
                ]
            ];

        } else {
            $paginate = false;
            $filter = false;

            $users->orderBy('standard', 'desc');

            $age = $request->segment(5);
            $gender = $request->segment(3);
            $header = 'NCFP Rating Top 100';

            if ($gender != 'all') {
                if ($gender == 'women') {
                    $users->where('gender', 'f');
                    $header .= ' Women ';
                } elseif ($gender == 'men') {
                    $users->where('gender', null);
                    $header .= ' Men';
                }
            }

            if ($request->segment(4) == 'under') {
                $users->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', $age);
                $header .= ' (' . $age . ' and below) ';
            } elseif ($request->segment(4) == 'above') {
                $users->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '>=', $age);
                $header .= ' (' . $age . ' and above' . ')';
            } elseif ($request->segment(4) == 'national-master') {
                $users->whereRaw(DB::raw("(title IN ('nm','wnm'))"));
                $header .= ' National Master';
            } elseif ($request->segment(4) == 'non-master') {
                $users->whereRaw(DB::raw("title is NULL"));
                $header .= ' Non-Master';
            }
            $users->where('federation','PHI');
            $users->limit(100);

            $list = $users->get();

            $rank = 1;
            foreach ($list as $row) {
                $keywords[] = strtolower($row->lastname . ' ' . $row->firstname);
                $names[] = $rank . '. ' . ucwords(strtolower($row->lastname)) . ' ' . ucwords(strtolower($row->firstname));
                $rank++;
            }

            $lists = [
                0 => [
                    'header' => '',
                    'subheader' => '',
                    'list' => $list,
                ]
            ];

        }

        $keywords = array_slice($keywords, 0, 10);
        $names = array_slice($names, 0, 10);

        $subheader = 'Based from NCFP February 2023 release.';

        $meta_description = $header . ' ' . implode(', ', $names);
        $meta_keywords = '' . implode(',', $keywords);
        $title = ucwords(strtolower($header));

        $data = [
            'paginate' => $paginate,
            'nav' => $nav,
            'qs' => $qs,
            'filter' => $filter,
            'lists' => $lists,
            'page' => $request->input('page') ?? '1',
            'segments' => $segments,
            'header' => $header,
            'title' => $title,
            'subheader' => $subheader,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'title_colors' => $this->title_colors(),

        ];

        return view('rating.ncfprating', $data);
    }

    public function nav()
    {


        $nav = [
            'top100' => [
                'Overall' => 'ncfp/top100/all',
                'National Masters' => 'ncfp/top100/all/national-master',
                'Non-Masters' => 'ncfp/top100/all/non-master',
                '60 and above' => 'ncfp/top100/all/above/60',
                '20 and below' => 'ncfp/top100/all/under/20',
                '18 and below' => 'ncfp/top100/all/under/18',
                '16 and below' => 'ncfp/top100/all/under/16',
                '14 and below' => 'ncfp/top100/all/under/14',
                '12 and below' => 'ncfp/top100/all/under/12',
                '10 and below' => 'ncfp/top100/all/under/10',
                '8 and below' => 'ncfp/top100/all/under/8',
                '6 and below' => 'ncfp/top100/all/under/6',
            ],
            'top100m' => [
                'Overall' => 'ncfp/top100/men',
                '60 and above' => 'ncfp/top100/men/above/60',
                '20 and below' => 'ncfp/top100/men/under/20',
                '18 and below' => 'ncfp/top100/men/under/18',
                '16 and below' => 'ncfp/top100/men/under/16',
                '14 and below' => 'ncfp/top100/men/under/14',
                '12 and below' => 'ncfp/top100/men/under/12',
                '10 and below' => 'ncfp/top100/men/under/10',
                '8 and below' => 'ncfp/top100/men/under/8',
                '6 and below' => 'ncfp/top100/men/under/6',
            ],
            'top100w' => [
                'Overall' => 'ncfp/top100/women',
                '60 and above' => 'ncfp/top100/women/above/60',
                '20 and below' => 'ncfp/top100/women/under/20',
                '18 and below' => 'ncfp/top100/women/under/18',
                '16 and below' => 'ncfp/top100/women/under/16',
                '14 and below' => 'ncfp/top100/women/under/14',
                '12 and below' => 'ncfp/top100/women/under/12',
                '10 and below' => 'ncfp/top100/women/under/10',
                '8 and below' => 'ncfp/top100/women/under/8',
                '6 and below' => 'ncfp/top100/women/under/6',
            ],

        ];

        return $nav;
    }

    private function title_colors()
    {
        $title_colors = [
            'nm' => 'green',
            'cm' => 'olive',
            'fm' => 'yellow',
            'im' => 'orange',
            'gm' => 'red',
            'wnm' => 'teal',
            'wcm' => 'blue',
            'wfm' => 'pink',
            'wim' => 'purple',
            'wgm' => 'violet',
        ];

        return $title_colors;
    }

    public function store_ratings()
    {
        /*
         * STEP 1
                UPDATE cph_ratings
                SET title_prev = title,standard_prev=standard,rapid_prev=rapid,blitz_prev=blitz,f960_prev=f960

            Step 2
                Set $arr_int

            Step 3
                Set col_ values

          Step 4
                to resolve issue with special characters open the csv file with notepad++, encoding->convert to utf8

         */

        ini_set('max_execution_time', 3600);
        //$file_n = Storage::url('rating_nov2_2019.csv');
        $file_n = Storage::disk('local')->path('rating_feb_2023.csv');

        $file = fopen($file_n, "r");
        $ctr = 0;

        $inserts = [];
        while (($data = fgetcsv($file, 200, ",")) !== false) {

            //skip column headers
            if ($ctr == 0) {
                $ctr++;
                continue;
            }
            $ctr++;

            $byear = null;
            $bdate = null;
            $fide_id = null;
            $status = 2;
            $gender = null;

            $col_gender = 3;
            $col_bdate = 27;
            $col_status = 29;
            $col_fide = 5;
            $col_ncfp_id = 0;
            $col_lastname = 1;
            $col_firstname = 2;
            $col_federation = 4;
            $col_title = 9;
            $col_standard = 10;
            $col_rapid = 14;
            $col_blitz = 18;
            $col_f960 = 22;

            $arr_int = [$col_standard, $col_rapid, $col_blitz, $col_f960];

            // no need to edit beyond this point

            if (isset($data[$col_gender])) {
                if ($data[$col_gender] !== '') {
                    $gender = strtolower($data[$col_gender]);
                } elseif ($data[$col_gender] !== 'F') {
                    $gender = "";
                } else {
                    $gender = null;
                }
            }

            if ($data[$col_ncfp_id] === 'A00602') {
                $gender = '';
            }

            if (isset($data[$col_bdate])) {
                $bdate = trim($data[$col_bdate]);
                if (trim($bdate) == '') {
                    $bdate = null;
                } else {
                    $details_bdate = explode('/', $bdate);

                    if (count($details_bdate) === 3) {

                        $bdate = $details_bdate[2] . '-' . $details_bdate[1] . '-' . $details_bdate[0];
                        if (!$this->validateDate($bdate)) {
                            $bdate = null;
                        }

                        if ($details_bdate[2] > 1900 and $details_bdate[2] <= 2019) {

                            $byear = $details_bdate[2];
                        }

                    } elseif (count($details_bdate) === 1) {

                        if ($details_bdate[2] > 1900 and $details_bdate[2] <= 2019) {

                            $byear = $details_bdate[2];
                        }
                    }
                }
            }

            if (isset($data[$col_status])) {
                if ($data[$col_status] !== 'i') {
                    $status = 1;
                }

            }

            if (isset($data[$col_fide])) {
                $fide_id_temp = $data[$col_fide];
                if (trim($fide_id_temp) !== '') {
                    $fide_id = $data[$col_fide];
                }

            }

            foreach ($arr_int as $i) {
                if (!isset($data[$i])) {
                    $data[$i] = 0;
                } else {
                    $data[$i] = $res = preg_replace("/[^0-9]/", "", $data[$i]);

                }
            }

            $ncfp_id = $this->sanitize($data[$col_ncfp_id]) != '' ? $this->sanitize($data[$col_ncfp_id]) : null;

            if ($data[$col_ncfp_id] === 'V00422') {
                $title = 'gm';
            } else {
                $title = $this->sanitize($data[$col_title]) != '' ? $this->sanitize($data[$col_title]) : null;
            }

            echo '<br />' . $ncfp_id;

            $users = DB::table('cph_ratings')->where('ncfp_id', $ncfp_id)->get();

            if ($users->count() === 0) {
                $insert = [
                    'ncfp_id' => $this->sanitize($col_ncfp_id) != '' ? $this->sanitize($col_ncfp_id) : null,
                    'fide_id' => $this->sanitize($fide_id) != '' ? $this->sanitize($fide_id) : null,
                    'firstname' => $this->sanitize($data[$col_firstname]) != '' ? $this->sanitize($data[$col_firstname]) : null,
                    'lastname' => $this->sanitize($data[$col_lastname]) != '' ? $this->sanitize($data[$col_lastname]) : null,
                    'gender' => $this->sanitize($gender) != '' ? $gender : null,
                    'federation' => $this->sanitize($data[$col_federation]) != '' ? $this->sanitize($data[$col_federation]) : null,
                    'title' => $title,
                    'standard' => $this->sanitize($data[$col_standard]) != '' ? $this->sanitize($data[$col_standard]) : null,
                    'rapid' => $this->sanitize($data[$col_rapid]) != '' ? $this->sanitize($data[$col_rapid]) : null,
                    'blitz' => $this->sanitize($data[$col_blitz]) != '' ? $this->sanitize($data[$col_blitz]) : null,
                    'f960' => $this->sanitize($data[$col_f960]) != '' ? $this->sanitize($data[$col_f960]) : null,
                    'birthdate' => $bdate,
                    'birthyear' => $byear,
                    'status' => $status,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $inserts[] = $insert;


                try {

                    DB::table('cph_ratings')->insert($insert);
                } catch (\Exception $e) {
                    echo json_encode($insert) . '' . $e->getMessage();
                }

            } else {
                $update = [

                    //'ncfp_id'        => $this->sanitize($col_ncfp_id) != '' ? $this->sanitize($col_ncfp_id) : NULL,
                    'fide_id' => $this->sanitize($fide_id) != '' ? $this->sanitize($fide_id) : null,
                    //'firstname'      => $this->sanitize($col_firstname) != '' ? $this->sanitize($col_firstname) : NULL,
                    //'lastname'       => $this->sanitize($col_lastname) != '' ? $this->sanitize($col_lastname) : NULL,
                    'gender' => $this->sanitize($gender) != '' ? $gender : null,
                    'federation' => $this->sanitize($data[$col_federation]) != '' ? $this->sanitize($data[$col_federation]) : null,
                    'title' => $title,
                    'standard' => $this->sanitize($data[$col_standard]) != '' ? $this->sanitize($data[$col_standard]) : null,
                    'rapid' => $this->sanitize($data[$col_rapid]) != '' ? $this->sanitize($data[$col_rapid]) : null,
                    'blitz' => $this->sanitize($data[$col_blitz]) != '' ? $this->sanitize($data[$col_blitz]) : null,
                    'f960' => $this->sanitize($data[$col_f960]) != '' ? $this->sanitize($data[$col_f960]) : null,
                    'birthdate' => $bdate,
                    'birthyear' => $byear,
                    'status' => $status,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                try {

                    DB::table('cph_ratings')->where('ncfp_id', $ncfp_id)->update($update);
                } catch (Exception $e) {
                    echo json_encode($update) . '' . $e->getMessage();
                }
            }
        }

        fclose($file);
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $tempDate = explode('-', $date);

        // checkdate(month, day, year)
        return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
    }

    public function sanitize($str)
    {

        $str = trim($str);
        $str = str_replace("\xA0", ' ', $str);

        return $str;

    }

}