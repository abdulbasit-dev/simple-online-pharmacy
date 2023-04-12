<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;

class ShowNotification extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $notifications = [];
    public $hasNotification = false;

    public function __construct()
    {
        $this->notifications = auth()->user()->unreadNotifications;
        $this->hasNotification = $this->notifications->count() > 0 ? true : false;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.show-notification');
    }
}
