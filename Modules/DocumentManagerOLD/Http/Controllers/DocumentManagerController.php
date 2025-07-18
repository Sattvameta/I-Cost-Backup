<?php

namespace Modules\DocumentManager\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
//use Modules\DocumentManager\Entities\Doc;
use Modules\ProjectManager\Entities\Project;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Illuminate\Support\Facades\DB;

class DocumentManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
		 if (!auth()->user()->can('access', 'projects visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }		
      $query = Project::select('id', 'project_title', 'version');
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
                //$query->where('company_id', auth()->user()->company_id);
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

       
        return view('documentmanager::index', compact('allProjects'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
       // return view('documentmanager::create');
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
    public function show(Request $request,$id)
    {
	
		$doc = DB::table('v_purchase_note_certificate')->select('*')->where('rowcount',$id)->get();
        

        return view('documentmanager::show', compact('doc'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //return view('documentmanager::edit');
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
	  public function ajaxListAllDocument(DatatableRequest $request) {
		 
		
		   
         $query = DB::table('v_purchase_note_certificate')->select('id','project_id', 'purchase_no','cer_or_delnote','doc_type','category','module','storage','rowcount');
            
				if(!auth()->user()->isRole('Super Admin')){
					if(auth()->user()->isRole('Admin')){
						if(auth()->user()->isRole('users')){
					 $query->whereHas('project', function($q){
						$q->whereHas('users', function($q){
							$q->where('id', auth()->id());
						});
					  });
				    }
				  }
				}
        
			$query->when($request->project_filter_id, function($q) use($request){
				$q->where('project_id', $request->project_filter_id);
			});
		
			return datatables()->of($query)
					->addColumn('id', function ($doc) {
					   return $doc="HS000".$doc->id."";
					})
					
					
				   ->addColumn('check',  function ($doc) {
					  
					   return '<a href= "#" class=\"btn btn-success btn-sm\"><input type="text" class="btn btn-success btn-sm" name="blockchain[]" value="Block Chain" readonly/></a>'; 
					   
					})
					
					->addColumn('action', function ($doc) {
								$actions = "";
								$actions .= "<a href=\" ../".$doc->storage."/".$doc->cer_or_delnote."\" class=\"btn btn-primary btn-sm\" target=\"_blanck\"><i class=\"fas fa-eye\"></i></a>";
								$actions .= "&nbsp<a href=\"" . route('documentmanager.view', ['rowcount' => $doc->rowcount]) . "\" class=\"btn btn-success btn-sm\"><i class=\"fas fa-print\"></i></a>";
								
								return $actions;
							})->rawColumns(['check'],['action'])
					->make(true);

		}

}
