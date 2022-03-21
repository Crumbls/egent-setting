<?php

namespace Egent\Setting\Http\Controllers\TransactionCoordinator;

use Egent\Setting\Http\Controllers\Controller;

use App\Models\CtmCredentials;
use App\Models\UserSignature;
use Egent\Setting\Events\Message\Created;
use Egent\Setting\Rules\Recipients;
use Illuminate\Http\Request;
use Egent\Setting\Models\Thread;
use Illuminate\Support\Facades\Validator;


class UpdateController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
	    /**
	     * TODO: Add in authorization.
	     */
	    $validator = Validator::make($request->all(), [
		    'transactionCoordinator' => [
			    'sometimes',
			    'nullable',
			    'uuid',
			    'exists:users,uuid'
		    ]
	    ]);
	    $data = $validator->validated();
	    $user = \Auth::user();
	    if (!array_key_exists('transactionCoordinator', $data) || !$data['transactionCoordinator']) {
		    foreach($user->transactionCoordinators as $tc) {
			    $user->transactionCoordinators()->detach($tc);
			    flash('Transaction Coordinator disconnected.', 'success');
		    }
	    } else {
		    $tc = User::where('uuid', $data['transactionCoordinator'])->firstOrFail();

		    if (!$user->transactionCoordinators->pluck('uuid')->contains($data['transactionCoordinator'])) {
			    $user->transactionCoordinators()->sync([$tc->getKey()]);
		    }

		    flash('Transaction Coordinator connected.', 'success');
	    }

	    return redirect()->back();
    }

}
