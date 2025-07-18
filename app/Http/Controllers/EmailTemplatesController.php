<?php

namespace App\Http\Controllers;

use Modules\Admin\Http\Requests\EmailTemplateRequest;
use App\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Yajra\DataTables\Utilities\Request as DatatableRequest;
use File;
use DB;
use Config;

class EmailTemplatesController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
            if (!auth()->user()->can('access', 'emailtemplates visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $title = 'Email Templates';
        return view('email_templates.index', compact('title'));
    }

    /**
     * Feeding list of roles to datatable.
     * @return Response
     */
    public function ajaxList(DatatableRequest $request) {



        $emailtemplates = Emailtemplate::get();

if ($request->status != '') {
            $emailtemplates = Emailtemplate::where('status', $request->status)->get();
}

        return datatables()->of($emailtemplates)
                        ->addColumn('action', function ($emailtemplates) {
                            $actions = '';
                            $actions .= "<a href=\"".url('/emailtemplates/view/'.$emailtemplates->id)."\" class=\"btn btn-success btn-sm\"><i class=\"fa fa-eye\"></i> </a>";
                             if (auth()->user()->can('access', 'emailtemplates add')) {
                                $actions .= "<a href=\"" . route('emailtemplates.edit', ['id' => $emailtemplates->id]) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fa fa-edit\"></i> </a>";
                            }
                            return $actions;
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
          if (!auth()->user()->can('access', 'emailtemplates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $title = 'New email template';
        return view('email_templates.form', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\Admin\Http\Requests\EmailTemplateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $validatedData = $request->validate([
            'type'    => 'required|unique:email_templates,type,' . $request->segment(3),
            'status' => 'required',
            'subject' => 'required|max:255',
            'template' => 'required|min:5|max:3000',
        ]);

        try {

            $EmailTemplate = EmailTemplate::create($request->all());

            if ($EmailTemplate) {
                return redirect()->route('emailtemplates')->with('message', 'Email template has been added successfully!');
            } else {
                return redirect()->back();
            }
        } catch (Exception $ex) {
            return redirect()->back();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \Modules\Admin\Entities\EmailTemplate $emailtemplate
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
          if (!auth()->user()->can('access', 'emailtemplates visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
         $emailtemplate = Emailtemplate::find($id);
         //dd($emailtemplates);
        // emailtemplate
        $title = 'Email template';
       // $emailtemplate->template = File::get(resource_path("views/emails/" . $emailtemplate->type . ".blade.php"));
        return view('email_templates.show', compact('emailtemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Modules\Admin\Entities\EmailTemplate $emailtemplate
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
  if (!auth()->user()->can('access', 'emailtemplates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $emailtemplate = EmailTemplate::find($id);
        //dd($emailtemplate);
        $title = 'Edit email template';

        //$emailtemplate->template = File::get(resource_path("views/emails/" . $emailtemplate->type . ".blade.php"));
        return view('email_templates.form', compact('title', 'emailtemplate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Modules\Admin\Http\Requests\EmailTemplateRequest $request
     * @param  \Modules\Admin\Entities\EmailTemplate $emailtemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $validatedData = $request->validate([
            'type'    => 'required|unique:email_templates,type,' . $request->segment(3),
             'status' => 'required',
            'subject' => 'required|max:255',
            'template' => 'required|min:5|max:3000',
        ]);

        try{
            $EmailTemplate = EmailTemplate::findOrFail($id);
            if($EmailTemplate->update($request->all())){
                return redirect()->route('emailtemplates')->with('message', 'Email template has been updated successfully!');
            }else{
                return redirect()->back();
            }
        } catch (Exception $ex) {
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Modules\Admin\Http\Requests\EmailTemplateRequest $request
     * @param  \Modules\Admin\Entities\EmailTemplate $emailtemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplateRequest $request, EmailTemplate $emailtemplate) {
        $response = [];
        if ($request->ajax()) {
            if ($emailtemplate->delete()) {
                $response['status'] = "success";
                $response['message'] = __('admin::email_template.deleted');
            } else {
                $response['status'] = "error";
                $response['message'] = __('admin::email_template.not_deleted');
            }
        }

        return \Response::json($response);
    }

    /**
     * Remove the multiple resource from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy_multiple(Request $request) {
        $response = [];
        if ($request->ajax()) {
            $ids = $request->input('ids');
            if (!empty($ids)) {
                $page = Page::whereIn('id', $ids);
                if ($page->delete()) {
                    $response['status'] = "success";
                    $response['message'] = "Pages has been deleted.";
                } else {
                    $response['status'] = "error";
                    $response['message'] = "Somthing went wrong try again later.";
                }
            }
        }

        return \Response::json($response);
    }

    /**
     * Show a list of all the pages formatted for Datatables.
     *
     * @param Modules\Admin\Http\Requests\EmailTemplateRequest $request
     * @param \Yajra\DataTables\DataTables $datatables
     * @return \Illuminate\Http\Response JSON
     */
    public function dataList(EmailTemplateRequest $request, DataTables $datatables) {
        if ($request->ajax()) {
            \DB::statement(DB::raw('set @rownum=0'));
            $builder = EmailTemplate::query()->select(\DB::raw('@rownum := @rownum + 1 AS rank'), 'id', 'type', 'subject', 'is_active', 'updated_at');
            return $datatables->eloquent($builder)
                            ->orderColumn('id $1', 'type $1', 'subject $1', 'is_active $1', 'updated_at $1')
                            ->filter(function ($query) use ($request) {

                                if ($request->has('search')) {
                                    $keyword = $request->search['value'];
                                    $query->where('id', 'like', "%$keyword%")
                                    ->orWhere('type', 'like', "%$keyword%")
                                    ->orWhere('subject', 'like', "%$keyword%");
                                }
                            })
                            ->editColumn('id', function($model) {
                                return $model->rank;
                            })
                            ->editColumn('type', function($model) {
                                return link_to(route('emailtemplates.show', $model->id), config('constants.email_template_types')[$model->type], [], true);
                            })
                            ->editColumn('is_active', function($model) {
                                return ($model->is_active == Config::get('constants.BOOLEAN_STATUS.TRUE')) ? '<span class="label label-xs label-success"> Active </span>' : '<span class="label label-danger"> In Active </span>';
                            })
                            ->addColumn('actions', function($model) {
                                return "<a href='" . route('emailtemplates.edit', $model->id) . "' class='btn btn-sm blue tooltips' title='" . __('admin::email_template.edit_link.title') . "'><i class='fa fa-edit'></i></a>";
                            })
                            ->rawColumns([0, 1, 2, 5])
                            ->make(true);
        }
    }

}
