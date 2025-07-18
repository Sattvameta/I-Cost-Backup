<?php

namespace Modules\FormulaManager\Entities;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model {

    protected $fillable = [
        'project_id',
        'keyword',
        'description',
        'formula',
        'value',
        'status'
    ];
   /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the project for the formula.
     */
    public function project()
    {
        return $this->belongsTo('Modules\ProjectManager\Entities\Project');
    }

}
