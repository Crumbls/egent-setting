<?php

namespace Egent\Setting\Http\Controllers\Connect;

use App\Models\User;
use App\Models\UserToken;
use Egent\Setting\Events\GoogleConnected;
use Egent\Setting\Http\Controllers\Controller;

use App\Models\CtmCredentials;
use App\Models\UserSignature;
use Egent\Setting\Events\Message\Created;
use Egent\Setting\Rules\Recipients;
use Illuminate\Http\Request;
use Egent\Setting\Models\Thread;
use Illuminate\Support\Facades\Validator;


class GoogleController extends Controller
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

		$validator = Validator::make($request->all(), [
			'code' => ['string','required'],
			'token' => ['sometimes','string'],
			'error' => ['sometimes','string'],
		]);

		$validator->validate();
		$data = $validator->validated();
		$driver = \Calendar::driver('google');
		$client = $driver->getClient();

		$token = null;
		if (array_key_exists('code', $data)) {
			$client->authenticate($data['code']);
			$token = $client->getAccessToken();
			if (!$token) {
				$url = $client->createAuthUrl();
				return redirect($url);
			}
			$entity = UserToken::firstOrNew(['user_id' => $user->getKey(), 'client' => 'google']);
			if (!$entity->exists) {
				$entity->user()->associate($user);
			}

			if (array_key_exists('created', $token)) {
				$token['created_at'] = $token['created'];
				unset($token['created']);
			}

			foreach($token as $k => $v) {
				$entity->$k = $v;
			}
			$entity->save();

		} else if ($token = $user->tokens()->where('client','google')->inRandomOrder()->take(1)->first()) {
			$client->setAccessToken($token->toArray());
		}

	    if ($token = $client->getAccessToken()) {
			GoogleConnected::dispatch($user, $client);
	    } else {
		    $url = $client->createAuthUrl();
		    return redirect($url);
	    }

		flash(__('Google Calendar connected.'), 'success');

		return redirect()->route('settings.index');
    }

}
