<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user=Auth::user();
        if ($user) {
            $user->prev_login = $user->last_login;
            $user->last_login = \Carbon\Carbon::now();
            $user->save();

            if (Cache::has('all_page_data')) {
                Cache::forget('all_page_data');
            }
        }
    }
}
