<?php

namespace Modules\CarbonDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ProjectManager\Entities\Project;
use App\CarbonTotal;
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
		 
		 $user = CarbonTotal::where("year",$year)->sum('Total');
		 
		 $year_one= $year+1;
		 
		 $user_one = CarbonTotal::where(\DB::raw("year"),$year_one)->sum('Total');
		 $year_two= $year+2;
		 
		 $user_two = CarbonTotal::where(\DB::raw("year"),$year_two)->sum('Total');
		 
		 $year_three= $year+3;
		 $user_three = CarbonTotal::where(\DB::raw("year"),$year_three)->sum('Total');
		 
		  $year_four= $year+4;
		 $user_four = CarbonTotal::where(\DB::raw("year"),$year_four)->sum('Total');
		 
		  $year_five= $year+5;
		 
		 $user_five = CarbonTotal::where(\DB::raw("year"),$year_five)->sum('Total');
		 $year_six= $year+6;
		 
		 $user_six = CarbonTotal::where(\DB::raw("year"),$year_six)->sum('Total');
		 
		 $year_seven= $year+7;
		 $user_seven = CarbonTotal::where(\DB::raw("year"),$year_seven)->sum('Total');
		 
		  $year_eight= $year+8;
		 $user_eight = CarbonTotal::where(\DB::raw("year"),$year_eight)->sum('Total');
		     /*$current= date("Y");
		     $year = [$current,$current+1,$current+2,$current+3,$current+4,$current+5,$current+6,$current+7,$current+8,$current+9,$current+10,$current+11,$current+12,$current+13,$current+14,$current+15,$current+16,$current+17,$current+18,$current+19,$current+20,$current+21,$current+22,$current+23,$current+24,$current+25,$current+26,$current+27,$current+28];
		    

        $user = [];
        foreach ($year as $key => $value) {
            $user[] = CarbonTotal::where(\DB::raw("DATE_FORMAT(created_at, '%Y')"),$value)->sum('Total');
        }*/
		
		

    	return view('carbondashboard::index')->with('year',json_encode($year))->with('user',($user))->with('year_one',json_encode($year_one))->with('user_one',($user_one))->with('year_two',json_encode($year_two))->with('user_two',($user_two))->with('year_three',json_encode($year_three))->with('user_three',($user_three))->with('year_four',json_encode($year_four))->with('user_four',($user_four))->with('year_five',json_encode($year_five))->with('user_five',($user_five))->with('year_six',json_encode($year_six))->with('user_six',($user_six))->with('user_seven',($user_seven))->with('year_seven',json_encode($year_seven))->with('user_eight',($user_eight))->with('year_eight',json_encode($year_eight));
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
