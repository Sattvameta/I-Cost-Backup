<?php

namespace Modules\SettingManager\Observers;

use Modules\SettingManager\Entities\Setting;
use Illuminate\Support\Facades\Storage;

class SettingObserver
{
    /**
     * Handle the setting "created" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function created(Setting $setting)
    {
        if($setting->getAttribute('manager') == "theme_images"){
            if($setting->getAttribute('config_value') != $setting->getOriginal('config_value')){
                Storage::move('public/temp/' . $setting->getAttribute('config_value'), 'public/settings/' . $setting->getAttribute('config_value'));
                $exists = Storage::disk('public')->exists('settings/'.  $setting->getOriginal('config_value'));
                if($exists){
                    Storage::disk('public')->delete('settings/'. $setting->getOriginal('config_value'));
                }
            }
        }
    }

    /**
     * Handle the setting "updated" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function updated(Setting $setting)
    {
        if($setting->getAttribute('manager') == "theme_images"){
            if($setting->getAttribute('config_value') != $setting->getOriginal('config_value')){
                Storage::move('public/temp/' . $setting->getAttribute('config_value'), 'public/settings/' . $setting->getAttribute('config_value'));
                $exists = Storage::disk('public')->exists('settings/'.  $setting->getOriginal('config_value'));
                if($exists){
                    Storage::disk('public')->delete('settings/'. $setting->getOriginal('config_value'));
                }
            }
        }

    }

    /**
     * Handle the setting "deleted" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function deleted(Setting $setting)
    {
    }

    /**
     * Handle the setting "restored" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function restored(Setting $setting)
    {
    }

    /**
     * Handle the setting "force deleted" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function forceDeleted(Setting $setting)
    {
    }
}
