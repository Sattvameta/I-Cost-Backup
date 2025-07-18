<?php

namespace Modules\CompanyManager\Http\Controllers;

use App\User;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\CompanyManager\Http\Requests\CompanyRequest;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Modules\CompanyManager\Http\Requests\CompanyProfileRequest;
use Illuminate\Support\Facades\DB;

class CompanyManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (!auth()->user()->can('access', 'admins visible') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        // Categories
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('All', '');

        return view('companymanager::index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!auth()->user()->can('access', 'admins add') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        // Categories
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('Select Category', '');

        return view('companymanager::create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        if (!auth()->user()->can('access', 'admins add') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        
        try{
            \DB::beginTransaction();
            //$request->merge(['creater_id' => auth()->id()]);
            if($request->hasFile('file')){
                // Store user avatar
                $avatar = $request->file('file')->store('avatars', 'public');
                // Attach avatar in request
                $request->request->add(['avatar'=> $avatar]);
            }
            if($request->hasFile('logo')){
                // Store logo
                $logo = $request->file('logo')->store('logos', 'public');
                // Attach logo in request
                $request->request->add(['company_logo'=> $logo]);
            }
            
            $request->offsetSet('password', bcrypt($request->password));

            $company = new User($request->all());

            if($company->save()){
                // Attach role with company
                $company->roles()->attach(2);

                $token = app('auth.password.broker')->createToken($company);

                // Db commit
                \DB::commit();

                return redirect()->route('companies.index')
                                ->withSuccess('Company has been created successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
          
            
        }
        return redirect()->route('companies.index')
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
        if (!auth()->user()->can('access', 'admins visible') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $company = User::findOrFail($id);
        return view('companymanager::show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Illuminate\Http\Request $request
     * @param (int) $id
     * @return \Illuminate\Http\Response
     */
	  public function userlist(Request $request, $id)
    {
        if (!auth()->user()->can('access', 'admins visible') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
		$company =DB::table('role_user')
            ->join('users', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id','users.full_name','users.company_id','users.company_name','users.email','roles.name','users.supplier_name')
			->where([
    ['users.company_id','=', $id],
    ['users.supplier_name','=', NULL]
   ])
            ->get();
		//$company = DB::table('users')->select('id','full_name','company_id','company_name','email')->where('company_id',$id)->get();
        $user = User::findOrFail($id);
       
        return view('companymanager::userlist', compact('company','user'));
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
        if (!auth()->user()->can('access', 'admins add') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $company = User::findOrFail($id);
        // Categories
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('Select Category', '');
        
        return view('companymanager::edit', compact('categories', 'company'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  CompanyRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, $id)
    {
        if (!auth()->user()->can('access', 'admins add') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $company = User::findOrFail($id);
        try{
            \DB::beginTransaction();
            // Update company avatar if changed
            if($request->hasFile('file')){
                // Remove old avatar
                if(\Storage::disk('public')->delete($company->avatar)){
                    $company->avatar = "";
                    $company->save();
                }
                // Upload avatar
                $avatar = $request->file('file')->store('avatars', 'public');
                // Attach avatar in request
                $request->request->add(['avatar'=> $avatar]);
            }
            if($request->hasFile('logo')){
                // Remove old logo
                if(\Storage::disk('public')->delete($company->company_logo)){
                    $company->company_logo = "";
                    $company->save();
                }
                // Upload logo
                $logo = $request->file('logo')->store('logos', 'public');
                // Attach avatar in request
                $request->request->add(['company_logo'=> $logo]);
            }
           
            if($company->update($request->all())){
                \DB::commit();
                return redirect()->route('companies.index')
                                ->withSuccess('Company has been updated successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
        }
        return redirect()->route('companies.index')
                ->withError('Something went wrong. Please try again later.');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile() {
        $company = auth()->user();
        // Categories
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('Select Category', '');
        return view('companymanager::profile', compact('company', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CompanyProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(CompanyProfileRequest $request)
    {
        $company = auth()->user();
        try{
            \DB::beginTransaction();
            // Update company avatar if changed
            if($request->hasFile('file')){
                // Remove old avatar
                if(\Storage::disk('public')->delete($company->avatar)){
                    $company->avatar = "";
                    $company->save();
                }
                // Upload avatar
                $avatar = $request->file('file')->store('avatars', 'public');
                // Attach avatar in request
                $request->request->add(['avatar'=> $avatar]);
            }
            if($request->hasFile('logo')){
                // Remove old logo
                if(\Storage::disk('public')->delete($company->company_logo)){
                    $company->company_logo = "";
                    $company->save();
                }
                // Upload logo
                $logo = $request->file('logo')->store('logos', 'public');
                // Attach avatar in request
                $request->request->add(['company_logo'=> $logo]);
            }
            if($company->update($request->all())){
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
     * Remove the specified resource from storage.
     *
     * @param Illuminate\Http\Request $request
     * @param (int) $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!auth()->user()->can('access', 'admins add') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $company = User::find($id);
        try{
            \DB::beginTransaction();

            if($company->delete()){
                \DB::commit();
                return redirect()->route('companies.index')
                        ->withSuccess('Company has been deleted successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
        }
        return redirect()->route('company.index')
                ->withError('Something went wrong. Please try again later.');
    }

    /**
     * Get the specified resource from storage.
     * @param DatatableRequest $request
     * @return Response
     */
    public function ajaxListAllCompanies(DatatableRequest $request) {

        $query = User::select(['id', 'category_id', 'company_name', 'company_contact', 'company_logo', 'full_name', 'status']);
        $query->whereHas('roles', function($q){
            $q->where('name', 'Admin');
        });
        $query->when(($request->has('status_filter') && ($request->status_filter != null)), function($q) use($request){
            $q->where('status', $request->query('status_filter'));
        });

        $query->when(($request->has('category_filter') && ($request->category_filter != null)), function($q) use($request){
            $q->where('category_id', $request->query('category_filter'));
        });

        return datatables()->of($query)
                ->editColumn('logo', function ($company) {
                    if(\Storage::disk('public')->has($company->company_logo)){
                         return '<img src="'.'../storage/app/public/'.$company->company_logo.'" alt="" height="50px" width="50px">';
                    }else{
                        return '<img src="'.asset('images/no-img-100x92.jpg').'" alt="" height="50px" width="50px">';
                    }
                })
                ->addColumn('category', function ($company) {
                    return @$company->category->name;
                })
                ->addColumn('status', function ($company) {
                    return ($company->status == 1) ? 'Active' : 'In-active';
                })
                ->addColumn('action', function ($company) {
                    $actions = "";
                    $actions .= "<a title=\"View company\" href=\"" . route('companies.view', $company->id) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-folder\"></i></a>";
                    if (auth()->user()->can('access', 'admins add')) {
                        $actions .= "&nbsp;<a title=\"Edit company\" href=\"" . route('companies.edit', $company->id) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-pencil-alt\"></i></a>";
                        $actions .= "&nbsp;<a title=\"Company features\" href=\"" . route('companies.features', $company->id) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-bars\"></i></a>";
                        $actions .= "&nbsp;<a title=\"Company permissions\" href=\"" . route('companies.permissions', $company->id) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-lock\"></i></a>";
						$actions .= "&nbsp;<a title=\"User List\" href=\"" . route('companies.userlist', $company->id) . "\" class=\"btn btn-success btn-sm\"><i class=\"fas fa-eye\"></i></a>";
                        $actions .= "&nbsp;<a title=\"Delete company\" onclick=\"return confirm('Are you sure want to remove the company?')\" href=\"" . route('companies.delete', $company->id) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"></i></a>";
                    }
                    return $actions;
                })
                ->rawColumns(['logo', 'action'])
                ->make(true);
    }
    
    public function apiaddcompany(Request $request){
       try{
           \DB::beginTransaction();

            $data = $request->json()->all();
           
            $comapany = $data['data'];
            foreach($comapany as $key=>$value){
                if($key == 'full_name'){
                    $company_name = $value;
                    $full_name = $value;
                }
                if($key == 'phone'){
                    
                    $phone = $value;
                }
                if($key == 'email'){
                    
                    $email = $value;
                }
                if($key == 'password'){
                   
                    $password = $value;
                }
                if($key == 'period'){
                   
                    $period = $value;
                }
                if($key == 'start_date'){
                   
                    $start_date = $value;
                }
                if($key == 'end_date'){
                   
                    $end_date = $value;
                }
            }
            $company_id='';
            $category_id='';
            $status=1;
            $creater_id=1;
         
            if (User::where('email', '=', $email)->exists()) {
              return response('User Already Exists!', 200)->header('Content-Type', 'text/plain');
              //return response()->json(["msg" => "User Already Exists!"]);
            }
            
             $inserted_id = \DB::table('users')->insertGetId(["full_name"=>$full_name,"email"=>$email,"phone"=>$phone,"company_name"=>$company_name,"company_id"=>NULL,"category_id"=>NULL,"status"=>1,"month"=>$period,"start_date"=>$start_date,"end_date"=>$end_date,"creater_id"=>1,"password" => bcrypt($password),"dummy" => $password]);
            \DB::table('role_user')->insertGetId(["role_id"=>2,"user_id"=> $inserted_id]);
            
            if($period == "14 DAYS FREE TRAIL"){
                \DB::table('company_features')->insertGetId(["feature_id"=>2,"company_id"=> $inserted_id]);
                \DB::table('company_features')->insertGetId(["feature_id"=>4,"company_id"=> $inserted_id]);
                \DB::table('company_features')->insertGetId(["feature_id"=>5,"company_id"=> $inserted_id]);
                $role_ids=array(7,8,9,10,11,12);
                foreach($role_ids as $perm_id){
                \DB::table('permission_role')->insert(["permission_id"=>$perm_id,"role_id"=> 2,"company_id"=> $inserted_id]);
                }
                
            }
            \DB::commit();
            return response('success', 200)->header('Content-Type', 'text/plain');
            //return response()->json(['status' => 'true']);
       }catch(\Exception $e){
           \DB::rollBack();
           return response('Unable to add User', 200)->header('Content-Type', 'text/plain');
           //return response()->json(['status' => 'false']);
       }
    }
    
    
}
