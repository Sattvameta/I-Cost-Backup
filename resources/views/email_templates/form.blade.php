@extends('layouts.app')
@push('styles')
<link href="{{ asset('/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" />
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('sliders')}}"> Email Templates</a></li>
                    <li class="breadcrumb-item active">{{ (@$emailtemplate) ? 'Update' : 'Create'}}</li>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{(@$emailtemplate) ? 'Update' : 'Create'}} Email Template</h3><hr>
                        <div class="card-tools">
                            <div class="box-tools pull-right">
                                <a href="{{route('emailtemplates')}}" class="btn btn-default" style="color:black"><i class="fa fa-fw fa-chevron-circle-left"></i> Back</a>

                            </div>
                        </div>
                    </div>


                    @if(@$emailtemplate)
                    {!! Form::model($emailtemplate, ['url'=>route('emailtemplates.update', @$emailtemplate->id), 'id'=>'form_emailtemplate', 'method'=>'POST', 'role'=>'form']) !!}
                    @else
                    {!! Form::open(['url'=>route('emailtemplates.store'), 'id'=>'form_emailtemplate', 'method'=>'POST', 'role'=>'form']) !!}
                    @endif
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group @if($errors->has('type')) has-error @endif">
                                    {{Form::label('type', 'Type')}}<span class="asterisk">*</span>
                                    {{Form::select('type', config('constants.email_template_types'),@$emailtemplate->type, [
                                    'class' => "form-control",
                                    'id' => "type",
                                    'data-live-search'=>'true',
                                    ])}}
                                    @if($errors->has('type'))
                                    <span class="invalid-feedback">{{$errors->first('type')}}</span>
                                    @endif
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group @if($errors->has('subject')) has-error @endif">
                                    {{Form::label('subject', 'Subject')}}<span class="asterisk">*</span>
                                    {{Form::text('subject',@$emailtemplate->subject, [
                                    'class' => "form-control",
                                    'id' => "subject",
                                    ])}}
                                    @if($errors->has('subject'))
                                    <span class="invalid-feedback">{{$errors->first('subject')}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{Form::label('status', 'Status')}}
                                    {!!Form::select('status', ['1'=>'Active','0'=>'InActive'], @$emailtemplate->status, ['class' => 'form-control'])!!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group {{ $errors->has('template') ? 'has-error' : '' }}">
                                    {!! Form::label('template', 'Template:', ['class' => 'control-label']) !!}
                                    <span class="asterisk">*</span>
                                    {{ Form::textarea('template', @$emailtemplate->template, ['class' => 'form-control textarea', "style"=>"width: 100%; height: 300px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;",'placeholder' => 'Description']) }}

                                    <span class="help-block"> {{ $errors->first('template', ':message') }} </span>
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
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.textarea').summernote()

        $('#form_emailtemplate').validate({
            rules: {
                type: {
                    required: true,
                },
                subject: {
                    required: true,

                },
                status: {
                    required: true
                },
                template: {
                    required: true
                },
            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        })
    });
</script>


@endpush