<?php

namespace Modules\SupplierManager\Http\Controllers;

use App\User;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Imports\UsersImport;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\SupplierManager\Http\Requests\SupplierRequest;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Modules\SupplierManager\Http\Requests\SupplierImportRequest;
use Modules\SupplierManager\Http\Requests\SupplierProfileRequest;

class SupplierManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (!auth()->user()->can('access', 'suppliers visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }

        // Categories
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('All', '');

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

        return view('suppliermanager::index', compact('categories', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!auth()->user()->can('access', 'suppliers add')  && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        // Categories
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('Select category', '');
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

        return view('suppliermanager::create', compact('categories', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SupplierRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        if (!auth()->user()->can('access', 'suppliers add') && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try{
            \DB::beginTransaction();
            $request->merge(['creater_id' => auth()->id()]);
            if($request->hasFile('file')){
                // Store supplier avatar
                $avatar = $request->file('file')->store('avatars', 'public');
                // Attach avatar in request
                $request->request->add(['avatar'=> $avatar]);
            }
            
            $request->offsetSet('password', bcrypt($request->password));

            $supplier = new User($request->all());
            if($supplier->save()){
                // Attach role with supplier
                $supplier->roles()->attach(3);

                $token = app('auth.password.broker')->createToken($supplier);

                // Db commit
                \DB::commit();

                return redirect()->route('suppliers.index')
                                ->withSuccess('Supplier has been created successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
        }
        return redirect()->route('suppliers.index')
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
        if (!auth()->user()->can('access', 'suppliers visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $supplier = User::findOrFail($id);
        //print_r($supplier);exit;
        // Categories
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('Select category', '');

        return view('suppliermanager::show', compact('categories', 'supplier'));
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
        if (!auth()->user()->can('access', 'suppliers add') && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $supplier = User::findOrFail($id);
        // Categories
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('Select category', '');
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

        return view('suppliermanager::edit', compact('categories', 'companies', 'supplier'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  SupplierRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id)
    {
        if (!auth()->user()->can('access', 'suppliers add') && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $supplier = User::findOrFail($id);
        try{
            \DB::beginTransaction();
            // Update supplier avatar if changed
            if($request->hasFile('file')){
                // Remove old avatar
                if(\Storage::disk('public')->delete($supplier->avatar)){
                    $supplier->avatar = "";
                    $supplier->save();
                }
                // Upload avatar
                $avatar = $request->file('file')->store('avatars', 'public');
                // Attach avatar in request
                $request->request->add(['avatar'=> $avatar]);
            }
            if($supplier->update($request->all())){
                \DB::commit();
                return redirect()->route('suppliers.index')
                                ->withSuccess('Supplier has been updated successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
        }
        return redirect()->route('suppliers.index')
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
        if (
            !auth()->user()->can('access', 'suppliers add') && 
            (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))
        ) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $supplier = User::find($id);
        try{
            \DB::beginTransaction();

            if($supplier->delete()){
                \DB::commit();
                return redirect()->route('suppliers.index')
                        ->withSuccess('Supplier has been deleted successfully!');
            }
        }catch(\Exception $e){
            \DB::rollBack();
        }
        return redirect()->route('suppliers.index')
                ->withError('Something went wrong. Please try again later.');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile() {
        $supplier = auth()->user();
        return view('suppliermanager::profile', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SupplierProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(SupplierProfileRequest $request)
    {
        $supplier = auth()->user();
        try{
            \DB::beginTransaction();
            // Update supplier avatar if changed
            if($request->hasFile('file')){
                // Remove old avatar
                if(\Storage::disk('public')->delete($supplier->avatar)){
                    $supplier->avatar = "";
                    $supplier->save();
                }
                // Upload avatar
                $avatar = $request->file('file')->store('avatars', 'public');
                // Attach avatar in request
                $request->request->add(['avatar'=> $avatar]);
            }
            if($supplier->update($request->all())){
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
     * Show the form for creating a new resource.
     * @return Response
     */
    public function import()
    {
        if (
            !auth()->user()->can('access', 'suppliers add') && 
            (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))
        ) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        // Categories
        $categories = Category::where('status', 1)
                ->pluck('name', 'id');
        $categories->prepend('Select category', '');
        // Companies
        $companies = User::select(['id', 'company_name'])
                    ->whereHas('roles', function ($query) {
                        $query->where('slug', '=', 'admin');
                    })->pluck('company_name', 'id');
        $companies->prepend('Select Company', '');

        return view('suppliermanager::import', compact('categories', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SupplierImportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function doImport(SupplierImportRequest $request)
    {
        if (!auth()->user()->can('access', 'suppliers add') && (!auth()->user()->isRole('Super Admin') || !auth()->user()->isRole('Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            $path = $request->file('file')->store('temp'); 
            //$path = storage_path('app').'/'.$path;  
            $import = new UsersImport($request->company_id, $request->category_id);
            $import->import($path);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        return redirect()->route('suppliers.index')->with("success", "Suppliers has been imported successfully!");
    }

    

    /**
     * Get the specified resource from storage.
     * @param DatatableRequest $request
     * @return Response
     */
    public function ajaxListAllSuppliers(DatatableRequest $request) {

        $query = User::select(['id', 'company_id', 'supplier_name', 'email', 'phone', 'avatar', 'status']);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->whereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                });
            }
        }
        $query->whereHas('roles', function($q){
            $q->where('name', 'Supplier');
        });
        $query->when(($request->has('status_filter') && ($request->status_filter != null)), function($q) use($request){
            $q->where('status', $request->query('status_filter'));
        });

        $query->when(($request->has('category_filter') && ($request->category_filter != null)), function($q) use($request){
            $q->where('category_id', $request->query('category_filter'));
        });

        $query->when(($request->has('company_filter') && ($request->company_filter != null)), function($q) use($request){
            $q->where('company_id', $request->query('company_filter'));
        });

        return datatables()->of($query)
                ->editColumn('company_id', function ($supplier) {
                    $dat ="";
                    if(!empty($supplier->company)){
                        $dat = $supplier->company->company_name;
                    }
                    return $dat;
                })
                ->editColumn('avatar', function ($supplier) {
                    $avatar="";
                    if(\Storage::disk('public')->has($supplier->avatar)){
                         $avatar.='<a href= "../storage/app/public/'.$supplier->avatar.'" class=\"btn btn-success btn-sm\">View</a>'."\n\r"." &nbsp,&nbsp ";
                    }else{
                        return 'NO DOCUMENTS';
                    }
                    return $avatar;
                })
                ->addColumn('status', function ($company) {
                    return ($company->status == 1) ? 'Active' : 'In-active';
                })
                ->addColumn('action', function ($supplier) {
                    $actions = "";
                    $actions .= "<a title=\"View supplier\" href=\"" . route('suppliers.view', $supplier->id) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-folder\"></i></a>";
                    if (auth()->user()->can('access', 'users add') && (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))) {
                        $actions .= "&nbsp;<a title=\"Edit supplier\" href=\"" . route('suppliers.edit', $supplier->id) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-pencil-alt\"></i></a>";
                        $actions .= "&nbsp;<a title=\"Delete supplier\" onclick=\"return confirm('Are you sure want to remove the supplier?')\" href=\"" . route('suppliers.delete', $supplier->id) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"></i></a>";
                    }
                    return $actions;
                })
                ->rawColumns(['avatar', 'action'])
                ->make(true);
    }
}
