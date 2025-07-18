<?php

namespace Modules\FormulaManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\FormulaManager\Entities\Formula;
use Modules\ProjectManager\Entities\Project;
use Modules\FormulaManager\Http\Requests\GeneralFormulaRequest;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Illuminate\Support\Facades\DB;

class FormulaManagerController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        if (!auth()->user()->can('access', 'central document visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        
        if(auth()->user()->isRole('Super Admin')){
            $projects = Project::whereStatus(1)->get(['id', 'project_title', 'version']);
        }else{
            $projects = Project::whereHas('users', function($q){
                $q->where('id', auth()->id());
            })
            ->whereStatus(1)
            ->get(['id', 'project_title', 'version']);
        }
        $projects = $projects->pluck('display_project_title', 'id');
        $projects->prepend('All', '');

        return view('formulamanager::index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        if (!auth()->user()->can('access', 'estimates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        if(auth()->user()->isRole('Super Admin')){
            $projects = Project::whereStatus(1)->get(['id', 'project_title', 'version']);
        }else{
            $projects = Project::whereHas('users', function($q){
                $q->where('id', auth()->id());
            })
            ->whereStatus(1)
            ->get(['id', 'project_title', 'version']);
        }
        $projects = $projects->pluck('display_project_title', 'id');

        $projects->prepend('Select Project', '');

        return view('formulamanager::createOrUpdate', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  GeneralFormulaRequest $request
     * @return Response
     */
    public function store(GeneralFormulaRequest $request) {
        try {
            \DB::beginTransaction();
            $formula = new Formula($request->all());
            $formula->save();	

            \DB::commit();	
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('formulas')->with('success', 'Formula has been saved Successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id) {
        if (!auth()->user()->can('access', 'estimates visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $formula = Formula::findOrFail($id);

        return view('formulamanager::show', compact('formula'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id) {
        if (!auth()->user()->can('access', 'estimates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }

        $formula = Formula::findOrFail($id);

        if(auth()->user()->isRole('Super Admin')){
            $projects = Project::whereStatus(1)->get(['id', 'project_title', 'version']);
        }else{
            $projects = Project::whereHas('users', function($q){
                $q->where('id', auth()->id());
            })
            ->whereStatus(1)
            ->get(['id', 'project_title', 'version']);
        }
        $projects = $projects->pluck('display_project_title', 'id');

        $projects->prepend('Select Project', '');
        return view('formulamanager::createOrUpdate', compact('formula', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     * @param  GeneralFormulaRequest $request
     * @return Response
     */
    public function update(GeneralFormulaRequest $request, $id) {
        $formula = Formula::findOrFail($id);
        try {
            \DB::beginTransaction();
            $formula->update($request->all());
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('formulas')->with('success', 'Formula has been updated Successfully');
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
            $formula = Formula::findOrFail($id);
            DB::beginTransaction();
            $formula->delete();
            DB::commit();
            $responce = ['status' => true, 'message' => 'This formula has been deleted Successfully!', 'data' => $formula];
            return redirect()->route('formulas')->with('success', $responce['message']);
        } catch (\Exception $e) {
            DB::rollBack();
            $responce = ['status' => false, 'message' => $e->getMessage()];
            return redirect()->route('formulas')->with('error', $responce['message']);
        }
    }

    /**
     * Get the specified resource from storage.
     * @param DatatableRequest $request
     * @return Response
     */
    public function ajaxListAllFormulas(DatatableRequest $request) {

        $query = Formula::select(['id', 'project_id', 'keyword', 'description', 'formula', 'value']);
        if(!auth()->user()->isRole('Super Admin')){
            $query->whereHas('project', function($q){
                $q->whereHas('users', function($q){
                    $q->where('id', auth()->id());
                });
            });
            
        }
        $query->when($request->status_filter_id, function($q) use($request){
            $q->where('status', $request->status_filter_id);
        });
        
        $query->when($request->project_filter_id, function($q) use($request){
            $q->where('project_id', $request->project_filter_id);
        });
        return datatables()->of($query)
                ->addColumn('project_title', function ($formula) {
                    return $formula->project->display_project_title;
                })
                ->addColumn('action', function ($formula) {
                            $actions = "";
                            $actions .= "<a href=\"" . route('formulas.view', ['id' => $formula->id]) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-folder\"></i> View</a>";
                            if (auth()->user()->can('access', 'estimates add')) {
                                $actions .= "&nbsp;<a href=\"" . route('formulas.edit', ['id' => $formula->id]) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-pencil-alt\"></i> Edit</a>";
                                $actions .= "&nbsp;<a title=\"Delete Formula\" onclick=\"return confirm('Are you sure want to remove the Formula?')\" href=\"" . route('formulas.delete', ['id' => $formula->id]) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"></i> Delete</a>";
                            }
                            return $actions;
                        })
                ->make(true);
    }

}
