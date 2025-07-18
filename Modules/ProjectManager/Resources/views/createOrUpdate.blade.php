@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
           
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('projects') }}"> Projects</a></li>                    
                    <li class="breadcrumb-item active">{{ !empty($project) ? 'Update Project' : 'Create Project'}}</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">
        @if(isset($project))
            {{ Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'patch']) }}
            <input type="hidden" name="id" value="{{ $project->id }}">
        @else
            {{ Form::open(['route' => 'projects.store']) }}
        @endif
        <div class="card-header">
            <h3 class="card-title">{{ !empty($project) ? 'Update Project' : 'Create Project'}}</h3><hr>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm" href="{{ route('projects') }}" title="Back">Back</a>
            </div>
        </div>
        <div class="card-body">
            @include('layouts.flash.alert')
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                        {{ Form::label('company_id', 'Company') }}<span class="asterisk">*</span>
                        {{ Form::select('company_id', $companies, @$project->company_id, [
                            'class' => "form-control multiselect-dropdown",
                            'id' => "company_id",
                            'data-live-search'=>'true',
                        ]) }}
                        @if($errors->has('company_id'))
                            <span class="invalid-feedback">{{ $errors->first('company_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group @if($errors->has('client')) has-error @endif">
                        {{ Form::label('client', 'Client') }}
                        {{ Form::text('client', @$project->client, [
                            'class' => "form-control",
                            'id' => "client",
                        ]) }}
                        @if($errors->has('client'))
                            <span class="invalid-feedback">{{ $errors->first('client') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group @if($errors->has('client_contacts')) has-error @endif">
                        {{ Form::label('client_contacts', 'Client Contact') }}
                        {{ Form::text('client_contacts',@$project->client_contacts, [
                            'class' => "form-control",
                            'id' => "client_contacts",
                        ]) }}
                        @if($errors->has('client_contacts'))
                            <span class="invalid-feedback">{{ $errors->first('client_contacts') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-group @if($errors->has('unique_reference_no')) has-error @endif">
                        {{ Form::label('unique_reference_no', 'Unique Reference No') }}<span class="asterisk">*</span>
                        {{ Form::text('unique_reference_no',@$project->unique_reference_no, [
                            'class' => "form-control",
                            'id' => "unique_reference_no",
                        ]) }}
                        @if($errors->has('unique_reference_no'))
                            <span class="invalid-feedback">{{$errors->first('unique_reference_no')}}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group @if($errors->has('project_title')) has-error @endif">
                        {{ Form::label('project_title', 'Project Title') }}<span class="asterisk">*</span>
                        {{ Form::text('project_title',@$project->project_title, [
                            'class' => "form-control",
                            'id' => "project_title",
                        ]) }}
                        @if($errors->has('project_title'))
                            <span style="color:red" class="error">{{ $errors->first('project_title') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="form-group @if($errors->has('project_address')) has-error @endif">
                        {{ Form::label('project_address', 'Project Address') }}
                        {{ Form::text('project_address',@$project->project_address, [
                            'class' => "form-control",
                            'id' => "project_address",
                        ]) }}
                        @if($errors->has('project_address'))
                            <span  style="color:red" class="error">{{ $errors->first('project_address') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <div class="form-group @if($errors->has('location')) has-error @endif">
                        {{ Form::label('location', 'Location')}}
                        {{ Form::text('location',@$project->location, [
                            'class' => "form-control",
                            'id' => "location",
                        ]) }}
                        @if($errors->has('location'))
                            <span style="color:red" class="error">{{ $errors->first('location') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="form-group @if($errors->has('sector')) has-error @endif">
                        {{ Form::label('sector', 'Sector')}}
                        {{ Form::text('sector',@$project->current_value_of_project, [
                            'class' => "form-control",
                            'id' => "sector",
                        ]) }}
                        @if($errors->has('sector'))
                            <span style="color:red" class="error">{{ $errors->first('sector') }}</span>
                        @endif
                    </div> 
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="form-group @if($errors->has('region')) has-error @endif">
                        {{ Form::label('region', 'Region') }}
                        {{ Form::select('region', config('constants.REGIONS'),@$project->region, [
                            'class' => "form-control",
                            'id' => "region",
                            'data-live-search'=>'true',
                        ]) }}
                        @if($errors->has('region'))
                            <span style="color:red" class="error">{{$errors->first('region')}}</span>
                        @endif
                    </div>
                </div>
            </div>
			 @if (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group @if($errors->has('type_of_contract')) has-error @endif">
                        {{ Form::label('type_of_contract', 'Type Of Contract') }}
                        {{ Form::text('type_of_contract', old('type_of_contract') ? old('type_of_contract') : @$project->type_of_contract, [
                            'class' => "form-control",
                            'id' => "type_of_contract"
                        ]) }}
                        @if($errors->has('type_of_contract'))
                            <span style="color:red" class="error">{{ $errors->first('type_of_contract') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group @if($errors->has('shifts')) has-error @endif">
                        {{ Form::label('shifts', 'Shifts') }}
                        {{ Form::select('shifts', config('constants.SHIFTS'),@$project->shifts, [
                            'class' => "form-control",
                            'id' => "shifts",
                            'data-live-search'=>'true',
                        ]) }}
                        @if($errors->has('shifts'))
                            <span style="color:red" class="error">{{ $errors->first('shifts') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12"> 
                    <div class="form-group @if($errors->has('project_manager')) has-error @endif">
                        {{ Form::label('project_manager', 'Project Manager')}}
                        {{ Form::text('project_manager', @$project->project_manager, [
                            'class' => "form-control",
                            'id' => "project_manager",
                        ]) }}
                        @if($errors->has('project_manager'))
                            <span style="color:red" class="error">{{ $errors->first('project_manager') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-sm-12"> 
                    <div class="form-group @if($errors->has('site_supervisor')) has-error @endif">
                        {{ Form::label('site_supervisor', 'Site Supervisor')}}
                        {{ Form::text('site_supervisor', @$project->site_supervisor, [
                            'class' => "form-control",
                            'id' => "site_supervisor",
                        ]) }}
                        @if($errors->has('site_supervisor'))
                            <span style="color:red" class="error">{{ $errors->first('site_supervisor') }}</span>
                        @endif
                    </div>
                </div>
            </div>
			@endif
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group @if($errors->has('current_start_date')) has-error @endif">
                        {{ Form::label('current_start_date', 'Current Start Date') }}<span class="asterisk">*</span>
                        {{ Form::date('current_start_date',@$project->current_start_date, [
                            'class' => "form-control",
                            'id' => "current_start_date",
                            'data-live-search'=>'true',
                        ]) }}
                        @if($errors->has('current_start_date'))
                            <span style="color:red" class="error">{{ $errors->first('current_start_date') }}</span>
                        @endif
                    </div>  
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group @if($errors->has('current_completion_date')) has-error @endif">
                        {{ Form::label('current_completion_date', 'Current Completion Date') }}
                        {{ Form::date('current_completion_date',@$project->current_completion_date, [
                            'class' => "form-control",
                            'id' => "current_completion_date",
                            'data-live-search'=>'true',
                        ]) }}
                        @if($errors->has('current_completion_date'))
                            <span style="color:red" class="error">{{ $errors->first('current_completion_date') }}</span>
                        @endif
                    </div> 
                </div>
            </div>
            <div class="row">
			@if (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))
                <div class="col-md-6 col-sm-12">
                    <div class="form-group @if($errors->has('current_value_of_project')) has-error @endif">
                        {{ Form::label('current_value_of_project', 'Current Value of Project') }}
                        {{ Form::text('current_value_of_project',@$project->current_value_of_project, [
                            'class' => "form-control",
                            'id' => "current_value_of_project",
                        ]) }}
                        @if($errors->has('current_value_of_project'))
                            <span style="color:red" class="error">{{ $errors->first('current_value_of_project') }}</span>
                        @endif
                    </div>
                </div>
				@endif
                <div class="col-md-6 col-sm-12">
                    <div class="form-group @if($errors->has('base_margin')) has-error @endif">
                        {{ Form::label('base_margin', 'Base Margin') }}<span class="asterisk">*</span>
                        {{ Form::text('base_margin',@$project->base_margin, [
                            'class' => "form-control",
                            'id' => "base_margin",
                            "min"=>"0" ,"step"=>"1"
                        ]) }}
                        @if($errors->has('base_margin'))
                            <span style="color:red" class="error">{{ $errors->first('base_margin') }}</span>
                        @endif
                    </div>
                </div>
            </div>
			@if (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))
            <div class="row">
			
                <div class="col-md-6 col-sm-12">        
                    <div class="form-group @if($errors->has('change_management')) has-error @endif">
                        {{ Form::label('change_management', 'Change Management') }}
                        {{ Form::text('change_management',@$project->change_management, [
                            'class' => "form-control",
                            'id' => "change_management",
                        ]) }}
                        @if($errors->has('change_management'))
                            <span style="color:red" class="error">{{$errors->first('change_management')}}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group @if($errors->has('adjusted_contract_value')) has-error @endif">
                        {{ Form::label('adjusted_contract_value', 'Adjusted Contract Value') }}
                        {{ Form::text('adjusted_contract_value',@$project->adjusted_contract_value, [
                            'class' => "form-control",
                            'id' => "adjusted_contract_value",
                            "min"=>"0" ,"step"=>"1"
                        ]) }}
                        @if($errors->has('adjusted_contract_value'))
                            <span style="color:red" class="error">{{$errors->first('adjusted_contract_value')}}</span>
                        @endif
                    </div>
                </div>
            </div>
			 @endif
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group @if($errors->has('labour_value')) has-error @endif">
                        {{ Form::label('labour_value', 'Labour Value') }}<span class="asterisk">*</span>
                        {{ Form::text('labour_value',@$project->labour_value, [
                            'class' => "form-control",
                            'id' => "labour_value",
                            "min"=>"0" ,"step"=>"1"
                        ]) }}
                        @if($errors->has('labour_value'))
                            <span style="color:red" class="error">{{$errors->first('labour_value')}}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group @if($errors->has('tender_status')) has-error @endif">
                        {{ Form::label('tender_status', 'Tender Status') }}
                        {{ Form::select('tender_status', config('constants.TENDER_STATUS'),@$project->tender_status, [
                            'class' => "form-control",
                            'id' => "tender_status",
                            'data-live-search'=>'true',
                        ]) }}
                        @if($errors->has('tender_status'))
                            <span style="color:red" class="error">{{$errors->first('tender_status')}}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Save project', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@endsection
@push('scripts')
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script type="text/javascript">
    $('document').ready(function() {
        $('.textarea').summernote()
    })
</script>
@endpush