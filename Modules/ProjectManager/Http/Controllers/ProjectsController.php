<?php

namespace Modules\ProjectManager\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ProjectManager\Entities\Project;
use Modules\ProjectManager\Http\Requests\AssignUsersInProjectRequest;
use Modules\ProjectManager\Http\Requests\CreateProjectNewVersionRequest;
use Modules\ProjectManager\Http\Requests\CreateProjectRequest as CreateProjectRequest;
use Modules\ProjectManager\Http\Requests\UpdateProjectRequest as UpdateProjectRequest;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        if (!auth()->user()->can('access', 'projects visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        return view('projectmanager::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        if (!auth()->user()->can('access', 'projects add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        // Companies
        $companies = User::select(['id', 'company_name'])
            ->whereHas('roles', function ($query) {
                $query->where('slug', '=', 'admin');
            });

        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $companies->where('id', auth()->id());
            }else{
                $companies->where('id', auth()->user()->company_id);
            }
        }
        $companies = $companies->pluck('company_name', 'id');
        $companies->prepend('Select Company', '');

        return view('projectmanager::createOrUpdate', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateProjectRequest $request) {
        if (!auth()->user()->can('access', 'projects add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
  
        try {
            \DB::beginTransaction();
            $project = new Project($request->all());
            $project->save();	

            \DB::commit();	
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withError($e->getMessage())->withInput();
        }
      return redirect()->route('projects')->with('success', 'Project has been Create Successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id) {
        if (!auth()->user()->can('access', 'projects visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $project = Project::find($id);
        $user=auth()->user();

        return view('projectmanager::show', compact(['project','user']));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id) {
        if (!auth()->user()->can('access', 'projects add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $project = Project::find($id);
        // Companies
        $companies = User::select(['id', 'company_name'])
            ->whereHas('roles', function ($query) {
                $query->where('slug', '=', 'admin');
            });

        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $companies->where('id', auth()->id());
            }else{
                $companies->where('id', auth()->user()->company_id);
            }
        }

        $companies = $companies->pluck('company_name', 'id');
        
        $companies->prepend('Select Company', '');

        return view('projectmanager::createOrUpdate', compact('project', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateProjectRequest $request, $id) {
        if (!auth()->user()->can('access', 'projects add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $project = Project::findOrFail($id);
        try {
            \DB::beginTransaction();
            $project->update($request->all());

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('projects')->with('success', 'Project has been updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id) {
        if (!auth()->user()->can('access', 'projects add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            $project = Project::findOrFail($id);
            DB::beginTransaction();

            $project->delete();

            DB::commit();
            return redirect()->route('projects')->withSuccess('This project has been deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('projects')->withError('Somthing went wrong. Please try again later.');
        }
    }

    /**
     * Get the specified resource from storage.
     * @param Request $request
     * @return Response
     */

    public function createProjectNewVersion(){
        if (!auth()->user()->can('access', 'projects add')) {
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
        $projects = $query->get();
        $projects = $projects->pluck('project_title', 'id');
		return view('projectmanager::createNewVersion', compact('projects'));
		}else{
		$projects = $query->get(['id', 'project_title', 'version']);
        $projects = $projects->pluck('display_project_title', 'id');
        return view('projectmanager::createNewVersion', compact('projects'));
		}
       
    }

    /**
     * Get the specified resource from storage.
     * @param Modules\ProjectManager\Http\Requests\CreateProjectNewVersionRequest $request
     * @return Response
     */

    public function storeProjectNewVersion(CreateProjectNewVersionRequest $request){
        if (!auth()->user()->can('access', 'projects add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            $project = Project::findOrFail($request->project_id);
            DB::beginTransaction();
            $version = (Project::where('project_copy_id', $project->id)->count()+1);
            $newVersionProject = $project->replicate();
            $newVersionProject->unique_reference_no = 'REF-'.$version.'-'.rand(11111111, 99999999);
            $newVersionProject->project_copy_id = $project->id;
            $newVersionProject->version = $version;
            $newVersionProject->push();

            DB::commit();
            return redirect()->route('projects')->with('success', 'This project new version has been created Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('projects.new.version')->with('error', 'Somthing went wrong please try again later!');
        }
        
    }

    /**
     * Get the specified resource from storage.
     * @param DatatableRequest $request
     * @return Response
     */
    public function ajaxListAllProjects(DatatableRequest $request) {

        $query = Project::select(['id', 'company_id', 'unique_reference_no', 'project_title', 'tender_status', 'region', 'type_of_contract', 'shifts', 'status']);
    
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
				$query =DB::table('users_project')
            ->join('projects', 'projects.id', '=', 'users_project.project_id')
            ->select('projects.id As id','projects.company_id AS company_id','projects.unique_reference_no','projects.project_title','projects.tender_status','projects.region','projects.type_of_contract','projects.shifts','projects.status')
			->where([
			['company_id', auth()->user()->company_id],
			['users_project.users_id', auth()->id()]
		   ])
		    ->distinct('users_project.project_id');
		    $project=User::find(auth()->user()->id);
               // $query->where('company_id', auth()->user()->company_id);
            }
        }
        $query->when(($request->has('status_filter_id') && ($request->status_filter_id != null)), function($q) use($request){
            $q->where('status', $request->status_filter_id);
        });
        
        $query->when(($request->has('tender_status_filter_id') && ($request->tender_status_filter_id != null)), function($q) use($request){
            $q->where('tender_status', $request->tender_status_filter_id);
        });

        $query->when(($request->has('region_filter_id') && ($request->region_filter_id != null)), function($q) use($request){
            $q->where('region', $request->region_filter_id);
        });

        $query->when(($request->has('shift_filter_id') && ($request->shift_filter_id != null)), function($q) use($request){
            $q->where('shifts', $request->shift_filter_id);
        });

        return datatables()->of($query)
                ->editColumn('company', function ($project) {
					
            if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
				 $project=User::find(auth()->user()->id);
                return $project->company->company_name ?? '-';
            }else{
				 return $project->company->company_name ?? '-';
			}
							
                   
                })

                ->editColumn('project_title', function ($project) {
                    $actions = "";
                    $actions .= "<a href=" . route('projects.view', ['id' => $project->id]) . ">".$project->project_title."</a>";
                    return $actions;
                    
                })

                ->editColumn('unique_reference_no', function ($project) {
                    $actions = "";
                    $actions .= "<a href=" . route('projects.view', ['id' => $project->id]) . ">".$project->unique_reference_no."</a>";
                    return $actions;
                    
                })
                ->editColumn('tender_status', function ($project) {
                    $actions = "";
                    $status = $project->status;
                    if($project->tender_status == 0){
                        $actions .= "Unsuccessful";
                    }elseif($project->tender_status == 1){
                        $actions .= "Live";
                    }elseif($project->tender_status == 2){
                        $actions .= "Tender";
                    }
                    //$actions .= $project->tender_status ? "Live" : "Dead";
                    return $actions;
                })
                ->addColumn('bid_value', function ($project) {
                    $actions = "";
                    /*$status = $project->status;
                    $actions .= ($status == 1) ? "Active" : "Inactive";*/
                    return $actions;
                })
                ->addColumn('status', function ($project) {
                    $actions = "";
                    $status = $project->status;
                    $default="";
                    $user=User::find(auth()->user()->id);
                    if($user->default_project==$project->id)
                    {
                        $default=" (<b>Default</b>) ";
                    }
                    $actions .= ($status == 1) ? "Active".$default : "Inactive".$default;
                    return $actions;
                })
                ->addColumn('action', function ($project) {
                    $form="";
                    $user=User::find(auth()->user()->id);
                    if($user->default_project!=$project->id)
                    $form='<br><br><form action='.route('projects.make.default').' method="POST"> <input type="hidden" name="_token" value='.csrf_token().' /> <input type="hidden" name="_method" value="PATCH"> <input type="hidden" value='.$project->id.' name="project_id"> <button type="submit" class="btn btn-success btn-sm">Set as Default</button></form>';
                    $actions = "";
                   // $actions .= "<a href=\"" . route('projects.view', ['id' => $project->id]) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fa fa-cog\"></i></a>";
                   
                    if (auth()->user()->can('access', 'projects add')) {
                        $actions .= "&nbsp;<a href=\"" . route('projects.edit', ['id' => $project->id]) . "\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-pencil-alt\"></i></a>";
                        $actions .= "&nbsp;<a title=\"Delete Project\" onclick=\"return confirm('Are you sure want to remove the Project?')\" href=\"" . route('projects.delete', ['id' => $project->id]) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fa fa-trash\"></i></a>";
                    }

                    $actions .=  $form;
                    return $actions;
                })
                ->escapeColumns([])->make(true);
    }



    protected function makeDefault(Request $request) {
        if (!auth()->user()->can('access', 'projects add')) {
           return redirect('dashboard')->withError('Not authroized to access!');
        }
     
        if ($request->isMethod('patch')) {        
        $project = Project::findOrFail($request->project_id);

        User::where('id',auth()->user()->id)->update(['default_project'=>$project->id]);

        return back()->with('success', 'Project has been updated Successfully');

        }
    }

}
