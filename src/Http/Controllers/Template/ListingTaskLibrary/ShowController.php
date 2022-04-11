<?php

namespace Egent\Setting\Http\Controllers\Template\ListingTaskLibrary;

use Egent\Setting\Http\Controllers\Controller;
use Egent\Setting\Models\ListingTask;
use Egent\Setting\Models\ListingTaskLibrary;
use Egent\Setting\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ListingTaskLibrary $listingTaskLibrary)
    {
	    $user = \Auth::user();
	    abort_if(!$user, 403);
		abort_if(!$listingTaskLibrary->exists, 404);
		$entities = ListingTask::where('user_id', $user->getKey())
			->where('listing_task_library_id', $listingTaskLibrary->getKey())
			->get();
		return response()->json(['entities' => $entities]);
    }
}
