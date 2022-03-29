<?php

namespace Egent\Setting\Http\Controllers\Connect;

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
	    $user = \Auth::user();
	    abort_if(!$user, 403);

		$providers = \egentCalendar::getInstalledDrivers();
		unset($providers['Local']);
		$providers = array_keys($providers);

	    $validator = Validator::make($request->all(), [
		    'provider' => [
				'required',
			    'string',
			    'in:'.implode(',',$providers)
		    ]
	    ]);

	    $validator->validate();
	    $data = $validator->validated();
		$driver = \egentCalendar::driver($data['provider']);

		return $driver->initialize();
    }

}
