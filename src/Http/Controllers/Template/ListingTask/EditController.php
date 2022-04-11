<?php
namespace Egent\Setting\Http\Controllers\Template\ListingTask;


use Egent\Setting\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @deprecated
 */
class EditController extends Controller
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

	    flash('This feature is coming soon!.', 'success');
		return redirect()->back();
    }
}
