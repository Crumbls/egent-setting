<?php

namespace Egent\Setting\Http\Controllers\Template\ListingTaskLibrary;

use Egent\Setting\Http\Controllers\Controller;
use Egent\Setting\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
		return response()->json([
			'entities' =>
			\Egent\Setting\Models\ListingTaskLibrary::all()->map(function($e) {
				return ['value' => $e->getKey(), 'label' => $e->name];
			})
		]);
    }
}
