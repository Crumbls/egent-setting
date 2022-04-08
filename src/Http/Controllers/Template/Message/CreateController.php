<?php
namespace Egent\Setting\Http\Controllers\Template\Message;


use Egent\Setting\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @deprecated
 */
class CreateController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
	    $user = \Auth::user();
	    abort_if(!$user, 403);

	    return view('setting::template.message.create', ['user' => $user]);
    }
}
