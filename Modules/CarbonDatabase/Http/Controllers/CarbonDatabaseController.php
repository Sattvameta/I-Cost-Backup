<?php

namespace Modules\CarbonDatabase\Http\Controllers;
use App\Imports\ImportCarbon;
use App\CarbonDatabase;
use App\CarbonCalculatorDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Illuminate\Support\Facades\DB;
use Modules\ProjectManager\Entities\Project;
use Modules\EstimateManager\Http\Requests\ImportActivityRequest;
class CarbonDatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
		 $query = DB::table('carbon_database')->select('id','materials');
		 $allProjects = $query->get();
         $allProjects = $allProjects->pluck('materials', 'id');
         $allProjects->prepend('Select materials', '');
        return view('carbondatabase::index', compact('allProjects'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('carbondatabase::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
       
  
    $scores = new CarbonCalculatorDatabase();
	$scores->user_id = auth()->id(); 
    $scores->carbon_database_id = $request->ice; 
    $scores->carbon_a_one_a_five_id = $request->ecf; 
    $scores->user_database_id = $request->cck; 
    $scores->ghg_id	 = $request->ghg; 
    $scores->save();
  
return redirect()->route('carbondatabase')->with('success', 'CarbonDatabase has been saved Successfully');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('carbondatabase::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('carbondatabase::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
	public function importProjCarbonView(Request $request){
        
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->where('company_id', auth()->user()->company_id);
            }
        }
        $allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select project', '');

        return view('carbondatabase::Carbonimport', compact('allProjects'));
    }
	public function importCarbonUpload(Request $request){
       
        try {
            $path = $request->file('file')->store('temp'); 
            
           // $path = storage_path('app').'/'.$path;  
            
             $import = new ImportCarbon();
           
              $import->import($path); 
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
        
        
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->where('company_id', auth()->user()->company_id);
            }
        }
        $allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select project', '');

        return view('carbondatabase::Carbonimport', compact('allProjects'));

        //return redirect()->route('estimates.projects', $request->project_id)->with("success", "Estimates has been imported successfully!");
    }
	public function ajaxListAllCarbon(DatatableRequest $request) {
		 
          $query = DB::table('carbon_database')->select('id','materials','embodied_carbon','notes')->get();
        
			$query->when($request->project_filter_id, function($q) use($request){
				$q->where('id', $request->project_filter_id);
			});
		
			return datatables()->of($query)
					->addColumn('id', function ($doc) {
					   return $doc->id;
					})
					->addColumn('materials', function ($doc) {
					   return $doc->materials;
					})
					->addColumn('embodied_carbon', function ($doc) {
					   return $doc->embodied_carbon;
					})
					->addColumn('notes', function ($doc) {
					   return $doc->notes;
					})
					->make(true);

		}
		public function ajaxListAllCarbonmaterial(DatatableRequest $request) {
		 
          $query = DB::table('carbon_a_one_a_five')->select('id','materials','factors','mass')->get();
        
			$query->when($request->project_filter_id, function($q) use($request){
				$q->where('id', $request->project_filter_id);
			});
		
			return datatables()->of($query)
					->addColumn('id', function ($doc) {
					   return $doc->id;
					})
					->addColumn('materials', function ($doc) {
					   return $doc->materials;
					})
					->addColumn('factors', function ($doc) {
					   return $doc->factors;
					})
					->addColumn('mass', function ($doc) {
					   return $doc->mass;
					})
					->make(true);

		}
			public function ajaxListCarbon(DatatableRequest $request) {
		 
          //$query = DB::table('user_carbon_database')->select('id','materials','factors','mass')->get();
		  
		     if(auth()->user()->isRole('Super Admin')){
			   $query =DB::table('user_carbon_database')->select('id','materials','factors','mass','company_id')->get();  
		       }
            elseif(auth()->user()->isRole('Admin')){
				 $query =DB::table('user_carbon_database')
				->join('users', 'user_carbon_database.company_id', '=', 'users.id')
				->select('user_carbon_database.id As id','user_carbon_database.materials As materials','user_carbon_database.factors As factors','user_carbon_database.mass As mass')
				->where('user_carbon_database.company_id',auth()->id())
				->get();
                //$query->where('company_id', auth()->id());
            }else{
				 $query =DB::table('user_carbon_database')
				->join('users', 'user_carbon_database.company_id', '=', 'users.id')
				->select('user_carbon_database.id As id','user_carbon_database.materials As materials','user_carbon_database.factors As factors','user_carbon_database.mass As mass')
				->get();
                //$query->where('company_id', auth()->user()->company_id);
            }
        
         
			$query->when($request->project_filter_id, function($q) use($request){
				$q->where('id', $request->project_filter_id);
			});
		
			return datatables()->of($query)
					->addColumn('id', function ($doc) {
					   return $doc->id;
					})
					->addColumn('materials', function ($doc) {
					   return $doc->materials;
					})
					->addColumn('factors', function ($doc) {
					   return $doc->factors;
					})
					->addColumn('mass', function ($doc) {
					   return $doc->mass;
					})
					->make(true);

		}
			public function ajaxGhgListCarbon(DatatableRequest $request) {
		 
          //$query = DB::table('user_carbon_database')->select('id','materials','factors','mass')->get();
		  
		    
			   $query =DB::table('carbon_ghg')->select('id','materials','factors','unit')->get();  
		       
        
         
			$query->when($request->project_filter_id, function($q) use($request){
				$q->where('id', $request->project_filter_id);
			});
		
			return datatables()->of($query)
					->addColumn('id', function ($doc) {
					   return $doc->id;
					})
					->addColumn('materials', function ($doc) {
					   return $doc->materials;
					})
					->addColumn('factors', function ($doc) {
					   return $doc->factors;
					})
					->addColumn('mass', function ($doc) {
					   return $doc->unit;
					})
					->make(true);

		}

}
