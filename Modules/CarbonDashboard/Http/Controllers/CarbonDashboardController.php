<?php

namespace Modules\CarbonDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ProjectManager\Entities\Project;
use App\CarbonTotal;
use App\CarbonTotalUser;
use App\CarbonTotalDatabase;
use Illuminate\Support\Facades\DB;
class CarbonDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
		 $year= date("Y");
		
		 $carbondatabase=DB::table('carbon_database_carbon_calculator')->select('user_id','carbon_database_id','carbon_a_one_a_five_id','user_database_id')->where('user_id',auth()->id())->get();
		foreach ($carbondatabase as $object){
			if($object->carbon_database_id =="1"){ 
			    
			    $user = CarbonTotalDatabase::where('user_id',auth()->id())->sum('Total');
		 
		 
		     /*$current= date("Y");
		     $year = [$current,$current+1,$current+2,$current+3,$current+4,$current+5,$current+6,$current+7,$current+8,$current+9,$current+10,$current+11,$current+12,$current+13,$current+14,$current+15,$current+16,$current+17,$current+18,$current+19,$current+20,$current+21,$current+22,$current+23,$current+24,$current+25,$current+26,$current+27,$current+28];
		    

        $user = [];
        foreach ($year as $key => $value) {
            $user[] = CarbonTotal::where(\DB::raw("DATE_FORMAT(created_at, '%Y')"),$value)->sum('Total');
        }*/
		 
		
		 
			}elseif($object->user_database_id =="1"){
			    
			    
		 
		 $user = CarbonTotalUser::where('user_id',auth()->id())->sum('Total');
		 
		
		     /*$current= date("Y");
		     $year = [$current,$current+1,$current+2,$current+3,$current+4,$current+5,$current+6,$current+7,$current+8,$current+9,$current+10,$current+11,$current+12,$current+13,$current+14,$current+15,$current+16,$current+17,$current+18,$current+19,$current+20,$current+21,$current+22,$current+23,$current+24,$current+25,$current+26,$current+27,$current+28];
		    

        $user = [];
        foreach ($year as $key => $value) {
            $user[] = CarbonTotal::where(\DB::raw("DATE_FORMAT(created_at, '%Y')"),$value)->sum('Total');
        }*/
		   
		 
			}else{		
			 
		 
		 $user = CarbonTotal::where('user_id',auth()->id())->sum('Total');
		 
	
		     /*$current= date("Y");
		     $year = [$current,$current+1,$current+2,$current+3,$current+4,$current+5,$current+6,$current+7,$current+8,$current+9,$current+10,$current+11,$current+12,$current+13,$current+14,$current+15,$current+16,$current+17,$current+18,$current+19,$current+20,$current+21,$current+22,$current+23,$current+24,$current+25,$current+26,$current+27,$current+28];
		    

        $user = [];
        foreach ($year as $key => $value) {
            $user[] = CarbonTotal::where(\DB::raw("DATE_FORMAT(created_at, '%Y')"),$value)->sum('Total');
        }*/
		  
			}
		}
		
		 
		     /*$current= date("Y");
		     $year = [$current,$current+1,$current+2,$current+3,$current+4,$current+5,$current+6,$current+7,$current+8,$current+9,$current+10,$current+11,$current+12,$current+13,$current+14,$current+15,$current+16,$current+17,$current+18,$current+19,$current+20,$current+21,$current+22,$current+23,$current+24,$current+25,$current+26,$current+27,$current+28];
		    

        $user = [];
        foreach ($year as $key => $value) {
            $user[] = CarbonTotal::where(\DB::raw("DATE_FORMAT(created_at, '%Y')"),$value)->sum('Total');
        }*/
		

    	return view('carbondashboard::index', compact('carbondatabase'));
    }
       
		
    

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('carbondashboard::create');
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
        return view('carbondashboard::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('carbondashboard::edit');
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
	 
}
