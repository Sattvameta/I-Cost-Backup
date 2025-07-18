@extends('layouts.login')
@section('content')
 <div class="h-100">
                    <div class="h-100 no-gutters row">
                        <div class="d-none d-lg-block col-lg-4">
                            <div class="slider-light">
                                <div class="slick-slider">
                                    <div>
                                        <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-plum-plate" tabindex="-1">
                                            <div class="slide-img-bg" style="background-image: url('{{ asset('architect/images/originals/city.jpg') }}');"></div>
                                            <div class="slider-content">
                                                <h3>Perfect Balance</h3>
                                                <p>
                                                    Icost is like a dream. Some think it's too good to be true! Extensive
                                                    collection of unified Components and Elements.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-premium-dark" tabindex="-1">
                                            <div class="slide-img-bg" style="background-image: url('{{ asset('architect/images/originals/citynights.jpg') }}');"></div>
                                            <div class="slider-content">
                                                <h3>Scalable, Modular, Consistent</h3>
                                                <p>
                                                    Easily exclude the components you don't require. Lightweight, consistent
                                                    
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-sunny-morning" tabindex="-1">
                                            <div class="slide-img-bg" style="background-image: url('{{ asset('architect/images/originals/citydark.jpg') }}');"></div>
                                            <div class="slider-content">
                                                <h3>Complex, but lightweight</h3>
                                                <p>We've included a lot of components that cover almost all use cases for any type of application.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8 bg-plum-plate bg-animation">
                            <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                             <div class="mx-auto app-login-box col-md-8">
                                        
                            <div class="modal-dialog w-100 mx-auto">
                                <div class="modal-content">
                                   
                                    
                                    
                                    <div class="modal-body">
                                        
                                     <div class="h5 modal-title text-center">
                                         <div class="login-logo">
                                            <a >
                                                <img src="{{ asset('storage/settings/' . config('get.MAIN_LOGO')) }}" alt="{{ config('get.SYSTEM_APPLICATION_NAME') }}" title="{{ config('get.SYSTEM_APPLICATION_NAME') }}" style="max-width: 300px; max-height: 150px;" />
                                            </a>
                                        </div>
                                            <h4 class="mt-2">
                                                <div>Welcome back,</div>
                                                <span>Forgot your password? Here you can easily retrieve a new password..</span>
                                            </h4>
                                        </div>
                                       
                                            <div class="form-row">
                                                <div class="col-md-12">
                                              <div class="card">
    <div class="card-body login-card-body">
        


        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif  
        
         {!! Form::open(['url' => route('password.email'), 'id'=>'forgotForm', 'method'=>'POST', 'role'=>'form' ]) !!}
        @csrf

        <div class="input-group mb-3 {{ $errors->has('email') ? ' has-error' : '' }}">
                        
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email">
                          
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
             <span class="invalid-feedback red" role="alert">
                 {{ $errors->first('email', ':message') }}
                </span>  
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block"> {{ __(' Forgot Password') }}</button>
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
                                            </div>
                                      
                                       
                                        <div class="divider"></div>
                                        
                                        
                                        
                                    </div>
                                   
                                </div>
                            </div>
                                  </div>
                            <div class="text-center text-white opacity-8 mt-3">Copyright © {{ config('get.SYSTEM_APPLICATION_NAME'). " ".date('Y') }} </div>
                      
                            </div>
                        </div>
                    </div>
                </div>

@endsection

@php /*
 <div class="card">
    <div class="card-body login-card-body">
         <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>


        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif  
        
         {!! Form::open(['url' => route('password.email'), 'id'=>'forgotForm', 'method'=>'POST', 'role'=>'form' ]) !!}
        @csrf

        <div class="input-group mb-3 {{ $errors->has('email') ? ' has-error' : '' }}">
                        
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email">
                          
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
             <span class="invalid-feedback red" role="alert">
                 {{ $errors->first('email', ':message') }}
                </span>  
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block"> {{ __(' Forgot Password') }}</button>
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

*/ @endphp