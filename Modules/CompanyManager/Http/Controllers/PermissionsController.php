<?php

namespace Modules\CompanyManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Module;
use App\SubModule;
use App\Role;
use App\Permission;
use App\PermissionRole;
use App\Feature;
use App\CompanyFeature;
use App\User;

class PermissionsController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try {
            $user_role = auth()->user()->roles()->first()->slug;
            if ($user_role != 'super_admin' && $user_role != 'admin') {
                return redirect('dashboard')->withError('Not authroized to access!');
            }
            $title = "Manage Permissions";
            $permissions = Permission::orderBy('module', 'ASC')->orderBy('id', 'ASC')->where('status', 1)->get();

           $is_default = 0;
            if ($user_role == 'super_admin') {
                //super admin can assign to admin / supplier and other users
                $role = Role::where('status', 1)->whereNotIn('slug', ['super_admin'])->get();
                $permissionsIds = PermissionRole::where('role_id', 2)->where('company_id', 1)->pluck('permission_id')->toArray();
            } elseif ($user_role == 'admin') {
                //admin can assign to its company users only
                $role = Role::where('status', 1)->whereNotIn('slug', ['super_admin', 'admin', 'supplier'])->get();
                $permissionsIds = PermissionRole::where('role_id', 4)->where('company_id', auth()->user()->id)->pluck('permission_id')->toArray();
                if (empty($permissionsIds)) {
                    $permissionsIds = PermissionRole::where('role_id', 4)->where('company_id', 1)->pluck('permission_id')->toArray();
                    $is_default = 1;
                }
            }
            return view('companymanager::permissions.index', compact('permissions', 'role', 'permissionsIds', 'title', 'is_default'));
        } catch (Exception $ex) {
        }
    }

    public function companyPermissions($company_id)
    {
        try {
            $user_role = auth()->user()->roles()->first()->slug;
            if ($user_role != 'super_admin' && $user_role != 'admin') {
                return redirect('dashboard')->withError('Not authroized to access!');
            }
            $is_default = 0;
            $permissionsIds = $permissions = [];
            $company = User::with(['roles'])->where('users.id', $company_id)->whereHas('roles', function ($query) {
                $query->where('slug', '=', 'admin');
            })->get()->first();
            $company_extra_features_id = CompanyFeature::where('company_id', $company_id)->pluck('feature_id', 'feature_id')->toArray();

            $query = Feature::where('status', 1);
            $query->where(function ($query) use ($company_extra_features_id) {
                $query->where('is_default', 1);
                if (!empty($company_extra_features_id))
                    $query->orWhereIn('id', $company_extra_features_id);
            });
            $company_features = $query->pluck('module', 'id')->toArray();

            $permissions = Permission::orderBy('module', 'ASC')->where('status', 1)->whereIn('module', $company_features)->get();

            if (!empty($company)) {

                $role = Role::where('status', 1)->whereNotIn('slug', ['super_admin', 'supplier'])->get();
                $permissionsIds = PermissionRole::where('role_id', 2)->where('company_id', $company_id)->pluck('permission_id')->toArray();
                if (empty($permissionsIds)) {
                    $permissionsIds = PermissionRole::where('role_id', 2)->where('company_id', 1)->pluck('permission_id')->toArray();
                    $is_default = 1;
                }
                return view('companymanager::permissions.company_permission', compact('role', 'permissions', 'permissionsIds', 'company', 'is_default'));
            } else {
                return redirect()->back()->with('message', 'Company permission can be set for the company admin only.');
            }
        } catch (Exception $ex) {
        }
    }

    public function company($company_id)
    {
        try {
            $user_role = auth()->user()->roles()->first()->slug;
            if ($user_role != 'super_admin') {
                return redirect('dashboard')->withError('Not authroized to access!');
            }
            $role = Role::where('status', 1)->whereNotIn('slug', ['super_admin', 'admin', 'supplier'])->get();
            $permissionsIds = PermissionRole::where('role_id', 4)->where('company_id', $company_id)->pluck('permission_id')->toArray();


            return view('companymanager::permissions.company', compact('permissions', 'role', 'permissionsIds', 'title', 'is_default'));
        } catch (Exception $ex) {
        }
    }

    /**
     *
     * @param type $id
     */
    public function edit($id)
    {
        try {
            $user_role = auth()->user()->roles()->first()->slug;
            if ($user_role != 'super_admin' && $user_role != 'admin') {
                return redirect('dashboard')->withError('Not authroized to access!');
            }
            $role = Role::where('status', 1)->whereNotIn('slug', ['super_admin'])->get();
            $modules = Module::where('id', $id)->get();
            $subModules = SubModule::where('module_id', $id)->get();
            return view('companymanager::permissions.edit', compact('subModules', 'modules', 'role'));
        } catch (Exception $ex) {
        }
    }

    /**
     *
     * @param type $roleId
     * @return type
     */
    public function getPermissions($roleId)
    {
        $user_role = auth()->user()->roles()->first()->slug;
        $permissions = Permission::orderBy('module', 'ASC')->where('status', 1)->get();
        $permissionsIds = [];
        $is_default = 0;
        if ($user_role == 'super_admin') {
            //super admin can assign to admin / supplier and other users

            $permissionsIds = PermissionRole::where('role_id', $roleId)->where('company_id', 1)->pluck('permission_id')->toArray();
        } elseif ($user_role == 'admin') {
            //admin can assign to its company users only

            $permissionsIds = PermissionRole::where('role_id', $roleId)->where('company_id', auth()->user()->id)->pluck('permission_id')->toArray();
            if (empty($permissionsIds)) {
                $permissionsIds = PermissionRole::where('role_id', $roleId)->where('company_id', 1)->pluck('permission_id')->toArray();
                $is_default = 1;
            }
        }

        return view('companymanager::permissions.data', compact('permissions', 'permissionsIds', 'is_default'));
    }
    public function getCompanyPermissions($roleIdcompany_id)
    {
        $info = explode('_', $roleIdcompany_id);
        $roleId = $info[0];
        $company_id = $info[1];
        $user_role = auth()->user()->roles()->first()->slug;
        $company_extra_features_id = CompanyFeature::where('company_id', $company_id)->pluck('feature_id', 'feature_id')->toArray();

        $query = Feature::where('status', 1);
        $query->where(function ($query) use ($company_extra_features_id) {
            $query->where('is_default', 1);
            if (!empty($company_extra_features_id))
                $query->orWhereIn('id', $company_extra_features_id);
        });
        $company_features = $query->pluck('module', 'id')->toArray();

        $permissions = Permission::orderBy('module', 'ASC')->where('status', 1)->whereIn('module', $company_features)->get();

        $permissionsIds = [];
        $is_default = 0;

        $permissionsIds = PermissionRole::where('role_id', $roleId)->where('company_id', $company_id)->pluck('permission_id')->toArray();
        if (empty($permissionsIds)) {
            $permissionsIds = PermissionRole::where('role_id', $roleId)->where('company_id', 1)->pluck('permission_id')->toArray();
            $is_default = 1;
        }


        return view('companymanager::permissions.data', compact('permissions', 'permissionsIds', 'is_default'));
    }

    /**
/**
     *
     * @param Request $request
     */
    public function updatePermissions(Request $request)
    { 
        if($request->permission_id){
            $request->permission_id += ['one' => '1'];
            $request->permission_id += ['two' => '2'];
            $request->permission_id += ['three' => '7'];
            $request->permission_id += ['four' => '8'];
            $request->permission_id += ['five' => '15'];
            $request->permission_id += ['six' => '16'];
            if(in_array('3',$request->permission_id)){
                
                $request->permission_id += ['seven' => '25'];
                $request->permission_id += ['eight' => '26'];
            
            }
        }
        
        

   
        $user_role = auth()->user()->roles()->first()->slug;
        if ($user_role != 'super_admin' && $user_role != 'admin') {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        //  $roles = Role::find($request->role_id);
        $company_id =  (isset($request->company_id) && !empty($request->company_id)) ? $request->company_id : auth()->user()->id;
        PermissionRole::where('role_id', '=', $request->role_id)->where('company_id', '=', $company_id)->delete();
        $i = 0;
        if (isset($request->default_permission) && $request->default_permission == 1) {
            return redirect()->back()->with('message', 'Permission settings set default successfully.');
        } else {
            if (empty($request->permission_id)) {
                $data[$i]['permission_id'] = 0;
                $data[$i]['role_id'] = $request->role_id;
                $data[$i]['company_id'] = $company_id;
            } else {
                
                foreach ($request->permission_id as $permission_id) {
                    $data[$i]['permission_id'] = $permission_id;
                    $data[$i]['role_id'] = $request->role_id;

                    $data[$i]['company_id'] = $company_id;
                    $i++;
                }
            }

            PermissionRole::insert($data);
            return redirect()->back()->with('message', 'Permission settings updated successfully.');
        }
        //$company->permissionroles()->where('role_id', $request->role_id)->sync($data);
    }
}
