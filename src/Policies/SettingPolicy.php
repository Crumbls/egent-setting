<?php

namespace Egent\Setting\Policies;

use App\Models\User;
use Egent\Office\Models\Office;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

	public function calendar() : bool {
		return true;
	}

	/**
	 * Determine whether the user has CTM account settings.
	 *
	 * @param  App\Models\User  $user
	 * @param  Egent\Office\Models\Office  $model
	 * @return mixed
	 */
	public function ctm(User $user = null) : bool
	{
		return true;
		if ($user->can('create', Office::class)) {
			return true;
		}

		return false;
	}

	public function goalMonthly() : bool {
		return true;
	}

	public function messaging() : bool {
		return false;
	}
	public function notification() : bool {
		return true;
	}
	public function template() : bool {
		return false;
	}

	/**
	 * Can the user link to a transaction coordinator.
	 * @param User $user
	 * @return bool
	 */
	public function transactionCoordinator(User $user) : bool {
		$roles = $user->roles->pluck('name');
		return $roles->contains('agent') || $roles->contains('broker');
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
