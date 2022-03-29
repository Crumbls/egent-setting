<?php

namespace Egent\Setting\Http\Controllers\Messaging;

use App\Models\User;
use App\Models\UserEmailSignature;
use App\Models\UserResponder;
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
			'signature' => [
				'sometimes',
				'array'
			],
		    'signature.enabled' => [
			    'sometimes',
			    'in:0,1'
		    ],
		    'signature.attach_contract' => [
			    'sometimes',
			    'in:0,1'
		    ],
		    'useremailsignature-trixFields' => [
			    'sometimes',
			    'array',
		    ],
		    'useremailsignature-trixFields.content' => [
			    'sometimes',
			    'nullable'
		    ],
		    'attachment-useremailsignature-trixFields' => [
			    'sometimes',
			    'array'
		    ],
		    'responder' => [
			    'sometimes',
			    'array'
		    ],
		    'responder.enabled' => [
			    'sometimes',
			    'in:0,1'
		    ],
		    'responder.start_at' => [
			    'sometimes',
			    'nullable',
			    'date'
		    ],
		    'responder.end_at' => [
			    'sometimes',
			    'nullable',
			    'date'
		    ],
		    'responder.subject' => [
			    'sometimes',
			    'nullable',
			    'string',
			    'min:1',
			    'max:256'
		    ],
		    'userresponder-trixFields' => [
				'sometimes',
			    'array',
		    ],
		    'userresponder-trixFields.content' => [
			    'sometimes',
			    'nullable'
		    ],
		    'attachment-userresponder-trixFields' => [
			    'sometimes',
			    'array'
		    ],
	    ]);


	    $validator->validate();
	    $data = $validator->validated();

	    /**
	     * Convert attachment fields.
	     */
		$temp = preg_grep('#^attachment\-(.*?)\-trixFields$#', array_keys($data));
		foreach($temp as $k) {
			$data[$k] = array_filter(array_map(function($e) {
				try {
					$e = json_decode($e,true);
					if (!$e) {
						return false;
					}
				} catch (\Throwable $e) {
					$e = false;
				}
				return $e;
			}, $data[$k]));

			$data[$k] = call_user_func_array('array_merge', array_values($data[$k]));
			$data[$k] = array_unique($data[$k]);
			$data[$k] = array_filter(array_map(function($file) {
				$file = storage_path('app/public/' . $file);
				if (!file_exists($file)) {
					return null;
				}
				try {
					$mimeType = mime_content_type($file);
					if (preg_match('#^image\/#', $mimeType)) {
						return $file;
					} else if ($mimeType == 'application/pdf') {
						return $file;
					}
					return null;
				} catch (\Throwable $e) {
					return null;
				}
				return $file;
			}, $data[$k]));

		}

	    /**
	     * Process responder.
	     */
	    if (!array_key_exists('responder', $data) || !is_array($data['responder'])) {
		    $data['responder'] = [];
	    }
	    if (array_key_exists('userresponder-trixFields', $data) && is_array($data['userresponder-trixFields'])) {
		    $data['responder']['body'] = implode(PHP_EOL, $data['userresponder-trixFields']);
	    }
	    $data['responder']['enabled'] = array_key_exists('enabled', $data['responder']) ? (bool)$data['responder']['enabled'] : false;
	    unset($data['userresponder-trixFields']);
	    if (array_key_exists('attachment-userresponder-trixFields', $data)) {
		    $data['responder']['attachments'] = $data['attachment-userresponder-trixFields'];
	    }
	    unset($data['attachment-userresponder-trixFields']);

	    $this->handleResponder($user, $data['responder']);

	    /**
	     * Process signature.
	     */
	    if (!array_key_exists('signature', $data) || !is_array($data['signature'])) {
		    $data['signature'] = [];
	    }
	    if (array_key_exists('useremailsignature-trixFields', $data) && is_array($data['useremailsignature-trixFields'])) {
		    $data['signature']['body'] = implode(PHP_EOL, $data['useremailsignature-trixFields']);
	    }
	    $data['signature']['enabled'] = array_key_exists('enabled', $data['signature']) ? (bool)$data['signature']['enabled'] : false;
	    unset($data['useremailsignature-trixFields']);
	    if (array_key_exists('attachment-useremailsignature-trixFields', $data)) {
		    $data['signature']['attachments'] = $data['attachment-useremailsignature-trixFields'];
	    }
	    unset($data['attachment-useremailsignature-trixFields']);

	    $this->handleSignature($user, $data['signature']);
	    //$this->handleResponder($data['responder']);

	    flash('Settings saved.', 'success');

	    return redirect()->back();
    }

	protected function handleSignature(User $user, array $input) : UserEmailSignature {
		$trixText = null;
		unset($input['attachments']);
		$trixText = null;

		$entity = $user->emailSignature->firstOrCreate();
		if ($entity->wasRecentlyCreated) {
			$entity->save();
		}
		$trixText = $entity->trixRichText()->firstOrCreate(['field' => 'body']);
		$trixText->content = $input['body'];
		$trixText->saveQuietly();
		unset($input['body']);
		foreach($input as $k => $v) {
			$entity->$k = $v;
		}
		$entity->save();
		return $entity;
	}

	protected function handleResponder(User $user, array $input) : UserResponder {
		$trixText = null;
		if (!$entity = $user->automaticResponder) {
			$entity = $user->automaticResponder()->create();
			$trixText = $entity->trixRichText()->create([
				'field' => 'body'
			]);
		}
		unset($input['attachments']);
		$trixText = $entity->trixRichText->first();
		$trixText->content = $input['body'];
		$trixText->saveQuietly();
		unset($input['body']);
		foreach($input as $k => $v) {
			$entity->$k = $v;
		}
		$entity->save();
		return $entity;
	}
}
