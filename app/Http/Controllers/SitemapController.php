<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Http\Request;
use View;
use Response;

class SitemapController extends Controller
{
	/**
	 * Show the profile for the given user.
	 *
	 * @param  int $id
	 * @return View
	 */

	public function index(Request $request)
	{

		$somevar = '';//might be useful for dyncamic sitemap
		$content = View::make('sitemap.main')->with('somevar', $somevar);

		return Response::make($content, '200')->header('Content-Type', 'text/xml');

		//return view('sitemap.main');
	}


}