<?php

namespace Egent\Setting\Listeners;

use App\Helpers\Slack;
use Egent\Setting\Events\TransactionCoordinator\Disconnected as Event;


class TCDisconnected
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Event $event)
    {
		$user = $event->user;
		$tc = $event->tc;
	    Slack::getSlack()->to('#notifications')->send(sprintf('%s has disconnected from %s as their Transaction Coordinator.', $user->email, $tc->email));
    }

}
