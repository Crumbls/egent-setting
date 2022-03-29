<?php
namespace Egent\Setting;

use App\Models\User;

class Setting
{
	public function for(User $user) {
		return $user->settings;
	}
}