<?php

namespace Egent\Setting\Policies;

use App\Models\User;
use Egent\Office\Models\Office;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;


	/**
	 * Determine whether the user has CTM account settings.
	 *
	 * @deprecated
	 * @param  App\Models\User  $user
	 * @param  Egent\Office\Models\Office  $model
	 * @return mixed
	 */
	public function ctm(User $user = null)
	{
		echo __METHOD__;
		return false;
		if ($user->can('create', Office::class)) {
			return true;
		}

		return false;
	}

	public function messaging() : bool {
		return true;
	}

	public function __call($method, $args)
	{
		echo $method;
		return true;
		if (!in_array($method, array_keys($this->functions))) {
			throw new BadMethodCallException();
		}

		array_unshift($args, $this->s);

		return call_user_func_array($this->functions[$method], $args);
	}
}
