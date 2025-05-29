<?php

namespace App\Listeners;

use App\Events\UserActivityLogged;
use App\Models\UserActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaveUserActivity
{

    public function __construct()
    {
        // You can initialize any dependencies here if needed
    }

    /**
     * Handle the event.
     */
    public function handle(UserActivityLogged $event): void
    {
        $activity = new UserActivity();
        $activity->user_id = Auth::id();
        $activity->activity = $event->activity;
        $activity->save();
        // Log::info($event->activity);
    }
}
