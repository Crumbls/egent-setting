<?php

namespace Egent\Setting\Events\TransactionCoordinator;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class Disconnected
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\MustVerifyEmail  $user
     * @return void
     */
    public function __construct(public User $user, public User $tc) {}
}