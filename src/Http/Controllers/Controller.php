<?php

namespace Egent\Setting\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
	    view()->startPush('css', view('laravel-trix::trixassets')->render());
        view()->startPush('js', sprintf('<script src="%s" defer></script>', asset('/js/setting.js')));
    }
}
