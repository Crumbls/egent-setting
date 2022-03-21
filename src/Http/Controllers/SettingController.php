<?php

namespace App\Http\Controllers\Setting;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionsStoreRequest;
use App\Http\Requests\SubscriptionsUpdateRequest;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Auth::user();
        abort_if(!$user, 403);

        return view('app.settings.index',
            [
                'user' => \Auth::user()
            ]);
    }

    /**
     * Handle the messaging settings.
     * TODO: This should be moved to it's own controller, but we don't have the time for it now.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function messaging(Request $request) {
        if ($request->isMethod('GET')) {
            echo 'get';
            exit;
        }
        echo 'post';
        exit;
    }

    /**
     * Handle the template settings.
     * TODO: This should be moved to it's own controller, but we don't have the time for it now.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function template(Request $request) {
        if ($request->isMethod('GET')) {
            echo 'get';
            exit;
        }
        echo 'post';
        exit;
    }

}