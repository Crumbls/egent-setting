<?php
namespace Egent\Setting\Http\Controllers\Template\Message;


use Egent\Setting\Http\Controllers\Controller;
use Egent\Setting\Models\Message;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Message $message)
    {
	    abort_if(!$message->exists, 404);
	    $user = \Auth::user();
	    abort_if(!$user, 403);
	    abort_if($message->user_id != $user->getKey(), 403);

	    $url = $request->get('redirectTo');
	    if ($url) {
		    if ($url == route('settings.templates.messages.delete', $message)) {
			    $url = false;
		    } else if ($url == route('settings.templates.messages.destroy', $message)) {
			    $url = false;
		    } else if (strpos($url, url('')) !== 0) {
				$url = false;
		    }
	    }
	    if (!$url) {
		    $url = route('settings.templates.index');
	    }

		$message->delete();
		return redirect($url);
    }
}
