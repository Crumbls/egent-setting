<?php

namespace Egent\Setting\Http\Controllers\Template;

use Egent\Setting\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
	    $user = \Auth::user();
	    abort_if(!$user, 403);

	    return view('setting::template.index', ['user' => $user]);
    }
}
