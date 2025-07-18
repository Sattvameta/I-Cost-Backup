@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Project</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('projects') }}"> Projects</a></li>                    
                    <li class="breadcrumb-item active">Assign Users</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">
        {{ Form::open(['route' => ['projects.store.assign.users']]) }}
        <div class="card-header">
            <h3 class="card-title">Assign Users to Project</h3>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm" href="{{ route('projects') }}" title="Back">Back</a>
            </div>
        </div>
        <div class="card-body">
            @include('layouts.flash.alert')
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="row">
                @foreach($users as $user)                 
                    <div class="col-md-2 col-sm-4">
                        <div class="form-group">
                            <div class="col-md-12  permissions-col chk">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="users{{ $user->id }}" name="users[]" value="{{ $user->id }}" {{ (!in_array($user->id, $assignedUsers)) ? '' : 'checked' }}>
                                    <label for="users{{ $user->id }}">{{ ucwords($user->meuser_name) }}</label>   
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach 
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