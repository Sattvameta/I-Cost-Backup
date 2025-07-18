<?php

namespace Modules\ProjectManager\Entities;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

    protected $fillable = [
        'company_id',
        'unique_reference_no',
        'client',
        'project_title',
        'location',
        'sector',
        'region',
        'project_address',
        'type_of_contract',
        'shifts',
        'project_manager',
        'site_supervisor',
        'client_contacts',
        'current_start_date',
        'current_completion_date',
        'current_value_of_project',
        'base_margin',
        'change_management',
        'adjusted_contract_value',
        'labour_value',
        'project_copy_id',
        'version',
        'project_total',
        'created',
        'hr_rate',
        'mhr_rate',
        'tender_status',
        'status',
    ];
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
                $query->where('project_title', 'LIKE', '%' . $keyword . '%');
            });
        }
        return $query;
    }

    /**
     * Get the staff timesheets of the project.
     */
    public function staffTimesheets()
    {
        return $this->hasMany('Modules\TimesheetManager\Entities\StaffTimesheet');
    }

    /**
     * Get the labour timesheets of the project.
     */
    public function labourTimesheets()
    {
        return $this->hasMany('Modules\TimesheetManager\Entities\LabourTimesheet');
    }

    /**
     * Get the estimates of the project.
     */
    public function mainActivities()
    {
        return $this->hasMany('Modules\EstimateManager\Entities\MainActivity');
    }
    
    /**
     * Get the library of the project.
     */
    public function librarymainActivities()
    {
        return $this->hasMany('Modules\EstimateManager\Entities\LibraryMainActivity');
    }

    /**
     * Get the quotations of the project.
     */
    public function quotations()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\Quotation', 'project_id');
    }

    /**
     * Get the purchases of the project.
     */
    public function purchases()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\Purchase', 'project_id');
    }

    /**
     * Get the formulas of the project.
     */
    public function formulas()
    {
        return $this->hasMany('Modules\FormulaManager\Entities\Formula', 'project_id');
    }

    /**
     * Get the company for the project.
     */
    public function company()
    {
        return $this->belongsTo('App\User', 'company_id');
    }

    public function getDisplayProjectTitleAttribute(){
        return $this->project_title."(v-".$this->version.")";
    }

}
