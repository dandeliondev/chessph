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

		$titles = ['un', 'agm', 'nm', 'cm', 'fm', 'im', 'gm', 'wfm', 'wcm', 'wim', 'wgm'];
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
			$header   = 'NCFP Rating';
			$paginate = false;
			$filter   = true;

			$top_gainers  = DB::table('cph_ratings');
			$top_all      = DB::table('cph_ratings');
			$top_women    = DB::table('cph_ratings');
			$top_nm       = DB::table('cph_ratings');
			$top_untitled = DB::table('cph_ratings');
			$top_juniors  = DB::table('cph_ratings');
			$top_kiddies  = DB::table('cph_ratings');
			$top_title    = DB::table('cph_ratings');

			$top_gainers->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_all->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_women->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_nm->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_untitled->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_juniors->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_kiddies->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));
			$top_title->select(DB::raw('*,standard - standard_prev as increase,YEAR(CURDATE()) - YEAR(birthdate) as age,DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`birthDate`)), \'%Y\')+0 AS age2'));

			$top_women->where('gender', 'f');
			$top_nm->where('title', 'nm');
			$top_untitled->where('title', NULL);

			$top_juniors->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '>=', 13);
			$top_juniors->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', 20);

			$top_kiddies->where(DB::raw('YEAR(CURDATE()) - YEAR(birthdate)'), '<=', 12);

			$top_title->where(DB::raw('title'), '<>', DB::raw('title_prev'));

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
			$top_title->limit(1);

			$list_top_gainers  = $top_gainers->get();
			$list_top_all      = $top_all->get();
			$list_top_women    = $top_women->get();
			$list_top_nm       = $top_nm->get();
			$list_top_untitled = $top_untitled->get();
			$list_top_juniors  = $top_juniors->get();
			$list_top_kiddies  = $top_kiddies->get();
			$list_top_title    = $top_title->get();


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
					'header'    => 'Latest Title Awardee',
					'subheader' => '',
					'list'      => $list_top_title,
				],
				7 => [
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

		$subheader = 'Based from NCFP December 1, 2019 release.';

		$meta_description = $header . ' ' . implode(', ', $names);
		$meta_keywords    = '' . implode(',', $keywords);
		$title            = ucwords(strtolower($header));

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
		/*
		 * STEP 1
				UPDATE cph_ratings
				SET title_prev = title,standard_prev=standard,rapid_prev=rapid,blitz_prev=blitz,f960_prev=f960

			Step 2
				Set arr int

			Step 3
				Set col_ values

		 */


		ini_set('max_execution_time', 3600);
		//$file_n = Storage::url('rating_nov2_2019.csv');
		$file_n = Storage::disk('local')->path('rating_dec_2019.csv');;
		$file = fopen($file_n, "r");
		$ctr  = 0;

		$inserts = [];
		while (($data = fgetcsv($file, 200, ",")) !== false) {
			if ($ctr == 0) {
				$ctr++;
				continue;
			}

			$byear   = NULL;
			$bdate   = NULL;
			$fide_id = NULL;
			$status  = 2;
			$gender  = NULL;

			$col_gender     = 3;
			$col_bdate      = 23;
			$col_status     = 24;
			$col_fide       = 25;
			$col_ncfp_id    = isset($data[0]) ?? '';
			$col_lastname   = isset($data[1]) ?? '';
			$col_firstname  = isset($data[2]) ?? '';
			$col_federation = isset($data[4]) ?? '';
			$col_title      = isset($data[5]) ?? '';
			$col_standard   = isset($data[6]) ?? '';
			$col_rapid      = isset($data[10]) ?? '';
			$col_blitz      = isset($data[14]) ?? '';
			$col_f960       = isset($data[18]) ?? '';

			$arr_int = [$col_standard, $col_rapid, $col_blitz, $col_f960];

			if (isset($data[$col_gender])) {
				if ($data[$col_gender] !== '') {
					$gender = strtolower($data[$col_gender]);
				} else {
					$gender = NULL;
				}

			}

			if (isset($data[$col_bdate])) {
				$bdate = trim($data[$col_bdate]);
				if (trim($bdate) == '') {
					$bdate = NULL;
				} else {
					$details_bdate = explode('/', $bdate);

					if (count($details_bdate) === 3) {

						$bdate = $details_bdate[2] . '-' . $details_bdate[1] . '-' . $details_bdate[0];
						if (!$this->validateDate($bdate)) {
							$bdate = NULL;
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

			$ncfp_id = $this->sanitize($col_ncfp_id) != '' ? $this->sanitize($col_ncfp_id) : NULL;

			$users = DB::table('cph_ratings')->where('ncfp_id', $ncfp_id)->get();

			if ($users->count() === 0) {
				$insert    = [
					'ncfp_id'    => $this->sanitize($col_ncfp_id) != '' ? $this->sanitize($col_ncfp_id) : NULL,
					'fide_id'    => $this->sanitize($fide_id) != '' ? $this->sanitize($fide_id) : NULL,
					'firstname'  => $this->sanitize($col_firstname) != '' ? $this->sanitize($col_firstname) : NULL,
					'lastname'   => $this->sanitize($col_lastname) != '' ? $this->sanitize($col_lastname) : NULL,
					'gender'     => $this->sanitize($gender) != '' ? $gender : NULL,
					'federation' => $this->sanitize($col_federation) != '' ? $this->sanitize($col_federation) : NULL,
					'title'      => $this->sanitize($col_title) != '' ? $this->sanitize($col_title) : NULL,
					'standard'   => $this->sanitize($col_standard) != '' ? $this->sanitize($col_standard) : NULL,
					'rapid'      => $this->sanitize($col_rapid) != '' ? $this->sanitize($col_rapid) : NULL,
					'blitz'      => $this->sanitize($col_blitz) != '' ? $this->sanitize($col_blitz) : NULL,
					'f960'       => $this->sanitize($col_f960) != '' ? $this->sanitize($col_f960) : NULL,
					'birthdate'  => $bdate,
					'birthyear'  => $byear,
					'status'     => $status,
					'updated_at' => date('Y-m-d H:i:s'),
					'created_at' => date('Y-m-d H:i:s'),
				];
				$inserts[] = $insert;
				DB::table('cph_ratings')->insert($insert);
			} else {
				$update = [

					//'ncfp_id'        => $this->sanitize($col_ncfp_id) != '' ? $this->sanitize($col_ncfp_id) : NULL,
					'fide_id'    => $this->sanitize($fide_id) != '' ? $this->sanitize($fide_id) : NULL,
					//'firstname'      => $this->sanitize($col_firstname) != '' ? $this->sanitize($col_firstname) : NULL,
					//'lastname'       => $this->sanitize($col_lastname) != '' ? $this->sanitize($col_lastname) : NULL,
					'gender'     => $this->sanitize($gender) != '' ? $gender : NULL,
					'federation' => $this->sanitize($col_federation) != '' ? $this->sanitize($col_federation) : NULL,
					'title'      => $this->sanitize($col_title) != '' ? $this->sanitize($col_title) : NULL,
					'standard'   => $this->sanitize($col_standard) != '' ? $this->sanitize($col_standard) : NULL,
					'rapid'      => $this->sanitize($col_rapid) != '' ? $this->sanitize($col_rapid) : NULL,
					'blitz'      => $this->sanitize($col_blitz) != '' ? $this->sanitize($col_blitz) : NULL,
					'f960'       => $this->sanitize($col_f960) != '' ? $this->sanitize($col_f960) : NULL,
					'birthdate'  => $bdate,
					'birthyear'  => $byear,
					'status'     => $status,
					'updated_at' => date('Y-m-d H:i:s'),
				];
				DB::table('cph_ratings')->where('ncfp_id', $ncfp_id)->update($update);
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