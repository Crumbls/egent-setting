<?php

namespace Egent\Setting\Http\Controllers\Messaging;

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

	    $validator = Validator::make($request->all(), [
		    'extended' => [
			    'required',
			    'array'
		    ],
		    'extended.listing_active' => [
			    'sometimes',
			    'numeric',
			    'min:0',
			    'max:100'
		    ],
		    'extended.client_new' => [
			    'sometimes',
			    'numeric',
			    'min:0',
			    'max:100'
		    ],
		    'extended.under_contract' => [
			    'sometimes',
			    'numeric',
			    'min:0',
			    'max:100'
		    ],
		    'extended.listing_closed' => [
			    'sometimes',
			    'numeric',
			    'min:0',
			    'max:100'
		    ],
		    'extended.ctm_skip' => [
			    'sometimes',
			    'boolean'
		    ],
		    'notification_deadline' => [
			    'sometimes',
			    'numeric',
			    'in:0,1'
		    ],
		    'notification_contract_created' => [
			    'sometimes',
			    'numeric',
			    'in:0,1'
		    ],
		    'notification_contract_signed' => [
			    'sometimes',
			    'numeric',
			    'in:0,1'
		    ],
		    'notification_contract_sent' => [
			    'sometimes',
			    'numeric',
			    'in:0,1'
		    ],
		    'notification_comment_created' => [
			    'sometimes',
			    'numeric',
			    'in:0,1'
		    ],
		    'notification_contract_signed_fully' => [
			    'sometimes',
			    'numeric',
			    'in:0,1'
		    ],
		    'notification_property_status_changed' => [
			    'sometimes',
			    'numeric',
			    'in:0,1'
		    ],
		    'notification_upcoming_events_tasks' => [
			    'sometimes',
			    'numeric',
			    'in:0,1'
		    ],
		    'ctm' => [
			    'sometimes',
			    'array'
		    ],
		    'ctm.username' => [
			    'sometimes',
			    'nullable',
			    'string',
			    'min:1',
			    'max:256'
		    ],
		    'ctm.password' => [
			    'sometimes',
			    'nullable',
			    'string',
			    'min:1',
			    'max:256'
		    ],
		    'signature_changed' => [
			    'sometimes',
			    'numeric',
			    'in:0,1'
		    ],
		    'signature' => [
			    'sometimes',
			    'nullable',
			    'required_if:signature_changed,1',
			    'base64image'
		    ]
	    ]);

	    /**
	     * TODO: Add in remaining rules.
	     */

	    $validator->validate();
	    $data = $validator->validated();

	    // TODO: Move this to the model.  This data is very sensitive and needs to be protected.
	    if (array_key_exists('signature_changed', $data) && $data['signature_changed']) {
		    if (true) {
			    $signature = new UserSignature();
			    $valid = $signature->set($data['signature']);
			    if ($valid) {
				    $signature->user()->associate($user);
				    $signature->save();
			    } else {
				    // TODO: Add in validation exception.
			    }
		    }
	    }
	    unset($data['signature']);
	    unset($data['signature_changed']);

	    $temp = array_merge((array)$user->extended, $data['extended']);

	    if (array_key_exists('ctm_skip', $data['extended'])) {
		    if ($data['extended']['ctm_skip'] && $data['extended']['ctm_skip']) {
			    unset($data['ctm']);
			    $temp['ctm_skip'] = 1;
		    } else {
			    unset($temp['ctm_skip']);
		    }
	    } else {
		    unset($temp['ctm_skip']);
	    }

	    /**
	     * This is an ugly way to patch data into extended.
	     */
	    $ext = array_diff_key($data, $user->getColumnNames());
	    unset($ext['extended']);
	    $temp = array_merge($temp, $ext);
	    foreach($ext as $k => $ign) {
		    unset($data[$k]);
	    }

	    $user->extended = $temp;

	    unset($data['signature_changed']);
	    unset($data['extended']);

	    if (array_key_exists('ctm', $data)
		    && array_key_exists('username', $data['ctm'])
		    && array_key_exists('password', $data['ctm'])
		    && $data['ctm']['username']
		    && $data['ctm']['password']
	    ) {
		    if ($credentials = $user->ctmCredentials) {
			    $credentials->username = $data['ctm']['username'];
			    $credentials->password = $data['ctm']['password'];
			    $credentials->save();
//                Slack::getSlack()->to('#notifications')->send(sprintf('%s just updated their CTM credentials.', $user->email));
		    } else {
			    $credentials = new CtmCredentials();
			    $credentials->username = $data['ctm']['username'];
			    $credentials->password = $data['ctm']['password'];
			    $user->ctmCredentials()->save($credentials);
//                Slack::getSlack()->to('#notifications')->send(sprintf('%s just added their CTM credentials.', $user->email));
		    }
	    } else if ($user->ctmCredentials) {
		    $user->ctmCredentials()->delete();
//            Slack::getSlack()->to('#notifications')->send(sprintf('%s just removed their CTM credentials.', $user->email));
	    }

	    unset($data['ctm']);



	    foreach ($data as $k => $v) {
		    $user->$k = $v;
	    }

	    $user->save();

	    flash('Settings saved.', 'success');

	    return redirect()->back();
    }

}
