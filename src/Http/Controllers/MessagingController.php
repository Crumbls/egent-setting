<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionsStoreRequest;
use App\Http\Requests\SubscriptionsUpdateRequest;
use Illuminate\Support\Facades\Validator;

class MessagingController extends Controller
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

    }
}