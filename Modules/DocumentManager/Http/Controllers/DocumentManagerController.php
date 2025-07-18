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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
//require_once "../vendor/autoload.php";

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

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
    public function create(Request $request,$id)
    {
		$doc = DB::table('v_purchase_note_certificate')->select('*')->where('rowcount',$id)->get();
        

        return view('documentmanager::create', compact('doc'));
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
 public function fileUpload(Request $request)
 
    {
		/*$img_name=$request->input('img_name');
		$img_url=$request->input('img_url');
		//$docs='http://localhost/uat-new/uat/public/uploads/purchase_certificate/18511403321608122815.jpg';
       
           //$file = $request->file('image');
           $name =$img_name;
           $filePath = 'images/' . $name;
           Storage::disk('s3')->put($filePath, file_get_contents($img_url));
           DB::insert('insert into blockchain_status (doc_title) values(?)',[$name]);

        return redirect()->route('documentmanager')->with('success', 'File has been updated Successfully');*/
		$img_name=$request->input('img_name');
		$img_url=$request->input('img_url');
		//$docs='http://localhost/uat-new/uat/public/uploads/purchase_certificate/18511403321608122815.jpg';
       
           //$file = $request->file('image');
           $name =$img_name;
          $filePath = 'images/' . $name;
       
         //Storage::disk('s3')->put($filePath, file_get_contents($img_url));
		 $web3 = new Web3('http://127.0.0.1:8545');
		 
         $privatekey="e1cc5f47b1e0c3420790d67b0287bfa14920ec68007bc0a49e9f9659fc3cb68c";
		 $account = $web3->eth()->accounts()->wallet()->add($privatekey);
         //$hashedPassword = $web3->eth()->accounts();
		     //$hashedPassword=Hash::make($name);
           DB::insert('insert into blockchain_status (doc_title,hash_key) values(?,?)',[$name,$hashedPassword]);

        return redirect()->route('documentmanager')->with('success',$hashedPassword);
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
					   $invoices_po = DB::table('blockchain_status')->select('*')->where('doc_title',$doc->cer_or_delnote)->orderBy('id')->get();
					  $inv_id="";
                        foreach($invoices_po as $inv){
                            $inv_id.=$inv->status;
                           if($inv_id =1){
                           return "<span style='color:red'>Blockchained</span>";
                           }else{  
                           return "Blockchain";						   
                        }
                        }
					   
					})
					
					->addColumn('action', function ($doc) {
						 $invoices_po = DB::table('blockchain_status')->select('*')->where('doc_title',$doc->cer_or_delnote)->orderBy('id')->get();
								$actions = "";
								$actions .= "<a href=\" ../".$doc->storage."/".$doc->cer_or_delnote."\" class=\"btn btn-primary btn-sm\" target=\"_blanck\"><i class=\"fas fa-eye\"></i></a>";
								$actions .= "&nbsp<a href=\"" . route('documentmanager.view', ['rowcount' => $doc->rowcount]) . "\" class=\"btn btn-success btn-sm\"><i class=\"fas fa-print\"></i></a>";
								 $inv_id="";
                               foreach($invoices_po as $inv){
                                $inv_id.=$inv->status;
								$actions .= "";
								return $actions;
							   }
								$actions .= "&nbsp<a href=\"" . route('documentmanager.create', ['rowcount' => $doc->rowcount]) . "\" class=\"btn btn-danger btn-sm\"><i class=\"fas fa-link\"></i></a>";
								return $actions;
							})->rawColumns(['check'],['action'])
					->make(true);

		}

}
