<?php
namespace Egent\Setting\Http\Controllers\Template\MessageLibrary;


use Egent\Setting\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
	    abort_if(!$user, 403);

		$validator = Validator::make($request->all(), [
			'name' => [
				'required',
				'string',
				'min:1',
				'max:50'
			]
		]);
		$validator->validate();
		$data = $validator->validated();
	    if ($user->settingMessageLibraries()->where('name', $data['name'])->count()) {
		    throw ValidationException::withMessages(['name' => 'This name must be unique.']);
	    }

	    /**
	     * Just putting a stop in here.  Eventually we need to open this up, but I want to stop it for now.
	     */
		if ($user->settingMessageLibraries->count() > 10) {
			throw ValidationException::withMessages(['name' => 'You have reached the maximum number of message libraries.']);
		}

	    $entity = $user->settingMessageLibraries()->create($data);

		if ($request->wantsJson()) {
			return response()->json($entity);
		}
			$next = null;
			try {
				$next = $request->session()->get('_previous')['url'];
				if ($next == route('settings.templates.message-libraries.create')) {
					$next = null;
					throw new \Exception('Bypass to index.');
				}
			} catch (\Throwable $e) {

			}
			if (!$next) {
				$next = route('settings.templates.message-libraries.index');
			}

	    flash('Library created!', 'success');

		return redirect($next);
    }
}
