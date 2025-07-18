<?php

namespace Modules\ReportManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Exports\ExportStaffTimesheet;
use App\Exports\ExportLabourTimesheet;
use App\Exports\ExportCarbonCalculator;
use App\Exports\ExportPurchase;
use Modules\ProjectManager\Entities\Project;
use Modules\PurchaseManager\Entities\Purchase;
use Illuminate\Support\Facades\DB;

class ReportManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('access', 'reports visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query =DB::table('users_project')
				->join('projects', 'projects.id', '=', 'users_project.project_id')
				->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
				->where([
				['company_id', auth()->user()->company_id],
				['users_project.users_id', auth()->id()],
				['status',1]
			   ]);
            }
        }
         if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $allProjects = $query->get();
        $allProjects = $allProjects->pluck('project_title', 'id');
         $allProjects->prepend('Select Project', '');
		}else{
		$allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select Project', '');
		}
        return view('reportmanager::index', compact('allProjects'));
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function viewPurchaseReport(Request $request, $id){
        $purchase = Purchase::findOrfail($id);
        return view('reportmanager::purchase_report_info', compact('purchase'));
    }

    /**
     * Export staff timesheet.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function exportStaffTimesheet(Request $request, $id){
        try{
            return (new ExportStaffTimesheet($id))->download('staff_timesheet_report.csv', \Maatwebsite\Excel\Excel::CSV);
        }catch(\Exception $e){
            return redirect()->back()->withError($e->getMessage());
        }
    }

    /**
     * Export labour timesheet.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function exportLabourTimesheet(Request $request, $id){
        try{
            return (new ExportLabourTimesheet($id))->download('labour_timesheet_report.csv', \Maatwebsite\Excel\Excel::CSV);
        }catch(\Exception $e){
            return redirect()->back()->withError($e->getMessage());
        }
    }
	
	 public function exportCarbonCalculator(Request $request, $id){
        try{
            return (new ExportCarbonCalculator($id))->download('carbon_calculator_report.csv', \Maatwebsite\Excel\Excel::CSV);
        }catch(\Exception $e){
            return redirect()->back()->withError($e->getMessage());
        }
    }

 public function exportPurchase(Request $request, $id){
        try{
            return (new ExportPurchase($id))->download('Purchase_report.csv', \Maatwebsite\Excel\Excel::CSV);
        }catch(\Exception $e){
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
