<?php
namespace Egent\Setting\Http\Controllers\Template\Message;

use Egent\Setting\Http\Controllers\Controller;

use Egent\Setting\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UpdateController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Message $message)
    {
		abort_if(!$message->exists, 404);
	    $user = \Auth::user();
	    abort_if(!$user, 403);
		abort_if($message->user_id != $user->getKey(), 403);

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
		foreach($data as $k => $v) {
			$message->$k = $v;
		}
	    $message->save();

	    return response()->json($message);
    }

}
