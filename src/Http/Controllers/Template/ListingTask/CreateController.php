<?php
namespace Egent\Setting\Http\Controllers\Template\ListingTask;


use Egent\Setting\Http\Controllers\Controller;
use Egent\Setting\Models\ListingTaskLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Getting rid of this for a strictly JS option soon.
 * @deprecated
 */
class CreateController extends Controller
{

    /**
     * Create resource form.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
	    $user = \Auth::user();
		$validator = Validator::make($request->all(), [
			'library' => [
				'required',
				'numeric'
			]
		]);
		$data = $validator->validated();
		$library = ListingTaskLibrary::find($data['library']);
		if (!$library) {
			$error = \Illuminate\Validation\ValidationException::withMessages([
				'library' => [__('Invalid library')],
			]);
			throw $error;
		}

	    return view('setting::template.listing-task.create', ['user' => $user, 'library' => $library]);
    }
}
