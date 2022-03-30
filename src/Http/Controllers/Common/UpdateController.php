<?php

namespace Egent\Setting\Http\Controllers\Common;

use Egent\Setting\Http\Controllers\Controller;

use App\Models\CtmCredentials;
use App\Models\UserSignature;
use Egent\Setting\Events\Message\Created;
use Egent\Setting\Models\Metadata;
use Egent\Setting\Models\Setting;
use Egent\Setting\Rules\Recipients;
use Illuminate\Http\Request;
use Egent\Setting\Models\Thread;
use Illuminate\Support\Facades\Validator;


class UpdateController extends Controller
{

	private $user;

	private $rules;

	/**
	 * Save our resource.
	 * TODO: I'd like to move this so it supports multiple types easily.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(Request $request)
	{
		$user = \Auth::user();
		abort_if(!$user, 403);

		$input = $request->all();

		$rules = array_map(function ($e) {
			return [
				'sometimes',
				'nullable',
				$e->type
			];
		}, $this->getRules());

		$rules['ctm'] = ['sometimes','array'];
		$rules['ctm.username'] = ['sometimes','nullable','string', 'max:256', 'min:3'];
		$rules['ctm.password'] = ['sometimes','nullable','string', 'max:256', 'min:3'];

		$rules['signature_changed'] = [
			'sometimes',
			'numeric',
			'in:0,1'
		];
		$rules['signature'] = [
			'sometimes',
			'nullable',
			'required_if:signature_changed,1',
			'base64image'
		];
		/*
				echo 'this is being rewritten to support a dot notation syntax.  Check back tonight.';
		dd($request->all());
				$validator = Validator::make($request->all(), [
					'extended' => [
						'sometimes',
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
		*/

		$validator = Validator::make($input, $rules);

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

		if (!array_key_exists('extended', $data)) {
			$data['extended'] = [];
		}

		$this->user = $user;

		// Handle ctm credentials, if necessary.
		if (array_key_exists('ctm', $data)) {
			$data['ctm'] = array_filter($data['ctm']);
			if (array_key_exists('username', $data['ctm']) && !array_key_exists('password', $data['ctm'])) {
				// Do not change.
			} else if (count($data['ctm']) != 2) {
				if ($temp = $user->ctmCredentials) {
					$temp->delete();
				}
			} else {
				$temp = $user->ctmCredentials ? $user->ctmCredentials : $user->ctmCredentials()->create($data['ctm']);
				if (!$temp->wasRecentlyCreated) {
					foreach($data['ctm'] as $k => $v) {
						$temp->$k = $v;
					}
					$temp->save();
				}
			}
			unset($data['ctm']);
		}

		// Remove extended.
		unset($data['extended']);

		// Convert to dot syntax for saving.
		$data = \Arr::dot($data);
		array_walk_recursive($data, [$this, 'save']);

		flash('Settings saved.', 'success');

		return redirect()->back();
	}

	protected function getRules(): array
	{
		if ($this->rules) {
			return $this->rules;
		}
		$temp = with(new Metadata);
		$table = $temp->getTable();
		$connection = $temp->getConnection();
		$this->rules = $connection->table($table)->select(['id', 'name', 'type', 'is_enabled', 'default'])->get()->keyBy('name')->toArray();
		return $this->rules;
	}

	private function save($value, $key)
	{
		if ($temp = $this->user->settings->get($key)) {
			if ($temp->value != $value) {
				$temp->value = $value;
				$temp->save();
			}
		} else if ($temp = $this->rules[$key]) {
			$setting = new Setting();
			$setting->metadata_id = $temp->id;
			$setting->settable_type = get_class($this->user);
			$setting->settable_id = $this->user->getKey();
			$setting->is_enabled = $temp->is_enabled;
			$setting->value = $value;
			$setting->save();
		}
	}
}
