<?php

namespace Modules\SettingManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SettingManager\Entities\Setting;
use Modules\SettingManager\Http\Requests\LogoRequest;
use Modules\SettingManager\Http\Requests\GeneralRequest;
use Illuminate\Support\Facades\DB;


use App\User;
use App\Category;
use Modules\CompanyManager\Http\Requests\CompanyRequest;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Modules\CompanyManager\Http\Requests\CompanyProfileRequest;
use Modules\SettingManager\Mail\QueryRegisterMailToUser;
use Modules\SettingManager\Mail\QueryRegisterMailToAdmin;
use Modules\SettingManager\Mail\QueryStatusMailToUser;

use Modules\PurchaseManager\Entities\Quotation;
class SettingManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getlogos(Request $request)
    {
        $settings = Setting::where('manager' , '=','theme_images')->get();
        //dd($request->old('setting'));
        return view('settingmanager::Admin.settings.logo-setting',compact('settings'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function themedelete($id)
    {
     $setting = Setting::find($id);
     try{
        $setting->delete();
        $responce =  ['status' => true,'message' => 'Setting has been deleted Successfully!','data' => $setting];
     }catch (\Exception $e)
     {
        $responce =  ['status' => false,'message' => $e->getMessage()];
     }
     return $responce;
}

    public function storeLogos(LogoRequest $request)
    {
        $data = $request->input('setting');
        foreach($request->input('setting') as $setting){
            $newUser = Setting::updateOrCreate(
                 [
                 'id'   => (isset($setting['id']) && !empty($setting['id'])) ? $setting['id'] : 0,
                 ],$setting);
         }

       //die("Done");

        return redirect()->route('settingtheme')->with(['success' => 'Setting saved successfully!']);
    }

    public function storeTempImage(Request $request)
    {

        $validatedData = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
        [
            'file.max'    => 'Image size exceeds 2MB'
        ]
      );

        $real_path = $request->file('file')->store('public/temp');
        $fake_path = str_replace("public/","",$real_path);
        $image_path = asset("storage/".$fake_path);
        $imageArray = explode("/", $fake_path);
        $imageName = end($imageArray);
        return json_encode(['success' => true, 'image_path' => $image_path, 'fake_path' => $fake_path, 'filename' => $imageName]);
    }

    public function getGeneralSetting(Request $request)
    {
        $allowed_columns = ['id', 'title', 'slug'];
        $sort = in_array($request->get('sort'), $allowed_columns) ? $request->get('sort') : 'created_at';
        $order = $request->get('direction') === 'asc' ? 'asc' : 'desc';
        $settings = Setting::orderBy($sort, $order)->where('manager','general')->get();
        return view('settingmanager::Admin.settings.general.general-setting',compact('settings'));
    }

    public function addGeneralSetting()
    {
        return view('settingmanager::Admin.settings.general.add');
    }

    public function storeGeneralSetting(GeneralRequest $request)
    {
        //dd($request->all());die;
        try{
            Setting::create($request->all());
        }catch (\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('setting.general')->with('success', 'General setting created successfully');
    }

    public function showGeneralSetting($id)
    {
        $settings = Setting::find($id);
        return view('settingmanager::Admin.settings.general.show',compact('settings'));
    }

    public function editGeneralSetting($id)
    {
        $settings = Setting::find($id);
        return view('settingmanager::Admin.settings.general.add',compact('settings'));
    }

    public function updateGeneralSetting(GeneralRequest $request, $id)
    {
        try{
            $setting = Setting::find($id);
            $setting->fill($request->all());
            $setting->save();
          }
          catch (\Illuminate\Database\QueryException $e) {
              return back()->withError($e->getMessage())->withInput();
          }
            return redirect()->route('setting.general')->with('success', 'Setting updated successfully!');
    }

    public function getSmtpSetting()
    {
        $smtp = Setting::where('manager','smtp')->get();
        return view('settingmanager::Admin.settings.smtp',compact('smtp'));
    }

    public function updateSmtpSetting(Request $request)
    {
        //dd($request->all());
        foreach($request->input('setting') as $setting){
        $newUser = Setting::updateOrCreate(
            [
            'slug'   => $setting['slug'],
            ],$setting);

        }

        return redirect()->route('setting.smtp')->with(['success' => 'Settings updated successfully!']);
    }

    public function updateSmtpSettingOld(Request $request)
    {
        for($i=0;$i<count($request->input('slug'));$i++)
        {
            $setting = Setting::where('slug',$request->input('slug')[$i])->first();
            if($setting != null)
            {
                $setting->config_value = $request->input('config_value')[$i];
                $setting->update();
            }
            else{
                $setting = new Setting();
                $setting->title = $request->input('title')[$i];
                $setting->slug = $request->input('slug')[$i];
                $setting->config_value = $request->input('config_value')[$i];
                $setting->field_type = $request->input('field_type')[$i];
                $setting->manager = $request->input('manager')[$i];
                $setting->save();
            }
        }

        return redirect()->route('setting.smtp')->with(['success' => 'Settings updated successfully!']);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('settingmanager::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
    
    public function helpdesk()
    {
        /*if (!auth()->user()->can('access', 'admins visible') && (!auth()->user()->isRole('Super Admin'))) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }*/
        // Categories
        $categories = DB::table('helpdesk_status')->where('status', 1)
                ->pluck('name', 'name');
        $categories->prepend('All', '');

       
        
        $settings = DB::table('tbl_query')->join('users', 'users.id', '=', 'tbl_query.userid')->select('tbl_query.*', 'users.company_name')->orderBy('tbl_query.id', 'asc')->get()->toArray();    	

       
        return view('settingmanager::Admin.settings.helpdesk',compact('settings','categories'));
    }
    
    public function helpdeskquery(Request $request)
    {
     
        
        $query_data = DB::table('tbl_query')->join('users', 'users.id', '=', 'tbl_query.company_id')->select('tbl_query.*', 'users.company_name')->where('tbl_query.id',$request->id)->first();    	

        $ret = DB::table('queryremark')->select('status','remark')->where('complaintNumber',$query_data->id)->get(); 
       //print_r($ret);exit;
        return view('settingmanager::Admin.settings.helpdesk_id',compact('query_data','ret'));
    }
    
    /**
     * Get the specified resource from storage.
     * @param DatatableRequest $request
     * @return Response
     */
    public function ajaxListAllQuery(DatatableRequest $request) {

       $query = DB::table('tbl_query')->join('users', 'users.id', '=', 'tbl_query.company_id')->select('tbl_query.*', 'users.company_name')->orderBy('id', 'asc');   
        
        
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                
                
                $query->where('tbl_query.company_id', auth()->id());
            }else{
                $comp_id = auth()->user()->company_id;
                $query_data1 = DB::table('users')->select('company_name')->where('id',$comp_id)->pluck('company_name');    	
                
                $query->where('tbl_query.company_id', $comp_id);
            }
        }
   

        $query->when(($request->has('category_filter') && ($request->category_filter != null)), function($q) use($request){
            $q->where('tbl_query.status', $request->query('category_filter'));
        });

        return datatables()->of($query)
                
                /*->addColumn('category', function ($company) {
                    return @$company->category->query_details;
                })*/

               ->addColumn('query_id', function ($company) {
                    return "ICQ".sprintf("%04d", $company->id);
                })
                ->addColumn('query_date', function ($company) {
                    return $company->query_date;
                })
               ->addColumn('company', function ($company) {
                    return $company->company_name;
                })
                ->addColumn('company_user', function ($company) {
                   $query5 = DB::table('users')->select('*')->where('id',$company->userid)->first();   
        
                    
                    return $query5->full_name;
                })
                ->addColumn('query_type', function ($company) {
                    return $company->query_type;
                })
                ->addColumn('query_status', function ($company) {
                    if($company->status == ""){
                        $company->status = "Not Processed Yet";
                    }
                    return $company->status;
                })
                ->addColumn('action', function ($company) {
                    //return $company->status;
                    $actions = "&nbsp;<a  title=\"View Query\" href=\"" . route('helpdesk.id',['id' => $company->id]) . "\" class=\"btn btn-info btn-sm\" ><center><i class=\"fas fa-eye\"></i></center></a>";
                    return $actions;
                    
                })
                
                
                ->make(true);
    }
     /**
     * @param  Request $request
     * @return Response
     */
     public function queryreview(Request $request){
         
        $query_id = $request->input('query_id');
        $status = $request->status;
        $remark = $request->input('remark');
        
        //DB::insert('insert into queryremark (complaintNumber,status,remark) values(?)',[$query_id,$status,$remark]);


        $data = array('complaintNumber'=>$query_id,"status"=>$status,"remark"=>$remark);
        $value = DB::table('queryremark')->insert($data);
        
        
         DB::table('tbl_query')->where('id', $query_id)->update(['status' => $status]);
         
         
         $query_res =  DB::table('tbl_query')->select('*')->where('id',$query_id)->first();
         $company =  DB::table('users')->select('*')->where('id',$query_res->userid)->first();
         
         \Mail::to($company->email)->send(new QueryStatusMailToUser($company->full_name,$query_res->id));
         
         
        
        return $this->helpdesk();
        
    }
    
    
    public function querycreate(Request $request){
        
        
         return view('settingmanager::Admin.settings.createquery');
        
    }
    /**
     * @param  Request $request
     * @return Response
     */
    public function savequery(Request $request){
            $query_data_user1 = DB::table('users')->where('id',auth()->user()->id)->first()->email;
        
               
                $comp_id = 0;
                if(!auth()->user()->isRole('Super Admin')){
                    if(auth()->user()->isRole('Admin')){
                        $comp_id = auth()->id();
                    }else{
                        $comp_id = auth()->user()->company_id;
                    }
                }
        
                $userid = auth()->user()->id;
                $query_type = $request->query_type;
                $query_details = $request->querydetails;
                $headshotName = "";
                if ($request->queryfile) {
                    $headshotName = time() . '.' . $request->queryfile->getClientOriginalExtension();
                    $file = $request->queryfile->move(public_path('uploads/helpdesk_files'), $headshotName);
                }
                
              
                $data = array('userid'=>$userid,'company_id'=>$comp_id,"query_type"=>$query_type,"query_details"=>$query_details,"query_file"=>$headshotName,"status"=>"Not Processed Yet");
                $value = DB::table('tbl_query')->insertGetId($data);
                
                
                $company =  DB::table('users')->select('*')->where('id',auth()->user()->id)->first();
                $query_res =  DB::table('tbl_query')->select('*')->where('id',$value)->first();
               
           
                /*try{*/
                   \Mail::to($query_data_user1)->send(new QueryRegisterMailToUser($company->full_name,$query_res->id));
                   \Mail::to('info@i-cost.co.uk')->cc(['rama@i-cost.co.uk','sharmila@icost-ai.com'])->send(new QueryRegisterMailToAdmin($company->full_name,$query_res->id));
                   
                /*}catch(\Exception $e){
                    return $e;
                }*/
             
                
            return $this->helpdesk();
            //return view('settingmanager::Admin.settings.createquery');
        
    }
    
}
