@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('projects') }}"> Projects</a></li>                    
                    <li class="breadcrumb-item active">Create New Version</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">
        {{ Form::open(['route' => ['projects.store.new.version']]) }}
        <div class="card-header">
            <h3 class="card-title">Create Project New Version</h3><hr>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm" href="{{ route('projects') }}" title="Back">Back</a>
            </div>
        </div>
        <div class="card-body">
            @include('layouts.flash.alert')
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-group @if($errors->has('project_id')) has-error @endif">
                        {{ Form::label('project_id', 'Project') }}<span class="asterisk">*</span>
                        {{ Form::select('project_id', $projects, old('project_id'), [
                            'class' => "form-control multiselect-dropdown",
                            'id' => "company_id",
                            'data-live-search'=>'true',
                        ]) }}
                        @if($errors->has('project_id'))
                            <span style="color:red" class="error">{{ $errors->first('project_id') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Submit', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@endsection
@push('scripts')
@endpush