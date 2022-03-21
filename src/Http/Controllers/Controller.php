<?php

namespace Egent\Setting\Http\Controllers;

use App\Models\User;
use App\Models\Participant;
use Egent\Setting\Models\Thread;
//use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    protected $threadClass, $participantClass;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->threadClass = Thread::class;
        $this->participantClass = Participant::class;

        view()->startPush('js', sprintf('<script src="%s" defer></script>', asset('/js/notification.js')));
	    view()->startPush('css', view('laravel-trix::trixassets')->render());
    }
}
