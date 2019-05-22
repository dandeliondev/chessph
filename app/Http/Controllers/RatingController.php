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

		$nav      = $this->nav($request);
		$segments = '';
		foreach ($request->segments() as $segment) {
			$segments .= $segment . '/';
		}

		$names      = [];
		$keywords[] = 'ncfp';
		$keywords[] = 'rating';
		$keywords[] = 'national chess federation of philippines';
		$keywords[] = 'chess';
		$keywords[] = 'philippines';
		$keywords[] = 'top players';

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
		$users->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
		//$users->crossJoin(DB::raw('(select @rownum := 0) r'));

		if ($request->segment(1) == '') {
			$header = 'NCFP Rating';
			$paginate = false;
			$filter   = true;

			$top_gainers = DB::table('cph_ratings');
			$top_all     = DB::table('cph_ratings');
			$top_women     = DB::table('cph_ratings');
			$top_nm     = DB::table('cph_ratings');
			$top_untitled     = DB::table('cph_ratings');
			$top_juniors     = DB::table('cph_ratings');
			$top_kiddies     = DB::table('cph_ratings');

			$top_gainers->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_all->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_women->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_nm->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_untitled->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_juniors->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_kiddies->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));

			$top_women->where('gender', 'f');
			$top_nm->where('title', 'nm');
			$top_untitled->where('title', null);

			$top_juniors->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '>=', 13);
			$top_juniors->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', 20);

			$top_kiddies->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', 12);



			$top_gainers->orderBy('increase', 'desc');
			$top_all->orderBy('standard', 'desc');
			$top_women->orderBy('standard', 'desc');
			$top_nm->orderBy('standard', 'desc');
			$top_untitled->orderBy('standard', 'desc');
			$top_juniors->orderBy('standard', 'desc');
			$top_kiddies->orderBy('standard', 'desc');

			$top_gainers->limit(10);
			$top_all->limit(10);
			$top_women->limit(10);
			$top_nm->limit(10);
			$top_untitled->limit(10);
			$top_juniors->limit(10);
			$top_kiddies->limit(10);

			$list_top_gainers = $top_gainers->get();
			$list_top_all     = $top_all->get();
			$list_top_women     = $top_women->get();
			$list_top_nm     = $top_nm->get();
			$list_top_untitled     = $top_untitled->get();
			$list_top_juniors     = $top_juniors->get();
			$list_top_kiddies     = $top_kiddies->get();


			$rank = 1;
			foreach ($list_top_all as $row) {
				$keywords[] = strtolower($row->lastname . ' ' . $row->firstname);
				$names[]    = $rank . '. ' . ucwords(strtolower($row->lastname)) . ' ' . ucwords(strtolower($row->firstname));
				$rank++;
			}

			$lists = [
				0 => [
					'header'    => 'Top 10 Overall',
					'subheader' => '',
					'list'      => $list_top_all,
				],
				1 => [
					'header'    => 'Top 10 Women',
					'subheader' => '',
					'list'      => $list_top_women,
				],
				2 => [
					'header'    => 'Top 10 National Masters',
					'subheader' => '',
					'list'      => $list_top_nm,
				],
				3 => [
					'header'    => 'Top 10 Non-Masters',
					'subheader' => '',
					'list'      => $list_top_untitled,
				],
				4 => [
					'header'    => 'Top 10 Juniors',
					'subheader' => '',
					'list'      => $list_top_juniors,
				],
				5 => [
					'header'    => 'Top 10 Kiddies',
					'subheader' => '',
					'list'      => $list_top_kiddies,
				],
				6 => [
					'header'    => 'Top 10 Highest Rating Increase',
					'subheader' => '',
					'list'      => $list_top_gainers,
				]
			];

		} elseif ($request->segment(2) != 'top100') {
			$paginate = true;
			$filter   = true;
			$header   = 'NCFP Rating List';

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
						$users->where('title', NULL);
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
				$names[]    = strtolower($row->lastname . ' ' . $row->firstname);
			}


			$lists = [
				0 => [
					'header'    => '',
					'subheader' => '',
					'list'      => $list,
				]
			];

		} else {
			$paginate = false;
			$filter   = false;

			$users->orderBy('standard', 'desc');

			$age    = $request->segment(5);
			$gender = $request->segment(3);
			$header = 'NCFP Rating Top 100';

			if ($gender != 'all') {
				if ($gender == 'women') {
					$users->where('gender', 'f');
					$header .= ' Women ';
				} elseif ($gender == 'men') {
					$users->where('gender', NULL);
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


			$users->limit(100);

			$list = $users->get();


			$rank = 1;
			foreach ($list as $row) {
				$keywords[] = strtolower($row->lastname . ' ' . $row->firstname);
				$names[]    = $rank . '. ' . ucwords(strtolower($row->lastname)) . ' ' . ucwords(strtolower($row->firstname));
				$rank++;
			}

			$lists = [
				0 => [
					'header'    => '',
					'subheader' => '',
					'list'      => $list,
				]
			];

		}


		$keywords = array_slice($keywords, 0, 10);
		$names    = array_slice($names, 0, 10);

		$subheader = 'Based from NCFP May 1, 2019 release.';

		$meta_description = $header . ' ' . implode(', ', $names);
		$meta_keywords    = '' . implode(',', $keywords);
		$title            = strtolower($header);

		$data = [
			'paginate'         => $paginate,
			'nav'              => $nav,
			'qs'               => $qs,
			'filter'           => $filter,
			'lists'            => $lists,
			'page'             => $request->input('page') ?? '1',
			'segments'         => $segments,
			'header'           => $header,
			'title'            => $title,
			'subheader'        => $subheader,
			'meta_description' => $meta_description,
			'meta_keywords'    => $meta_keywords,
			'title_colors'     => $this->title_colors(),

		];

		return view('rating.ncfprating', $data);
	}

	public function nav()
	{


		$nav = [
			'top100'  => [
				'Overall'          => 'ncfp/top100/all',
				'National Masters' => 'ncfp/top100/all/national-master',
				'Non-Masters'      => 'ncfp/top100/all/non-master',
				'60 and above'     => 'ncfp/top100/all/above/60',
				'20 and below'     => 'ncfp/top100/all/under/20',
				'18 and below'     => 'ncfp/top100/all/under/18',
				'16 and below'     => 'ncfp/top100/all/under/16',
				'14 and below'     => 'ncfp/top100/all/under/14',
				'12 and below'     => 'ncfp/top100/all/under/12',
				'10 and below'     => 'ncfp/top100/all/under/10',
				'8 and below'      => 'ncfp/top100/all/under/8',
				'6 and below'      => 'ncfp/top100/all/under/6',
			],
			'top100m' => [
				'Overall'      => 'ncfp/top100/men',
				'60 and above' => 'ncfp/top100/men/above/60',
				'20 and below' => 'ncfp/top100/men/under/20',
				'18 and below' => 'ncfp/top100/men/under/18',
				'16 and below' => 'ncfp/top100/men/under/16',
				'14 and below' => 'ncfp/top100/men/under/14',
				'12 and below' => 'ncfp/top100/men/under/12',
				'10 and below' => 'ncfp/top100/men/under/10',
				'8 and below'  => 'ncfp/top100/men/under/8',
				'6 and below'  => 'ncfp/top100/men/under/6',
			],
			'top100w' => [
				'Overall'      => 'ncfp/top100/women',
				'60 and above' => 'ncfp/top100/women/above/60',
				'20 and below' => 'ncfp/top100/women/under/20',
				'18 and below' => 'ncfp/top100/women/under/18',
				'16 and below' => 'ncfp/top100/women/under/16',
				'14 and below' => 'ncfp/top100/women/under/14',
				'12 and below' => 'ncfp/top100/women/under/12',
				'10 and below' => 'ncfp/top100/women/under/10',
				'8 and below'  => 'ncfp/top100/women/under/8',
				'6 and below'  => 'ncfp/top100/women/under/6',
			],

		];

		return $nav;
	}

	private function title_colors()
	{
		$title_colors = [
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

		return $title_colors;
	}

	public function store_ratings()
	{
		ini_set('max_execution_time', 3600);
		$file_n = Storage::url('rating_may_2019.csv');
		$file_n = Storage::disk('local')->path('rating_may_2019.csv');;
		$file     = fopen($file_n, "r");
		$all_data = array();
		$array    = array();
		$ctr      = 0;
		$arr_int  = [6, 7, 8, 9, 10, 11, 12, 14, 15, 16, 18, 19];
		while (($data = fgetcsv($file, 200, ",")) !== false) {
			if ($ctr == 0) {
				$ctr++;
				continue;
			}
			$byear   = NULL;
			$fide_id = NULL;
			$status  = 2;
			$gender  = NULL;

			if (isset($data[3])) {
				if ($data[3] !== '') {
					$gender = strtolower($data[3]);
				} else {
					$gender = NULL;
				}

			}

			if (isset($data[20])) {
				$byear = $data[20];
				if (trim($byear) == '') {
					$byear = NULL;
				}
			}

			if (isset($data[21])) {
				if ($data[21] !== 'i') {
					$status = 1;
				}

			}

			foreach ($arr_int as $i) {
				if (!isset($data[$i])) {
					$data[$i] = 0;
				} else {
					$data[$i] = $res = preg_replace("/[^0-9]/", "", $data[$i]);

				}
			}

			$ncfp_id = trim($data[0]) != '' ? trim($data[0]) : NULL;

			$users = DB::table('cph_ratings')->where('ncfp_id', $ncfp_id)->get();

			$standard_prov = NULL;
			$rapid_prov    = NULL;
			$blitz_prov    = NULL;

			if (isset($data[9]) && trim($data[9]) != '') {
				$standard_prov_det = explode('/', $data[9]);
				$standard_prov     = (integer)$standard_prov_det[0];
			}

			if (isset($data[13]) && trim($data[13]) != '') {
				$rapid_prov_det = explode('/', $data[13]);
				$rapid_prov     = (integer)$rapid_prov_det[0];
			}

			if (isset($data[17]) && trim($data[17]) != '') {
				$blitz_prov_det = explode('/', $data[17]);
				$blitz_prov     = (integer)$blitz_prov_det[0];
			}


			if ($users === NULL) {
				$insert = [
					'ncfp_id'        => trim($data[0]) != '' ? trim($data[0]) : NULL,
					//'fide_id'        => trim($fide_id) != '' ? trim($fide_id) : NULL,
					'firstname'      => trim($data[2]) != '' ? trim($data[2]) : NULL,
					'lastname'       => trim($data[1]) != '' ? trim($data[1]) : NULL,
					'gender'         => trim($gender) != '' ? $gender : NULL,
					'federation'     => trim($data[4]) != '' ? trim($data[4]) : NULL,
					'title'          => trim($data[5]) != '' ? trim($data[5]) : NULL,
					'standard'       => trim($data[6]) != '' ? trim($data[6]) : NULL,
					'standard_prov'  => $standard_prov,
					'standard_games' => trim($data[7]) != '' ? trim($data[7]) : NULL,
					'standard_k'     => trim($data[8]) != '' ? trim($data[8]) : NULL,
					'rapid'          => trim($data[10]) != '' ? trim($data[10]) : NULL,
					'rapid_prov'     => $rapid_prov,
					'rapid_games'    => trim($data[11]) != '' ? trim($data[11]) : NULL,
					'rapid_k'        => trim($data[12]) != '' ? trim($data[12]) : NULL,
					'blitz'          => trim($data[14]) != '' ? trim($data[14]) : NULL,
					'blitz_prov'     => $blitz_prov,
					'blitz_games'    => trim($data[15]) != '' ? trim($data[15]) : NULL,
					'blitz_k'        => trim($data[16]) != '' ? trim($data[16]) : NULL,
					'r960'           => trim($data[18]) != '' ? trim($data[18]) : NULL,
					'total_games'    => trim($data[19]) != '' ? trim($data[19]) : 0,
					'birthyear'      => $byear,
					'status'         => $status,
					'created_at'     => date('Y-m-d H:i:s'),
				];

				DB::table('cph_ratings')->insert($insert);
			} else {
				$update = [
					//'ncfp_id'        => trim($data[0]) != '' ? trim($data[0]) : NULL,
					//'fide_id'        => trim($fide_id) != '' ? trim($fide_id) : NULL,
					'firstname'      => trim($data[2]) != '' ? trim($data[2]) : NULL,
					'lastname'       => trim($data[1]) != '' ? trim($data[1]) : NULL,
					'gender'         => trim($gender) != '' ? $gender : NULL,
					'federation'     => trim($data[4]) != '' ? trim($data[4]) : NULL,
					'title'          => trim($data[5]) != '' ? trim($data[5]) : NULL,
					'standard'       => trim($data[6]) != '' ? trim($data[6]) : NULL,
					'standard_prov'  => $standard_prov,
					'standard_games' => trim($data[7]) != '' ? trim($data[7]) : NULL,
					'standard_k'     => trim($data[8]) != '' ? trim($data[8]) : NULL,
					'rapid'          => trim($data[10]) != '' ? trim($data[10]) : NULL,
					'rapid_prov'     => $rapid_prov,
					'rapid_games'    => trim($data[11]) != '' ? trim($data[11]) : NULL,
					'rapid_k'        => trim($data[12]) != '' ? trim($data[12]) : NULL,
					'blitz'          => trim($data[14]) != '' ? trim($data[14]) : NULL,
					'blitz_prov'     => $blitz_prov,
					'blitz_games'    => trim($data[15]) != '' ? trim($data[15]) : NULL,
					'blitz_k'        => trim($data[16]) != '' ? trim($data[16]) : NULL,
					'r960'           => trim($data[18]) != '' ? trim($data[18]) : NULL,
					'total_games'    => trim($data[19]) != '' ? trim($data[19]) : 0,
					'birthyear'      => $byear,
					'status'         => $status,
					'updated_at'     => date('Y-m-d H:i:s'),
				];
				DB::table('cph_ratings')->where('ncfp_id', $ncfp_id)->update($update);
			}
		}

		fclose($file);
	}

}