<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Http\Request;

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
        $nav      = $this->nav($request);
        $segments = '';
        foreach ($request->segments() as $segment) {
            $segments .= $segment . '/';
        }

        $titles = ['un', 'nm', 'cm', 'fm', 'im', 'gm', 'wfm', 'wcm', 'wim', 'wgm'];
        $qs     = [
            'search'     => $request->input('search') ?? '',
            'sort_by'    => $request->input('sort_by') ?? 'lastname',
            'order'      => $request->input('order') ?? 'asc',
            'age_from'   => $request->input('age_from') ?? 0,
            'age_option' => $request->input('age_option') ?? 'any',
            'age_to'     => $request->input('age_to') ?? 20,
            'age_basis'  => $request->input('age_basis') ?? 'birthyear',
            'gender'     => $request->input('gender') ?? 'all',
            'title'      => $request->input('title') ?? $titles,
        ];
        $users  = DB::table('cph_ratings');
        $users->select(DB::raw('*,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
        //$users->crossJoin(DB::raw('(select @rownum := 0) r'));

        if ($request->segment(2) != 'top100') {
            $paginate = true;
            $filter   = true;
            $header   = 'NCFP Rating List';

            $users->orderBy($qs['sort_by'], $qs['order']);

            if (trim($qs['search']) !== '') {
                $users->whereRaw(DB::raw("(ncfp_id LIKE '%{$qs['search']}%'  OR firstname LIKE '%{$qs['search']}%' OR lastname LIKE '%{$qs['search']}%')"));

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

                    $users->where(DB::raw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0'), '>=', $qs['age_from']);
                    $users->where(DB::raw('DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0'), '<=', $qs['age_to']);

                } else {
                    $users->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '>=', $qs['age_from']);
                    $users->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', $qs['age_to']);
                }
            }

//            echo $users->toSql();
//            exit();

            $list = $users->paginate(100);

        } else {
            $paginate = false;
            $filter   = false;

            $users->orderBy('standard', 'desc');

            $age    = $request->segment(5);
            $gender = $request->segment(3);
            $header = 'NCFP Rating Top 100';

            if ($request->segment(4) == 'under') {
                $users->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', $age);
                $header .= ' Under ' . $age;
            } elseif ($request->segment(4) == 'above') {
                $users->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '>=', $age);
                $header .= ' Above ' . $age;
            }

            if ($gender != 'all') {
                if ($gender == 'women') {
                    $users->where('gender', 'f');
                    $header .= ' (Women) ';
                } elseif ($gender == 'men') {
                    $users->where('gender', null);
                    $header .= ' (Men)';
                }
            }

            $users->limit(100);

            $list = $users->get();

        }

        $keywords[] = 'ncfp';
        $keywords[] = 'rating';
        $keywords[] = 'national chess federation of philippines';
        $keywords[] = 'top players';
        $names      = [];

        foreach ($list as $row) {
            $keywords[] = strtolower($row->lastname . ' ' . $row->firstname);
            $names[]    = strtolower($row->lastname . ' ' . $row->firstname);
        }

        $keywords = array_slice($keywords, 0, 10);
        $names    = array_slice($names, 0, 10);

        $subheader        = 'Based from NCFP March 1, 2019 release.';
        $meta_description = $header . ' ' . $subheader . ' ' . implode(', ', $names);
        $meta_keywords    = '' . implode(',', $keywords);
        $title            = 'NCFP rating ' . strtolower($header);

        $data = [
            'paginate'         => $paginate,
            'nav'              => $nav,
            'qs'               => $qs,
            'filter'           => $filter,
            'list'             => $list,
            'page'             => $request->input('page') ?? '1',
            'segments'         => $segments,
            'header'           => $header,
            'title'            => $title,
            'subheader'        => $subheader,
            'meta_description' => $meta_description,
            'meta_keywords'    => $meta_keywords,

        ];

        return view('rating.ncfprating', $data);
    }

    public function nav()
    {


        $nav = [
            'top100'  => [
                'Overall'  => 'ncfp/top100/all',
                'above 60' => 'ncfp/top100/all/above/60',
                'Under 20' => 'ncfp/top100/all/under/20',
                'Under 18' => 'ncfp/top100/all/under/18',
                'Under 16' => 'ncfp/top100/all/under/16',
                'Under 14' => 'ncfp/top100/all/under/14',
                'Under 12' => 'ncfp/top100/all/under/12',
                'Under 10' => 'ncfp/top100/all/under/10',
                'Under 8'  => 'ncfp/top100/all/under/8',
                'Under 6'  => 'ncfp/top100/all/under/6',
            ],
            'top100m' => [
                'Overall'  => 'ncfp/top100/men',
                'above 60' => 'ncfp/top100/men/above/60',
                'Under 20' => 'ncfp/top100/men/under/20',
                'Under 18' => 'ncfp/top100/men/under/18',
                'Under 16' => 'ncfp/top100/men/under/16',
                'Under 14' => 'ncfp/top100/men/under/14',
                'Under 12' => 'ncfp/top100/men/under/12',
                'Under 10' => 'ncfp/top100/men/under/10',
                'Under 8'  => 'ncfp/top100/men/under/8',
                'Under 6'  => 'ncfp/top100/men/under/6',
            ],
            'top100w' => [
                'Overall'  => 'ncfp/top100/women',
                'above 60' => 'ncfp/top100/women/above/60',
                'Under 20' => 'ncfp/top100/women/under/20',
                'Under 18' => 'ncfp/top100/women/under/18',
                'Under 16' => 'ncfp/top100/women/under/16',
                'Under 14' => 'ncfp/top100/women/under/14',
                'Under 12' => 'ncfp/top100/women/under/12',
                'Under 10' => 'ncfp/top100/women/under/10',
                'Under 8'  => 'ncfp/top100/women/under/8',
                'Under 6'  => 'ncfp/top100/women/under/6',
            ],

        ];

        return $nav;
    }

    public function store_ratings()
    {
        exit('hello world');
        //$file_n   = Storage::url('rating_march_2019.csv');
        $file_n = Storage::disk('local')->path('rating_march_2019.csv');;
        $file     = fopen($file_n, "r");
        $all_data = array();
        $array    = array();
        $ctr      = 0;
        $arr_int  = [6, 9, 7, 8, 10, 13, 11, 12, 14, 17, 15, 16, 18];
        while (($data = fgetcsv($file, 200, ",")) !== false) {
            if ($ctr == 0) {
                $ctr++;
                continue;
            }
            $bday    = null;
            $fide_id = null;
            $status  = 2;
            $gender  = null;

            if (isset($data[21])) {
                $fide_id = $data[21];
            }

            if (isset($data[20])) {
                if ($data[20] !== 'i') {
                    $status = 1;
                }

            }

            if (isset($data[3])) {
                if ($data[3] !== '') {
                    $gender = strtolower($data[3]);
                } else {
                    $gender = null;
                }

            }

            if (isset($data[19])) {
                $bday = $data[19];
                if (trim($bday) != '') {
                    $bday = date("Y-m-d", strtotime($bday));
                } else {
                    $bday = null;
                }
            }
            foreach ($arr_int as $i) {
                if (!isset($data[$i])) {
                    $data[$i] = 0;
                } else {
                    $data[$i] = $res = preg_replace("/[^0-9]/", "", $data[$i]);

                }
            }

            $insert = [
                'ncfp_id'        => trim($data[0]) != '' ? trim($data[0]) : null,
                'fide_id'        => trim($fide_id) != '' ? trim($fide_id) : null,
                'firstname'      => trim($data[2]) != '' ? trim($data[2]) : null,
                'lastname'       => trim($data[1]) != '' ? trim($data[1]) : null,
                'gender'         => trim($gender) != '' ? $gender : null,
                'federation'     => trim($data[4]) != '' ? trim($data[4]) : null,
                'title'          => trim($data[5]) != '' ? trim($data[5]) : null,
                'standard'       => trim($data[6]) != '' ? trim($data[6]) : null,
                'standard_prov'  => trim($data[9]) != '' ? trim($data[9]) : null,
                'standard_games' => trim($data[7]) != '' ? trim($data[7]) : null,
                'standard_k'     => trim($data[8]) != '' ? trim($data[8]) : null,
                'rapid'          => trim($data[10]) != '' ? trim($data[10]) : null,
                'rapid_prov'     => trim($data[13]) != '' ? trim($data[13]) : null,
                'rapid_games'    => trim($data[11]) != '' ? trim($data[11]) : null,
                'rapid_k'        => trim($data[12]) != '' ? trim($data[12]) : null,
                'blitz'          => trim($data[14]) != '' ? trim($data[14]) : null,
                'blitz_prov'     => trim($data[17]) != '' ? trim($data[17]) : null,
                'blitz_games'    => trim($data[15]) != '' ? trim($data[15]) : null,
                'blitz_k'        => trim($data[16]) != '' ? trim($data[16]) : null,
                'total_games'    => trim($data[18]) != '' ? trim($data[18]) : 0,
                'birthdate'      => $bday,
                'status'         => $status,

            ];

            DB::table('cph_ratings')->insert($insert);
        }

        fclose($file);
    }

}