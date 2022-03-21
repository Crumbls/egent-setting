<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionsStoreRequest;
use App\Http\Requests\SubscriptionsUpdateRequest;
use Illuminate\Support\Facades\Validator;

class TransactionCoordinatorController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

	/**
	 * @param Request $request
	 */
	public function store(Request $request) {

	}
}