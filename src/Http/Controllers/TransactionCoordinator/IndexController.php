<?php

namespace Egent\Setting\Http\Controllers\TransactionCoordinator;

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
		$this->authorize('setting-transaction-coordinator');

	    return view('setting::connect-transaction-coordinator', [ 'user' => $user ]);
    }
}
