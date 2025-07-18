<?php

namespace Modules\SettingManager\Events;

use Illuminate\Queue\SerializesModels;
use Modules\SettingManager\Entities\Setting;

class SettingCreated
{
    use SerializesModels;
    public $settings;

    /**
     * Create a new event instance.
     * @param  Setting $setting
     * @return void
     */
    public function __construct(Setting $setting)
    {
        $this->settings = $setting;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
