<?php

namespace Modules\CompanyManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Module;
use App\SubModule;
use App\Feature;
use App\CompanyFeature;
use App\User;

class FeaturesController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($company_id)
    {
        try {
            $user_role = auth()->user()->roles()->first()->slug;
            if ($user_role != 'super_admin' && $user_role != 'admin') {
                return redirect('dashboard')->withError('Not authroized to access!');
            }

            $company = User::with(['roles'])->where('users.id', $company_id)->whereHas('roles', function ($query) {
                $query->where('slug', '=', 'admin');
            })->get()->first();

            if (!empty($company)) {

                $title = "Manage Features";
                $features = Feature::orderBy('is_default', 'DESC')->orderBy('module', 'ASC')->where('status', 1)->where('is_default', 0)->get();
                $default_features = Feature::orderBy('is_default', 'DESC')->orderBy('module', 'ASC')->where('status', 1)->where('is_default', 1)->get();
                $featureIds = CompanyFeature::where('company_id', $company_id)->pluck('feature_id')->toArray();

                return view('companymanager::features.index', compact('default_features', 'features', 'featureIds', 'company'));
            } else {
                return redirect()->back()->with('message', 'Features can be enabled for the company admin only.');
            }
        } catch (Exception $ex) {
        }
    }
    /*
     *
     * @param Request $request
     */
    public function updateFeatures(Request $request)
    {
        
        if($request->feature_id){
            if(in_array('3',$request->feature_id)){
           
                $request->feature_id += ['one' => '6'];
             
            
            }
        }
    
        $user_role = auth()->user()->roles()->first()->slug;
        if ($user_role != 'super_admin') {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        if($request->has('feature_id')){
            $allFeatures = (array)$request->feature_id;
            if(count($allFeatures)){
                CompanyFeature::where('company_id', '=', $request->company_id)->delete();
                $i = 0;
                foreach ($allFeatures as $feature) {
                    $data[$i]['feature_id'] = $feature;
                    $data[$i]['company_id'] = $request->company_id;
                    $i++;
                }
                CompanyFeature::insert($data);
                return redirect()->back()->with('message', 'Company features updated successfully.');
            }else{
                return redirect()->back()->with('message', 'Please select some features to update company features.');
            }   
        }else{
            return redirect()->back()->with('message', 'Please select some features to update company features.');
        } 
    }
}
