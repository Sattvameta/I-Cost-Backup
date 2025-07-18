<?php

namespace Modules\CarbonDatabase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Illuminate\Support\Facades\DB;

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
        //
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

}
