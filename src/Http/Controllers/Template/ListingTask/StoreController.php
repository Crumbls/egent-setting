<?php
namespace Egent\Setting\Http\Controllers\Template\ListingTask;


use Egent\Setting\Http\Controllers\Controller;
use Egent\Setting\Models\ListingTaskLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
	    $user = \Auth::user();
	    $user = \Auth::user();
	    $validator = Validator::make($request->all(), [
		    'library' => [
			    'required',
			    'numeric'
		    ],
		    'title' => [
				'required',
			    'string',
			    'min:1',
			    'max:50'
		    ]
	    ]);
		$validator->validate();
	    $data = $validator->validated();
	    $library = ListingTaskLibrary::find($data['library']);
	    if (!$library) {
		    $error = \Illuminate\Validation\ValidationException::withMessages([
			    'library' => [__('Invalid library')],
		    ]);
		    throw $error;
	    }

		$data['listing_task_library_id'] = $library->getKey();
		unset($data['library']);

		$entity = $user->listingTasks()->create($data);
		if ($request->wantsJson()) {
			return response()->json($entity);
		}
		return redirect()->route('settings.templates.index');
    }
}
