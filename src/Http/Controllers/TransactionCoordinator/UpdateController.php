<?php

namespace Egent\Setting\Http\Controllers\TransactionCoordinator;

use Egent\Setting\Events\TransactionCoordinator\Connected;
use Egent\Setting\Events\TransactionCoordinator\Disconnected;
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
		$class = get_class($user);

	    if (!array_key_exists('transactionCoordinator', $data) || !$data['transactionCoordinator']) {
		    foreach($user->transactionCoordinators as $tc) {
			    $user->transactionCoordinators()->detach($tc);

				Disconnected::dispatch($user, $tc);

			    flash('Transaction Coordinator disconnected.', 'success');
		    }
	    } else {
		    $tc = $class::where('uuid', $data['transactionCoordinator'])->firstOrFail();

			$existing = $user->transactionCoordinators->keyBy('uuid');

			foreach($existing->except($tc->uuid) as $dc) {
				Disconnected::dispatch($user, $dc);
			}

			if (!$existing->has($tc->uuid)) {
				$user->transactionCoordinators()->sync([$tc->getKey()]);
				Connected::dispatch($user, $tc);
			}

		    flash('Transaction Coordinator connected.', 'success');
	    }

	    return redirect()->back();
    }

}
