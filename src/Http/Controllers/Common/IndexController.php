<?php

namespace Egent\Setting\Http\Controllers\Common;

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

	    $views = $user
		    ->roles
		    ->sortByDesc('level')
		    ->pluck('name')
		    ->map(function ($e) {
			    return 'setting::' . $e;
		    });
	    $views->push('setting::index');

	    $data = [
		    'user' => $user,
		    'signature' => '',
		    'data' => (array)$user->extended
	    ];

	    $signature = $user->signatures->first();

	    if ($signature) {
		    $data['signature'] = $signature->getBase64();
		    $data['signature_width'] = $signature->width;
		    $data['signature_height'] = $signature->height;
	    }

	    /**
	     * I hate this, but it's necessary short term until we finish adding all fields.
	     */

	    return view()->first($views->toArray(), $data);
    }
}
