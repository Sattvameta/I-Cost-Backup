@extends('layouts.login')
@section('content')
 <div class="h-100">
                    <div class="h-100 no-gutters row">
                        <!--<div class="d-none d-lg-block col-lg-4">
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
                        </div>-->
                        <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-12 bg-plum-plate bg-animation" style="background-image:url('storage/settings/login-image.jpg')!important;background-size: cover;">
                            <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                             <div class="mx-auto app-login-box col-md-8">
                                        
                            <div class="modal-dialog w-100 mx-auto">
                                <div class="modal-content">
                                   
                                     <form method="POST" action="{{ route('login') }}">
                                    
                                    <div class="modal-body">
                                        
                                     <div class="h5 modal-title text-center">
                                         <div class="login-logo">
                                           <!-- <a >
                                                <img src="{{ asset('storage/settings/' . config('get.MAIN_LOGO')) }}" alt="{{ config('get.SYSTEM_APPLICATION_NAME') }}" title="{{ config('get.SYSTEM_APPLICATION_NAME') }}" style="max-width: 300px; max-height: 150px;" />
                                            </a>
											<a >
                                                <img src="{{ 'storage/settings/carbontoolkit.png' }}"  style="max-width: 300px; max-height: 150px;" />
                                            </a>-->
                                        </div>
                                            <h4 class="mt-2">
                                                <div>Welcome back,</div>
                                                <span>Sign in to start your session.</span>
                                            </h4>
                                        </div>
                                       
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                       @if (\Cookie::get('user_login_email') !== null)
                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ \Cookie::get('user_login_email') }}" placeholder="Email">
                @else
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email">
                @endif
                
                @error('email')
                <span class="invalid-feedback red" role="alert">
                    {{ $message }}
                </span>
                @enderror 
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                     @if (\Cookie::get('user_login_password') !== null)
                <input id="password" type="password" value="{{\Cookie::get('user_login_password')}}" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
                @else      
                <input id="password" type="password"  class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
                @endif
                                                    </div>
                                                    
               @error('password')
                <span class="invalid-feedback red" role="alert">
                   {{ $message }}
                </span>
                @enderror
                                                </div>
                                            </div>
                                            <div class="position-relative form-check">
                                                @if (\Cookie::get('user_login_remember'))
                            <input type="checkbox" name="remember" id="remember" checked="checked" class="form-check-input">
                            @else
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            @endif
                           <label for="remember" class="form-check-label">Keep me logged in</label>
                                            </div>
                                       
                                        <div class="divider"></div>
                                        
                                        
                                           @if (Route::has('password.request'))
                                           
                                        <h6 class="mb-0">
                                            <a href="{{ route('password.request') }}" class="text-primary">{{ __('Forgot Your Password?') }}</a>
                                        </h6>
                                        
      
                                          @endif
                                    </div>
                                    <div class="modal-footer clearfix">
                                        
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-primary btn-lg">Login to Dashboard</button>
                                        </div>
                                    </div>
                                    @csrf
                                     </form>
                                </div>
                            </div>
                            <div class="text-center text-white opacity-8 mt-3">Copyright © {{ config('get.SYSTEM_APPLICATION_NAME'). " ".date('Y') }} </div>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>

@endsection

