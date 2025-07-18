<?php

namespace Modules\SettingManager\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Events\Sluggable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use Sluggable; // Attach the Sluggable trait to the model

    protected $fillable = ['title','slug','config_value','manager','field_type'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::updating(function($model)
        {
            $model->yamlParse();
        });
        static::saved(function($model)
        {
            $model->yamlParse();
        });
        static::updated(function($model)
        {
            $model->yamlParse();
        });
        static::deleted(function($model)
        {
            $model->yamlParse();
        });
    }

    public function sluggable() {
		return [
            'dbfield' => 'slug',
            'source' => 'title',
		];
    }

    protected function yamlParse()
    {
        $settings = DB::table('settings')->whereNull('manager')->orWhere('manager','general')->orWhere('manager','social')->orWhere('manager','options')->orWhere('manager','smtp')->orWhere('manager','theme_images')->pluck('config_value','slug')->toArray();

        //dd($settings);
        // $query = DB::getQueryLog();
        // $query = end($query);
        // var_dump($query);
        $listYaml = Yaml::dump($settings, 4, 60);
        Storage::disk('configuration')->put('settings.yml', $listYaml);
    }


}
