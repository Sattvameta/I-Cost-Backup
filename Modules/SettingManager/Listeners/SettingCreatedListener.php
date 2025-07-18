<?php

namespace Modules\SettingManager\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\SettingManager\Events\SettingCreated;

class SettingCreatedListener
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
     * @param  SettingCreated  $event
     * @return void
     */
    public function handle(SettingCreated $event)
    {
        //
    }
}
