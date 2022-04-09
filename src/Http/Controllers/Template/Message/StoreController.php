<?php
namespace Egent\Setting\Http\Controllers\Template\Message;


use Egent\Setting\Http\Controllers\Controller;
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
	    abort_if(!$user, 403);

		$validator = Validator::make($request->all(), [
			'title' => [
				'required',
				'string',
				'min:1',
				'max:50'
			],
			'message' => [
				'required',
				'string',
				'min:1',
				'max:5024'
			],
			'library_id' => [
				'required',
				'numeric',
				'in:'.$user->settingMessageLibraries->pluck('id')->implode(',')
			]
		]);
		$validator->validate();
		$data = $validator->validated();
		$entity = $user->settingMessages()->create($data);

		return response()->json($entity);
    }
}
