<?php

namespace Modules\UserManager\Http\Controllers;

use App\Role;
use App\User;
use App\Projectuser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\UserManager\Http\Requests\UserRequest;
use Modules\UserManager\Http\Requests\StoreRequest;
use Modules\UserManager\Http\Requests\UserProfileRequest;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Modules\UserManager\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\DB;
class UserManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (!auth()->user()->can('access', 'users visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        // Roles
        $roles = Role::whereNotIn('slug', ['super_admin', 'admin', 'supplier'])
                ->pluck('name', 'id');
        $roles->prepend('All', '');
        // Companies
        $companies = User::select(['id', 'company_name'])
            ->whereHas('roles', function ($query) {
                $query->where('slug', '=', 'admin');
            });
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $companies->where('id', auth()->id());
            }else{
                $companies->whereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                });
            }
        }
        $companies = $companies->pluck('company_name', 'id');
        $companies->prepend('All', '');

        return view('usermanager::index', compact('roles', 'companies'));
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function admins()
    {
        if (!auth()->user()->can('access', 'users visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        // Roles
        $roles = Role::whereNotIn('slug', ['super_admin', 'admin', 'supplier'])
                ->pluck('name', 'id');
        $roles->prepend('All', '');
        // Companies
        $companies = User::select(['id', 'company_name'])
            ->whereHas('roles', function ($query) {
                $query->where('slug', '=', 'admin');
            });
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $companies->where('id', auth()->id());
            }else{
                $companies->whereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                });
            }
        }
        $companies = $companies->pluck('company_name', 'id');
        $companies->prepend('All', '');

        return view('usermanager::admins', compact('roles', 'companies'));
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!auth()->user()->can('access', 'users add') && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        // Roles
        $roles = Role::whereNotIn('slug', ['super_admin', 'admin', 'supplier'])
                ->pluck('name', 'id');
        $roles->prepend('Select Role', '');
        // Companies
        $companies = User::select(['id', 'company_name'])
            ->whereHas('roles', function ($query) {
                $query->where('slug', '=', 'admin');
            });
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $companies->where('id', auth()->id());
            }else{
                $companies->whereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                });
            }
        }
        $companies = $companies->pluck('company_name', 'id');
        $companies->prepend('Select company', '');

        return view('usermanager::create', compact('roles', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if (!auth()->user()->can('access', 'users add') && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try{
            \DB::beginTransaction();
            $request->merge(['creater_id' => auth()->id()]);
            if($request->hasFile('file')){
                // Store user avatar
                $avatar = $request->file('file')->store('avatars', 'public');
                // Attach avatar in request
                $request->request->add(['avatar'=> $avatar]);
            }
            
            $request->offsetSet('password', bcrypt($request->password));
//print_r($request->all());exit;
            $user = new User($request->all());
            if($user->save()){
                // Attach role with user
                $user->roles()->attach($request->role_id);

                $token = app('auth.password.broker')->createToken($user);

                // Db commit
                \DB::commit();

                return redirect()->route('users.index')
                                ->withSuccess('User has been created successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
        }
        return redirect()->route('users.index')
                ->withError('Something went wrong. Please try again later.');
    }

    /**
     * Display the specified resource.
     *
     * @param Illuminate\Http\Request $request
     * @param (int) $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!auth()->user()->can('access', 'users visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $user = User::findOrFail($id);
        $user->setAttribute('role', $user->roles->first()->name);
        return view('usermanager::show', compact('user'));
    }
    public function showadmin(Request $request, $id)
    {
        if (!auth()->user()->can('access', 'users visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $user = User::findOrFail($id);
        $user->setAttribute('role', $user->roles->first()->name);
        return view('usermanager::showadmin', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Illuminate\Http\Request $request
     * @param (int) $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!auth()->user()->can('access', 'users add') && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $user = User::findOrFail($id);
        $user->setAttribute('role_id', $user->roles->first()->id);
        // Roles
        $roles = Role::whereNotIn('slug', ['super_admin', 'admin', 'supplier'])
                ->pluck('name', 'id');
        $roles->prepend('Select Role', '');
        // Companies
        $companies = User::select(['id', 'company_name'])
            ->whereHas('roles', function ($query) {
                $query->where('slug', '=', 'admin');
            });
            
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $companies->where('id', auth()->id());
            }else{
                $companies->whereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                });
            }
        }
        $companies = $companies->pluck('company_name', 'id');
        $companies->prepend('Select Company', '');

        return view('usermanager::edit', compact('companies', 'roles', 'user'));
    }
    
    public function adminedit(Request $request, $id)
    {
        if (!auth()->user()->can('access', 'users add') && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $user = User::findOrFail($id);
        $user->setAttribute('role_id', $user->roles->first()->id);
        // Roles
        $roles = Role::whereNotIn('slug', ['super_admin', 'admin', 'supplier'])
                ->pluck('name', 'id');
        $roles->prepend('Select Role', '');
        // Companies
        $companies = User::select(['id', 'company_name'])
            ->whereHas('roles', function ($query) {
                $query->where('slug', '=', 'admin');
            });
            
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $companies->where('id', auth()->id());
            }else{
                $companies->whereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                });
            }
        }
        $companies = $companies->pluck('company_name', 'id');
        $companies->prepend('Select Company', '');

        return view('usermanager::adminedit', compact('companies', 'roles', 'user'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        if (!auth()->user()->can('access', 'users add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $user = User::findOrFail($id);
        
        
        try{
            \DB::beginTransaction();
            // Update user avatar if changed
            if($request->hasFile('file')){
                // Remove old avatar
                if(\Storage::disk('public')->delete($user->avatar)){
                    $user->avatar = "";
                    $user->save();
                }
                // Upload avatar
                $avatar = $request->file('file')->store('avatars', 'public');
                // Attach avatar in request
                $request->request->add(['avatar'=> $avatar]);
            }
           
            if($user->update($request->all())){
                \DB::table('role_user')->where('user_id',$id)->update(['role_id' =>  $request->role_id]);
                
       
              
              
                \DB::commit();
                return redirect()->route('users.index')
                                ->withSuccess('User has been updated successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
        }
        return redirect()->route('users.index')
                ->withError('Something went wrong. Please try again later.');
    }
    
   
    /**
     * Remove the specified resource from storage.
     *
     * @param Illuminate\Http\Request $request
     * @param (int) $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!auth()->user()->can('access', 'users add') && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $user = User::find($id);
        try{
            \DB::beginTransaction();

            if($user->delete()){
                \DB::commit();
                return redirect()->route('users.index')
                        ->withSuccess('User has been deleted successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
        }
        return redirect()->route('users.index')
                ->withError('Something went wrong. Please try again later.');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile() {
        $user = auth()->user();
        return view('usermanager::profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UserProfileRequest $request)
    {
        $user = auth()->user();
        try{
            \DB::beginTransaction();
            // Update user avatar if changed
            if($request->hasFile('file')){
                // Remove old avatar
                if(\Storage::disk('public')->delete($user->avatar)){
                    $user->avatar = "";
                    $user->save();
                }
                // Upload avatar
                $avatar = $request->file('file')->store('avatars', 'public');
                // Attach avatar in request
                $request->request->add(['avatar'=> $avatar]);
            }
            if($user->update($request->all())){
                \DB::commit();
                return redirect()->route('users.profile')
                                ->withSuccess('Profile has been updated successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
        }
        return redirect()->route('users.profile')
                ->withError('Something went wrong. Please try again later.');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function password() {
        return view('usermanager::password');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChangePasswordRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            \DB::beginTransaction();
            $user = auth()->user();
            $user->password = bcrypt($request->new_password);
            if($user->save()){
                \DB::commit();
                return redirect()->route('users.password')
                    ->withSuccess('Password has been changed successfully.');
            }
        } catch (\Exception $e) {
            \DB::rollBack();
        }
        return redirect()->route('users.password')
                ->withError('Something went wrong. Please try again later.');
    }
	
	 public function project(Request $request, $id)
    {
        
		$project =DB::table('users')
            ->join('projects','users.company_id', '=', 'projects.company_id')
            ->select('users.id','projects.project_title','projects.id','users.company_id','projects.version')
			->where('users.id',$id)
            ->get();
        $user = User::findOrFail($id);
        return view('usermanager::project', compact('project','user'));
    }
	public function storeproject(Request $request) {
		 $data = $request->all();
         $users = $data['users_id'];
         $projects = $data['projects_status'];
       
  //insert using foreach loop
  foreach($projects as $key => $input) {
    $scores = new Projectuser();
    $scores->users_id = isset($users[$key]) ? $users[$key] : ''; //add a default value here
    $scores->project_id = isset($projects[$key]) ? $projects[$key] : ''; //add a default value here
    //$scores->projects_status = isset($projects[$key]) ? 1 : ''; //add a default value here
    $scores->save();
  }
return redirect()->route('users.index')->with('success', 'Projects has been saved Successfully');
}

      /* try{
            \DB::beginTransaction();
            $user = new Projectuser($request->all());
            $user->save();	 

            \DB::commit();	
        }catch (\Exception $e) {
            \DB::rollBack();
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('users.index')->with('success', 'Projects has been saved Successfully');
    }*/
 
	 public function editproject(Request $request, $id)
    {
        
		$project =DB::table('users_project')
            ->join('projects','projects.id', '=', 'users_project.project_id')
            ->select('projects.project_title','users_project.id','users_project.project_id','users_project.id','projects.version')
			->where([
				['users_project.users_id',$id]
			   ])
            ->get();
		
        $users = User::findOrFail($id);
        return view('usermanager::editproject', compact('project','users'));
    } 
     public function updateproject(Request $request,$id) {
		  
		$blog = Projectuser::find($id);

    $blog->delete();

		
		
return redirect()->route('users.index')->with('success', 'Projects has been Deleted Successfully');
}

    /**
     * Get the specified resource from storage.
     * @param DatatableRequest $request
     * @return Response
     */
    public function ajaxListAllUsers(DatatableRequest $request) {

        $query = User::select(['id', 'company_id', 'full_name', 'email', 'phone', 'status','avatar']);
        
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->whereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                });
            }
        }
        $query->when(($request->has('status_filter') && ($request->status_filter != null)), function($q) use($request){
            $q->where('status', $request->query('status_filter'));
        });

        $query->when(($request->has('company_filter') && ($request->company_filter != null)), function($q) use($request){
            $q->where('company_id', $request->query('company_filter'));
        });

        $query->when(($request->has('role_filter') && ($request->role_filter != null)), function($q) use($request){
            $q->whereHas('roles', function($q) use($request){
                $q->where('role_id', $request->query('role_filter'));
            });
        }, function ($q) {
            $q->whereHas('roles', function($q){
                $q->whereNotIn('name', ['Super Admin', 'Admin', 'Supplier']);
            });
        });

        return datatables()->of($query)
                ->editColumn('avatar', function ($user) {
                    if(\Storage::disk('public')->has($user->avatar)){
                        return '<img src="'.asset('../storage/app/public/'.$user->avatar).'" alt="" height="50px" width="50px">';
                    }else{
                        return '<img src="'.asset('images/no-img-100x92.jpg').'" alt="" height="50px" width="50px">';
                    }
                })
                ->addColumn('role', function ($user) {
                    return $user->roles->first()->name;
                })
                ->addColumn('company', function ($user) {
                    return @$user->company->company_name;
                })
                ->addColumn('status', function ($user) {
                    return ($user->status == 1) ? 'Active' : 'In-active';
                })
                ->addColumn('action', function ($user) {
                    $actions = "";
                    $actions .= "<a title=\"View user\" href=\"" . route('users.view', $user->id) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-folder\"></i></a>";
                    if (auth()->user()->can('access', 'users add')  && (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))) {
                        $actions .= "&nbsp;<a title=\"Edit user\" href=\"" . route('users.edit', $user->id) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-pencil-alt\"></i></a>";
                        $actions .= "&nbsp;<a title=\"Delete user\" onclick=\"return confirm('Are you sure want to remove the user?')\" href=\"" . route('users.delete', $user->id) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"></i></a>";
						 if (auth()->user()->isRole('Admin')) {
						 $actions .= "&nbsp;<a title=\"Add Project\" href=\"" . route('users.project', $user->id) . "\"  class=\"btn btn-success btn-sm\"><i class=\"fas fa-plus-square\"></i></a>";
						  $actions .= "&nbsp;<a title=\"View Project\" href=\"" . route('users.editproject', $user->id) . "\"  class=\"btn btn-info btn-sm\"><i class=\"fas fa-eye\"></i></a>";
                    }
                    }
                    return $actions;
                })
                ->rawColumns(['avatar', 'action'])
                ->make(true);
    }
    
    /**
     * Get the specified resource from storage.
     * @param DatatableRequest $request
     * @return Response
     */
    public function ajaxListAllAdmins(DatatableRequest $request) {

        //$query = User::select(['id', 'company_id', 'full_name', 'email', 'phone', 'status']);
        $query = \DB::table('users')->select('id', 'company_id', 'full_name', 'email', 'phone', 'status','avatar','company_name','company_logo')->where('company_id', '!=', 0)->where('company_id', '=', '')->orWhereNull('company_id');
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->whereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                });
            }
        }
        $query->when(($request->has('status_filter') && ($request->status_filter != null)), function($q) use($request){
            $q->where('status', $request->query('status_filter'));
        });

        $query->when(($request->has('company_filter') && ($request->company_filter != null)), function($q) use($request){
            $q->where('id', $request->query('company_filter'));
        });

        

        return datatables()->of($query)
                ->editColumn('avatar', function ($user) {
                    
                    if(\Storage::disk('public')->has($user->company_logo)){
                         return '<img src="'.'../../storage/app/public/'.$user->company_logo.'" alt="" height="50px" width="50px">';
                    }else{
                        return '<img src="'.asset('images/no-img-100x92.jpg').'" alt="" height="50px" width="50px">';
                    }
                })
                
                ->addColumn('company', function ($user) {
                    return @$user->company_name;
                })
                ->addColumn('status', function ($user) {
                    return ($user->status == 1) ? 'Active' : 'In-active';
                })
                ->addColumn('action', function ($user) {
                    $actions = "";
                    $actions .= "<a title=\"View user\" href=\"" . route('users.adminview', $user->id) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-folder\"></i></a>";
                    if (auth()->user()->can('access', 'users add')  && (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))) {
                        /*$actions .= "&nbsp;<a title=\"Edit user\" href=\"" . route('users.adminedit', $user->id) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-pencil-alt\"></i></a>";*/
                       /* $actions .= "&nbsp;<a title=\"Delete user\" onclick=\"return confirm('Are you sure want to remove the user?')\" href=\"" . route('users.delete', $user->id) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"></i></a>";*/
                    }
                    return $actions;
                })
                ->rawColumns(['avatar', 'action'])
                ->make(true);
    }
}
