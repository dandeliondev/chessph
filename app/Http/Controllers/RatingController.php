<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;

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

    public function index()
    {
        $nav = [
            'top100'  => [
                'Overall'  => 'top100/overall',
                'Under 20' => 'top100/under-20',
                'Under 18' => 'top100/under-18',
            ],
            'top100m' => [
                'Overall'  => 'top100/overall',
                'Under 20' => 'top100/under-20',
                'Under 18' => 'top100/under-18',
            ],
            'top100w' => [
                'Overall'  => 'top100/overall',
                'Under 20' => 'top100/under-20',
                'Under 18' => 'top100/under-18',
            ],

        ];

        $data = [
            'nav' => $nav

        ];
        return view('rating.ncfprating', $data);
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
                'ncfp_id'        => trim($data[0]) != '' ? $data[0] : null,
                'fide_id'        => trim($fide_id) != '' ? $fide_id : null,
                'firstname'      => trim($data[2]) != '' ? $data[2] : null,
                'lastname'       => trim($data[1]) != '' ? $data[1] : null,
                'gender'         => trim($gender) != '' ? $gender : null,
                'federation'     => trim($data[4]) != '' ? $data[4] : null,
                'title'          => trim($data[5]) != '' ? $data[5] : null,
                'standard'       => trim($data[6]) != '' ? $data[6] : null,
                'standard_prov'  => trim($data[9]) != '' ? $data[9] : null,
                'standard_games' => trim($data[7]) != '' ? $data[7] : null,
                'standard_k'     => trim($data[8]) != '' ? $data[8] : null,
                'rapid'          => trim($data[10]) != '' ? $data[10] : null,
                'rapid_prov'     => trim($data[13]) != '' ? $data[13] : null,
                'rapid_games'    => trim($data[11]) != '' ? $data[11] : null,
                'rapid_k'        => trim($data[12]) != '' ? $data[12] : null,
                'blitz'          => trim($data[14]) != '' ? $data[14] : null,
                'blitz_prov'     => trim($data[17]) != '' ? $data[17] : null,
                'blitz_games'    => trim($data[15]) != '' ? $data[15] : null,
                'blitz_k'        => trim($data[16]) != '' ? $data[16] : null,
                'total_games'    => trim($data[18]) != '' ? $data[18] : 0,
                'birthdate'      => $bday,
                'status'         => $status,

            ];

            DB::table('cph_ratings')->insert($insert);
        }

        fclose($file);
    }

}