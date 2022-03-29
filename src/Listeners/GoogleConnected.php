<?php

namespace Egent\Setting\Listeners;

use App\Helpers\Slack;
use Egent\Setting\Events\GoogleConnected as Event;

class GoogleConnected
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
		$client = $event->client;
	    $user->settings->set('calendar_enabled', true);
	    $user->settings->set('calendar_provider', 'Google');

	    $service = new \Google_Service_Calendar($client);

		try {
			$calendars = collect($service->calendarList->listCalendarList()->items);
			$calendar = $calendars->first(function($calendar) {
				if ($calendar->accessRole != 'owner') {
					return false;
				}
				return $calendar->summary == 'eGent Events';
			});
			if (!$calendar) {
				// c_jv4b1vl6e6m40uijn4fvchumhc@group.calendar.google.com
				$calendar = new \Google_Service_Calendar_Calendar();
				$calendar->setSummary('eGent Events');
				$calendar->setTimeZone('America/Denver');
				$calendar = $service->calendars->insert($calendar);
			}
			$id = $calendar->id;
			$user->settings->set('calendar_id', $id);
		} catch (\Throwable $e) {
			dd($e);
		}

    }

}
