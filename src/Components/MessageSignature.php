<?php
namespace Egent\Setting\Components;


use App\Models\User;
use Egent\Egent\Core\Models\Task;
use Egent\Notification\Models\Thread;
use Spatie\Tags\Tag;

use Illuminate\View\Component;

class MessageSignature extends Component
{

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private User $user)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
		if (!$this->user->exists) {
			return;
		}

        $user = $this->user;

        return view('setting::component.message.signature', [
            'user' => $user
        ]);
    }
}
