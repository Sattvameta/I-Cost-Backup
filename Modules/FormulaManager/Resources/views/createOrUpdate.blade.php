@extends('layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Formula</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('formulas') }}"> Formulas</a></li>                    
                    <li class="breadcrumb-item active">{{ !empty($formula) ? 'Update Formula' : 'Create Formula'}}</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ !empty($formula) ? 'Update Formula' : 'Create Formula'}}</h3>
            <div class="card-tools">
                <div class="box-tools pull-right">
                <a href="{{route('formulas')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('layouts.flash.alert')
            
            @if(isset($formula))
                {{ Form::model($formula, ['route' => ['formulas.update', $formula->id], 'method' => 'patch']) }}
                <input type="hidden" name="id" value="{{ $formula->id }}">
            @else
                {{ Form::open(['route' => 'formulas.store']) }}
            @endif
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group @if($errors->has('project_id')) has-error @endif">
                            {{ Form::label('project_id', 'Project') }}<span class="asterisk">*</span>
                            {{ Form::select('project_id', $projects, @$formula->project_id, [
                                'class' => "form-control select2-input",
                                'id' => "project_id",
                                'data-live-search'=>'true',
                            ]) }}
                            @if($errors->has('project_id'))
                                <span class="invalid-feedback">{{ $errors->first('project_id') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group @if($errors->has('keyword')) has-error @endif">
                            {{ Form::label('keyword', 'Keyword') }}<span class="asterisk">*</span>
                            {{ Form::text('keyword', @$formula->keyword, [
                                'class' => "form-control",
                                'id' => "keyword",
                            ]) }}
                            @if($errors->has('keyword'))
                                <span class="invalid-feedback">{{ $errors->first('keyword') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group @if($errors->has('description')) has-error @endif">
                            {{ Form::label('description', 'Description') }}<span class="asterisk">*</span>
                            {{ Form::text('description', @$formula->description, [
                                'class' => "form-control",
                                'id' => "description",
                            ]) }}
                            @if($errors->has('description'))
                                <span class="invalid-feedback">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group @if($errors->has('formula')) has-error @endif">
                            {{ Form::label('formula', 'Formula') }}<span class="asterisk">*</span>
                            {{ Form::text('formula', @$formula->formula, [
                                'class' => "form-control",
                                'id' => "formula",
                            ]) }}
                            @if($errors->has('formula'))
                                <span class="invalid-feedback">{{ $errors->first('formula') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group @if($errors->has('value')) has-error @endif">
                            {{ Form::label('value', 'Value') }}<span class="asterisk">*</span>
                            {{ Form::text('value', @$formula->value, [
                                'class' => "form-control",
                                'id' => "value",
                            ]) }}
                            @if($errors->has('value'))
                                <span class="invalid-feedback">{{ $errors->first('value') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::submit('Submit', [ 'class' => "btn btn-primary btn-flat" ]) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
</section>
@endsection