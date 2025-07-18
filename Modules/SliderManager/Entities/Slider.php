<?php

namespace Modules\SliderManager\Entities;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model {

    protected $fillable = ['title', 'image', 'link','description','status'];
  /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status = null)
    {
        if ($status === '0' || $status == 1) {
            $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope a query to only include filtered users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', '%' . $keyword . '%');
            });
        }
        return $query;
    }

}
