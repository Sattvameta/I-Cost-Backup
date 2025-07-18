<?php

namespace Modules\CarbonCalculator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Illuminate\Support\Facades\DB;
use Modules\ProjectManager\Entities\Project;
use App\CarbonCalculator;
use App\CarbonDatabase;
use Modules\EstimateManager\Http\Requests\ImportActivityRequest;
use App\Imports\CarbonImports;

class CarbonCalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

		 	   $proj = Project::select('id', 'project_title', 'version');
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $proj->where('company_id', auth()->id());
            }else{
               $proj =DB::table('users_project')
				->join('projects', 'projects.id', '=', 'users_project.project_id')
				->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
				->where([
				['company_id', auth()->user()->company_id],
				['users_project.users_id', auth()->id()],
				['status',1]
			   ]);
                //$query->where('company_id', auth()->user()->company_id);
            }
        }
        if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $Projects = $proj->get();
        $Projects = $Projects->pluck('project_title', 'id');
         $Projects->prepend('Select Project', '');
		}else{
		$Projects = $proj->get(['id', 'project_title', 'version']);
        $Projects = $Projects->pluck('display_project_title', 'id');
        $Projects->prepend('Select Project', '');
		}
		 
		   $total =DB::table('v_carbon_database_calculator_total')
					->select('total')
					->sum('total');
					
		   $user=auth()->user();
        if(isset($user->default_project) && !isset($id) )
        {
       $project = Project::find($user->default_project);

       if($project)
        $id=$user->default_project;

        }
					//Print_r($total_quantity);
					 //$total = $total_val->get();
        //return view('carbondatabase::index', compact('allProjects'));
        return view('carboncalculator::index' , compact('Projects','total','user'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
		 $carbondatabase=DB::table('carbon_database_carbon_calculator')->select('user_id','carbon_database_id','carbon_a_one_a_five_id','user_database_id')->where('user_id',auth()->id())->get();
		foreach ($carbondatabase as $object){
		if($object->carbon_a_one_a_five_id =="1"){
		$query = DB::table('carbon_a_one_a_five')->select('id','materials');
		}elseif($object->user_database_id =="1"){
	    $query = DB::table('user_carbon_database')->select('id','materials');
		}else{
	    $query = DB::table('carbon_database')->select('id','materials');
		}
	}
		 $allProjects = $query->get('materials');
		
         $allProjects = $allProjects->pluck('id','materials');
		 // print_r( $allProjects);
         $allProjects->prepend('', 'Select materials / Type');
		 
		 $query_one = DB::table('carbon_a_four')->select('id','transport');
		 $allProjects_one = $query_one->get('transport');
		
         $allProjects_one = $allProjects_one->pluck('id','transport');
		 // print_r( $allProjects);
         $allProjects_one->prepend('','Transport');
		 
		 $query_two = DB::table('carbon_a_five')->select('id','wastage');
		 $allProjects_two = $query_two->get('wastage');
		
         $allProjects_two = $allProjects_two->pluck('id','wastage');
		 // print_r( $allProjects);
         $allProjects_two->prepend('','Wastage');
		 
		   $proj = Project::select('id', 'project_title', 'version');
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $proj->where('company_id', auth()->id());
            }else{
               $proj =DB::table('users_project')
				->join('projects', 'projects.id', '=', 'users_project.project_id')
				->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
				->where([
				['company_id', auth()->user()->company_id],
				['users_project.users_id', auth()->id()],
				['status',1]
			   ]);
                //$query->where('company_id', auth()->user()->company_id);
            }
        }
        if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $Projects = $proj->get();
        $Projects = $Projects->pluck('project_title', 'id');
         $Projects->prepend('Select Project', '');
		}else{
		$Projects = $proj->get(['id', 'project_title', 'version']);
        $Projects = $Projects->pluck('display_project_title', 'id');
        $Projects->prepend('Select Project', '');
		}
		  return view('carboncalculator::add' , compact('allProjects','allProjects_one','allProjects_two','Projects'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if($request->ajax())
     {

      $materials = $request->materials;
      $Transport = $request->Transport;
      $wastage = $request->wastage;
      $quantity = $request->quantity;
      $project_id = $request->project_id;
      for($count = 0; $count < count($materials); $count++)
      {
       $data = array(
        'materials' => $materials[$count],
        'Transport'  => $Transport[$count],
        'wastage'  => $wastage[$count],
        'quantity'  => $quantity[$count],
        'project_id'  => $project_id[$count]
       );
       $insert_data[] = $data; 
      }

      CarbonCalculator::insert($insert_data);
      return response()->json([
       'success'  => 'Data Added successfully.'
      ]);
     }
    }
    

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
		 $carbon_formula = DB::table('v_carbon_database_calculator_total')->select('id','materials','transport','wastage','quantity','Total','factors','transport_factor','wastage_factor','a_five','mass')->where('id',$id)->get();
         return view('carboncalculator::show', compact('carbon_formula'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('carboncalculator::edit');
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
       $purchaseOrder = CarbonCalculator::findOrFail($id);
            DB::beginTransaction();
            $purchaseOrder->delete();
            DB::commit();
            return redirect()->route('carboncalculator')->with('success', 'Deleted successfully!');
    }
	    /**
     * Display project estimate import view.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function importProjectCarbonView(Request $request){
        
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

        return view('carboncalculator::importCarbon', compact('allProjects'));
    }
     public function importProjCarbon(Request $request){
       
        try {
            $path = $request->file('file')->store('temp'); 
            
           // $path = storage_path('app').'/'.$path;  
            
             $import = new CarbonImports($request->project_id);
           
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

        return view('carboncalculator::importCarbon', compact('allProjects'));

        //return redirect()->route('estimates.projects', $request->project_id)->with("success", "Estimates has been imported successfully!");
    }
    
	public function ajaxListAllCarbon(DatatableRequest $request) {
		 
        $query = DB::table('v_carbon_database_calculator_total')->select('id','materials','transport','wastage','quantity','Total','created_at','project_id');
        /*$query =DB::table('carbon_calculator')
					->join('carbon_database','carbon_calculator.materials', '=', 'carbon_database.carbondatabase_id')
					->select('carbon_calculator.id AS id','carbon_calculator.quantity AS quantity', 'carbon_database.materials AS materials', 'carbon_database.embodied_carbon AS embodied_carbon')->orderby('id');*/
					
			$query->when($request->project_filter_id, function($q) use($request){
				$q->where('project_id', $request->project_filter_id);
			});
		
			return datatables()->of($query)
					
					->addColumn('materials', function ($doc) {
						
					          return $doc->materials;
						  
					})
					->addColumn('transport', function ($doc) {
					
					          return $doc->transport;
						  
					})
					->addColumn('wastage', function ($doc) {
						
					          return $doc->wastage;
						  
					})
					->addColumn('quantity', function ($doc) {
					   return $doc->quantity;
					})
					->addColumn('total', function ($doc) {
					   return round($doc->Total,2);
					})
					->addColumn('created_at', function ($doc) {
					   return $doc->created_at;
					})
					->addColumn('action', function ($doc) {
								$actions = "";
								  $actions .= "&nbsp;<a title=\"View\" href=\"" . route('view', $doc->id) . "\"  class=\"btn btn-success btn-sm\"><i class=\"fas fa-eye\"></i></a>";
								  $actions .= "&nbsp;<a title=\"Delete\" onclick=\"return confirm('Are you sure want to remove?')\" href=\"" . route('delete', $doc->id) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"></i></a>";
								
								return $actions;
							})->rawColumns(['action'])					
					

					->make(true);

		}
}
