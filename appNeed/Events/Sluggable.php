<?php
namespace App\Events;
use Illuminate\Support\Facades\DB;

trait Sluggable
{
	public static function bootSluggable()
	{
		static::creating(function ($model) {
            $settings = $model->sluggable();
            $dbcolumn = isset($settings['dbfield']) ? $settings['dbfield'] : 'slug';
            $string = !empty($model[$dbcolumn]) ? $model[$dbcolumn] : $model[$settings['source']];
            $model->$dbcolumn = $model->generateSlug($model, $dbcolumn, $string);
		});
	}

	abstract public function sluggable(): array;

	public function generateSlug($model, $dbcolumn,  $title)
    {
        // Normalize the title
        $slug = strtolower(str_slug($title));
        $i = 1;
        while ($this->isAliasExist($model, $dbcolumn, $slug)) {
            $slug = $slug . '-' . ++$i;
        }
        return $slug;
    }


    protected function isAliasExist($model, $dbcolumn, $slug)
    {
         $query = DB::table($model->table)->where($dbcolumn , '=', $slug);
         if($model->id){
            $query->where('id', '<>', $model->id);
         }
        $cnt =  $query->count();
        return $cnt > 0 ? true : false;
    }

}

