<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
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