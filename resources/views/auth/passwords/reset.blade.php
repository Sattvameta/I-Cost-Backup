@extends('layouts.login')

@section('content')

<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('Reset Password') }}</p>
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif 
        {!! Form::open(['url' => route('password.update'), 'id'=>'form', 'method'=>'POST', 'role'=>'form' ]) !!}
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="input-group mb-3 {{ $errors->has('email') ? ' has-error' : '' }}">

            <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            <span class="error invalid-feedback"> {{ $errors->first('email', ':message') }} </span>
        </div>
        <div class="input-group mb-3 {{ $errors->has('password') ? ' has-error' : '' }}">

            <input id="password" type="password"  class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">


            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            <span class="error invalid-feedback"> {{ $errors->first('password', ':message') }} </span>
        </div>
        <div class="input-group mb-3 {{ $errors->has('password_confirmation') ? ' has-error' : '' }}"">
            <input id="password_confirmation" type="password"  class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            <span class="error invalid-feedback"> {{ $errors->first('password_confirmation', ':message') }} </span>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block"> {{ __('Reset Password') }}</button>
            </div>
            <!-- /.col -->
        </div>
        {!! Form::close() !!}
        <p class="mt-3 mb-1">
            <a class="" href="{{ route('login') }}">
                {{ __('Login?') }}</a>
        </p>

    </div>
    <!-- /.login-card-body -->
</div>
@endsection
