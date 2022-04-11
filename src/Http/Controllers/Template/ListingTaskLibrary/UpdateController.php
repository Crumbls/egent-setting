<?php

namespace Egent\Setting\Http\Controllers\Template\ListingTaskLibrary;

use Egent\Setting\Http\Controllers\Controller;
use Egent\Setting\Models\ListingTask;
use Egent\Setting\Models\ListingTaskLibrary;
use Egent\Setting\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdateController extends Controller
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
		    ->limit(100) // TODO: Add in a creation limit.
		    ->get()
	        ->keyBy('id');

		$validator = Validator::make($request->all(), [
			'entities' => [
				'required',
				'array'
			],
			'entities.*' => [
				'sometimes',
				'numeric',
				'in:'.$entities->pluck('id')->implode(',')
			]
		]);

		$validator->validate();
		$data = $validator->validated();

		// Remove entities that no longer exist here.
	    foreach($entities as $entity) {
			if (!in_array($entity->getKey(), $data['entities'])) {
				$entity->delete();
			}
	    }

		foreach($data['entities'] as $ord => $eid) {
			$entity = $entities->get($eid);
			$entity->ord = $ord+1;
			$entity->save();
		}

		return response()->json(['entities' => $entities]);
    }
}
