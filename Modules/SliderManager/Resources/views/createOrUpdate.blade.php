@extends('layouts.app')
@push('styles')
    <link href="{{ asset('/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" />
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Sliders</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('sliders')}}"> Sliders</a></li>                    
                    <li class="breadcrumb-item active">{{!empty($slider) ? 'Update Slider' : 'Create Slider'}}</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
     

        <div class="row">
          
            <!-- left column -->
            <div class="col-md-12">
  @include('layouts.flash.alert')
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{!empty($slider) ? 'Update Slider' : 'Create Slider'}}</h3>
                    </div>


                @if(isset($slider))
                {{ Form::model($slider, ['route' => ['sliders.update', $slider->id], 'method' => 'patch','enctype'=>"multipart/form-data"]) }}
                @else
                {{ Form::open(['route' => 'sliders.store','enctype'=>"multipart/form-data"]) }}
                @endif
               <div class="card-body">

                        <div class="row">
                          <div class="col-sm-12">
                                <div class="form-group @if($errors->has('title')) has-error @endif">
                                    {{Form::label('title', 'Title')}}<span class="asterisk">*</span>
                                    {{Form::text('title',(isset($slider))?$slider->title:'', [
                                    'class' => "form-control",
                                    'id' => "title",
                                    ])}}
                                    @if($errors->has('title'))
                                    <span class="invalid-feedback">{{$errors->first('title')}}</span>
                                    @endif
                                </div>
                            </div>

                       
                    </div>
                
                   <div class="row">
                            <div class="col-sm-12">
                        <div class="form-group @if($errors->has('description')) has-error @endif">
                                <label for="description">Description</label>
                                {{ Form::textarea('description', (isset($slider))?$slider->description:'', ['class' => 'form-control', "style"=>"width: 100%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;",'placeholder' => 'Description']) }}
                                @if($errors->has('description'))
                                <span class="help-block">{{ $errors->first('description') }}</span>
                                @endif
                            </div> 
                                  </div> 
                        </div> 
                    <div class="row">
                         <div class="col-sm-6">
                                <div class="form-group">
                                    {{Form::label('status', 'Status')}}
                                    {!!Form::select('status', ['1'=>'Active','0'=>'InActive'],  (isset($slider))?$slider->status:'', ['class' => 'form-control'])!!}
                                </div>
                            </div>
                        <div class="col-md-6">
                               <div class="form-group  @if($errors->has('image')) has-error @endif">
                                 
                                     <label> Image</label>

                                    <input data-preview="#image" name="image" type="file" id="photoInput" class="form-control">
      <?php if (isset($slider->image) && file_exists( public_path() . '/uploads/sliders/' . $slider->image) && !empty($slider->image)) {
        $image_url= '/uploads/sliders/' . $slider->image;
    } else {
        $image_url ='/images/placeholder.png';
    }?>
                                    <img class="preview img-thumbnail" style="width: 150px;" src="{{URL::asset($image_url)}}">
                                 
                                  
                                </div>
                        </div>


                    </div> 
                 <div class="col-sm-12">
                            <div class="form-group">
                                {{Form::submit('Submit', [ 'class' => "btn btn-primary btn-flat" ])}}
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div></div>
</div>
</section>
@endsection
@push('scripts')
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
  <script type="text/javascript">

    $('document').ready(function(){

  //  $('.textarea').summernote()
  })
</script>
@endpush