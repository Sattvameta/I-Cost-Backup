<?php

namespace Modules\CarbonCalculator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Illuminate\Support\Facades\DB;
use App\CarbonCalculator;
use App\CarbonDatabase;
class CarbonCalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

		 $query = DB::table('carbon_database')->select('id','materials');
		 $allProjects = $query->get();
         $allProjects = $allProjects->pluck('materials');
         $allProjects->prepend('Select materials', '');
		 
		   $total =DB::table('v_carbon_database_calculator_total')
					->select('total')
					->sum('total');
					
		  
					//Print_r($total_quantity);
					 //$total = $total_val->get();
        //return view('carbondatabase::index', compact('allProjects'));
        return view('carboncalculator::index' , compact('allProjects','total'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
		$query = CarbonDatabase::select('carbondatabase_id');
		 $allProjects = $query->get('carbondatabase_id');
		
         $allProjects = $allProjects->pluck('carbondatabase_id');
		 // print_r( $allProjects);
         $allProjects->prepend('Select materials', '');
		  return view('carboncalculator::add' , compact('allProjects'));
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
      $co2 = $request->co2;
      $quantity = $request->quantity;
      $total = $request->total;
      for($count = 0; $count < count($materials); $count++)
      {
       $data = array(
        'materials' => $materials[$count],
        'co2'  => 0,
        'quantity'  => $quantity[$count],
        'total'  =>0
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
        return view('carboncalculator::show');
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
	
	public function ajaxListAllCarbon(DatatableRequest $request) {
		 
        //  $query = DB::table('carbon_calculator')->select('id','materials','co2','quantity','total')->get();
        $query =DB::table('carbon_calculator')
					->join('carbon_database','carbon_calculator.materials', '=', 'carbon_database.materials')
					->select('carbon_calculator.id AS id','carbon_calculator.quantity AS quantity', 'carbon_database.materials AS materials', 'carbon_database.embodied_carbon AS embodied_carbon')->orderby('id');
			$query->when($request->project_filter_id, function($q) use($request){
				$q->where('id', $request->project_filter_id);
			});
		
			return datatables()->of($query)
				
					->addColumn('materials', function ($doc) {
					   return $doc->materials;
					})
					->addColumn('embodied_carbon', function ($doc) {
					   return $doc->embodied_carbon;
					})
					->addColumn('quantity', function ($doc) {
					   return round($doc->quantity);
					})
					->addColumn('total', function ($doc) {
					   return round($doc->embodied_carbon * $doc->quantity,2);
					})
					->filter(function ($instance) use ($request) {
                      
                        if (!empty($request->get('search'))) {
                             $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('carbon_calculator.id', 'LIKE', "%$search%")
								->orWhere('carbon_database.materials', 'LIKE', "%$search%")
								->orWhere('carbon_database.embodied_carbon', 'LIKE', "%$search%")
								->orWhere('carbon_calculator.quantity', 'LIKE', "%$search%")
								->orWhere('total', 'LIKE', "%$search%");
                            });
                        }
						
                    })
					->addColumn('action', function ($doc) {
								$actions = "";
								  $actions .= "&nbsp;<a title=\"Delete\" onclick=\"return confirm('Are you sure want to remove?')\" href=\"" . route('delete', $doc->id) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"></i></a>";
								
								return $actions;
							})->rawColumns(['action'])					
					

					->make(true);

		}
}
