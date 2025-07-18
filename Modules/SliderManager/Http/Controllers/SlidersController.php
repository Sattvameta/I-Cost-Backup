<?php

namespace Modules\SliderManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SliderManager\Entities\Slider;
use Modules\SliderManager\Http\Requests\CreateSliderRequest as CreateSliderRequest;
use Modules\SliderManager\Http\Requests\UpdateSliderRequest as UpdateSliderRequest;
use Yajra\DataTables\Utilities\Request as DatatableRequest;
use Illuminate\Support\Facades\DB;

class SlidersController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function systemtour(Request $request) {
        
        if (!auth()->user()->can('access', 'sliders visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
       $sliders = Slider::select(['id', 'title', 'image', 'description', 'status'])->where('status', 1)->get()->toArray();

        return view('slidermanager::slider', compact('sliders'));
    }
    
    public function index(Request $request) {
        if (!auth()->user()->can('access', 'sliders visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $allowed_columns = ['id', 'title', 'status', 'created_at'];
        $sort = in_array($request->get('sort'), $allowed_columns) ? $request->get('sort') : 'created_at';
        $order = $request->get('direction') === 'asc' ? 'asc' : 'desc';
        $sliders = Slider::status(request('status'))->filter(request('keyword'))->orderBy($sort, $order)->paginate(config('get.ADMIN_PAGE_LIMIT'));

        return view('slidermanager::index', compact('sliders'));
    }

    public function ajaxListAllSliders(DatatableRequest $request) {


        $sliders = Slider::select(['id', 'title', 'image', 'description', 'status']);

        if ($request->status != '') {
            $sliders = $sliders->where('status', $request->status);
        }


        return datatables()->of($sliders)
                        ->addColumn('status', function ($slider) {
                                    $actions = "";
                                    $status = $slider->status;
                                    $actions .= ($status == 1) ? "Active" : "Inactive";
                                    return $actions;
                                })
                        ->addColumn('action', function ($slider) {
                                    $actions = "";

                                    $actions .= "<a href=\"" . route('sliders.view', ['id' => $slider->id]) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-folder\">
                              </i> View</a>";
                                    if (auth()->user()->can('access', 'sliders add')) {
                                        $actions .= "&nbsp;<a href=\"" . route('sliders.edit', ['id' => $slider->id]) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-pencil-alt\"></i> Edit</a>";
                                        $actions .= "&nbsp;<a title=\"Delete Slider\" onclick=\"return confirm('Are you sure want to remove the Slider?')\" href=\"" . route('sliders.delete', ['id' => $slider->id]) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"></i> Delete</a>";
                                    }

                                    return $actions;
                                })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        if (!auth()->user()->can('access', 'sliders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        return view('slidermanager::createOrUpdate');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateSliderRequest $request) {
        $validated = $request->validated();
        $insertionData = $validated;


        try {

            $slider = Slider::create($insertionData);



            if ($slider->save()) {


                if ($request->image) {
                    $fileName = $slider->id . '_' . time() . '.' . $request->image->getClientOriginalExtension();
                    $file = $request->image->move(public_path('uploads/sliders'), $fileName);

                    if ($slider->image && \File::exists(public_path('uploads/sliders/' . $slider->image))) { // unlink or remove previous image from folder
                        unlink(public_path('uploads/sliders/' . $slider->image));
                    }



                    $insertionData['image'] = $fileName;
                    $updated = $slider->update($insertionData);
                }
            }
            // Slider::create($request->all());			
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('sliders')->with('success', 'Slider has been saved Successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id) {
        if (!auth()->user()->can('access', 'sliders visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $slider = Slider::find($id);
        return view('slidermanager::show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id) {
        if (!auth()->user()->can('access', 'sliders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $slider = Slider::find($id);
        return view('slidermanager::createOrUpdate', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateSliderRequest $request, $id) {
        $slider = Slider::findOrFail($id);

        $validated = $request->validated();
        $insertionData = $validated;
        try {

            if ($request->image) {
                $fileName = $slider->id . '_' . time() . '.' . $request->image->getClientOriginalExtension();
                $file = $request->image->move(public_path('uploads/sliders'), $fileName);

                if ($slider->image && \File::exists(public_path('uploads/sliders/' . $slider->image))) { // unlink or remove previous image from folder
                    unlink(public_path('uploads/sliders/' . $slider->image));
                }



                $insertionData['image'] = $fileName;
            }

            $updated = $slider->update($insertionData);
        } catch (Exception $e) {
            // Todo: Exception handleing.
        }
        return redirect()->route('sliders')->withSuccess('Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id) {
        if (!auth()->user()->can('access', 'sliders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        DB::beginTransaction();
        $slider = Slider::where('id', '=', $id)->first();
        // dd($category->products->count());
        try {
            $slider->delete();
            DB::commit();
            $responce = ['status' => true, 'message' => 'This slider has been deleted Successfully!', 'data' => $slider];
            return redirect()->route('sliders')->with('success', $responce['message']);
        } catch (\Exception $e) {
            DB::rollBack();
            $responce = ['status' => false, 'message' => $e->getMessage()];
            return redirect()->route('sliders')->with('error', $responce['message']);
        }
    }

}
