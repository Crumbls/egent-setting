<?php

namespace App\Http\Controllers\Setting;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionsStoreRequest;
use App\Http\Requests\SubscriptionsUpdateRequest;
use Illuminate\Support\Facades\Validator;

class ConnectController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Auth::user();
        abort_if(!$user, 403);

	    flash('This feature is coming soon.', 'success');

	    return redirect()->back();

	    abort(500);
        return view('app.settings.index',
            [
                'user' => \Auth::user()
            ]);
    }
}