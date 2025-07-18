<?php

namespace App;

use Auth;
use App\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 
        'category_id', 
        'creater_id',
        'full_name', 
        'rate', 
        'email', 
        'password', 
        'phone',
        'fax',
        'suburb',
        'postcode',
        'address_line1', 
        'address_line2', 
        'company_name',  
        'company_contact', 
        'company_logo',
        'supplier_name',
        'supplier_contact_name',
        'avatar', 
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 1,
    ];

    /**
     * Get the roles that associated with the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    /**
     * Get the features that owns the user.
     */
    public function features()
    {
        return $this->belongsToMany('App\Feature');
    }
    /**
     * Get the category that owns the user.
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get the company that owns the user.
     */
    public function company()
    {
        return $this->belongsTo('App\User', 'company_id');
    }

    /**
     * Get the roles that associated with the user.
     */
    public function projects()
    {
        return $this->hasMany('Modules\ProjectManager\Entities\Project');
    }

    /**
     * The quotations that belong to the user.
     */
    public function quotations()
    {
        return $this->belongsToMany('Modules\PurchaseManager\Entities\Quotation', 'quotation_supplier', 'quotation_id', 'supplier_id');
    }

    public function isRole($roleName)
    {
        if($this->roles->contains('name', $roleName)){
            return true;
        }
        return false;
    }

    /**
     * Set the reset password notification for user.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    
}
