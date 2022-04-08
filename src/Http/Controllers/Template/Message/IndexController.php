<?php

namespace Egent\Setting\Http\Controllers\Template\Message;

use Egent\Setting\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
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
			'library' => ['sometimes','numeric']
		]);
		$input = $validator->validated();

		$data = [];

		if (array_key_exists('library', $input)) {
			$data['entities'] = $user->settingMessages()->where('library_id', $input['library'])->get();
		} else {
			$data['entities'] = $user->settingMessages;
		}

		return response()->json($data);
    }
}
