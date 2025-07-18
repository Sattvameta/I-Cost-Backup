@php
$currentUrl = \Illuminate\Support\Facades\Request::segment(2);
@endphp
<div class="app-sidebar sidebar-shadow bg-mean-fruit sidebar-text-dark" style="overflow: auto">
                    <div class="app-header__logo">
                   @if(!auth()->user()->isRole('Super Admin'))
            @if(auth()->user()->isRole('Admin'))
                @if(\Storage::disk('public')->has(auth()->user()->company_logo))
                    <img src="{{ asset('storage/'.auth()->user()->company_logo) }}" alt="" class="brand-image img-circle elevation-3"
                    style="opacity: .8;height:40px;border-radius: 50%">
                @else
                    <img src="{{ asset('images/no-img-100x92.jpg') }}" alt="" class="brand-image img-circle elevation-3"
                    style="opacity: .8;height:40px;border-radius: 50%">
                @endif
            @else
                @if(\Storage::disk('public')->has(auth()->user()->company->company_logo))
                    <img src="{{ asset('storage/'.auth()->user()->company->company_logo) }}" alt="" class="brand-image img-circle elevation-3"
                    style="opacity: .8;height:40px;border-radius: 50%">
                @else
                    <img src="{{ asset('images/no-img-100x92.jpg') }}" alt="" class="brand-image img-circle elevation-3"
                    style="opacity: .8;height:40px;border-radius: 50%">
                @endif
            @endif
        @else
            @if(\Storage::disk('public')->has('settings/'.config('get.MAIN_LOGO')))
                <img src="{{ asset('storage/settings/' . config('get.MAIN_LOGO')) }}" alt="" class="brand-image img-circle elevation-3"
                style="opacity: .8;height:40px;border-radius: 50%">
            @else
                <img src="{{ asset('images/no-img-100x92.jpg') }}" alt="" class="brand-image img-circle elevation-3"
                style="opacity: .8;height:40px;border-radius: 50%">
            @endif
        @endif
                        <div class="header__pane ml-auto">
                            <div>
                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="app-header__mobile-menu">
                        <div>
                            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="app-header__menu">
                        <span>
                            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                <span class="btn-icon-wrapper">
                                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                                </span>
                            </button>
                        </span>
                    </div>
                    <div class="scrollbar-sidebar">
                        <div class="app-sidebar__inner">
                            <ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading">Menu</li>
                                <li class="mm-{{(Request::segment(1)=='dashboard')?'active':''}}" @php /* style="background: rgba(0, 0, 0, 0.15)" */ @endphp >
                                    <a href="{{route('dashboard')}}">
                                        <i class="metismenu-icon pe-7s-rocket"></i>
                                        Dashboard
                                        @php /* <i style="color:red" class="metismenu-state-icon pe-7s-target"></i>*/ @endphp
                                    </a>
                                    
                                </li>
                                
                                  @if (auth()->user()->can('access', 'users visible'))
                                  
                                <li class="mm-{{ request()->is('users*') ? 'active' : '' }}">
                                    <a href="{{route('users.index')}}">
                                        <i class="metismenu-icon pe-7s-users"></i>
                                         User Manager
                                      
                                    </a>
                                    
                                </li>
                                
                                @endif
                                
                                
                @if (auth()->user()->can('access', 'admins visible'))
                                  <!-- Company --> 
                                <li class="mm-{{ request()->is('companies*') ? 'active' : '' }}">
                                    <a href="{{ route('companies.index') }}">
                                        <i class="metismenu-icon pe-7s-portfolio"></i>
                                        Company Manager
                                       
                                    </a>
                                    
                                </li>
                                
                                @endif
                                
                                
                                @if (auth()->user()->can('access', 'suppliers visible'))
                                   <!-- Supplier --> 
                                   
                                   
                               <li class="mm">
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-rocket"></i>
                                        Suppliers Management
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('suppliers.index') }}" class="mm-{{ request()->is('supplierss/suppliers*') ? 'active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Supplier Management
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('quotations.index') }}" class="mm-{{ request()->is('quotations*') ? 'active' : '' }}" >
                                                <i class="metismenu-icon"></i>
                                                Request for Quotation (RFQs)
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('purchase.orders.index') }}" class="mm-{{ request()->is('purchase-orders*') ? 'active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Purchase Orders
                                            </a>
                                        </li>
                                    </ul>
                               </li>
                                
                                @endif
                                
                                
                                
                              @if (auth()->user()->can('access', 'projects visible'))
                                   <!-- Project --> 
                                   
                                   
                                <li class="mm-{{ request()->is('projects*') ? 'active':''}}">
                                    <a href="{{ route('projects')}}">
                                        <i class="metismenu-icon pe-7s-news-paper"></i>
                                       Projects
                                       
                                    </a>
                                    
                                </li>
                                
                             @endif
                             
                             
                             
                             @if ((auth()->user()->can('access', 'estimates visible')))
                                   <!-- Estimates --> 
                                   
                                   
                                <li class="mm-{{ (request()->is('estimates/projects*')) ? 'active' : '' }}">
                                    <a href="{{ route('estimates.projects') }}">
                                        <i class="metismenu-icon pe-7s-calculator"></i>
                                       Estimates
                                       
                                    </a>
                                    
                                </li>
                                
                                
                                
                                
                                  <li class="mm-{{ (request()->is('library/projects*')) ? 'active' : '' }}">
                                    <a href="{{ route('library.projects') }}">
                                        <i class="metismenu-icon pe-7s-albums"></i>
                                       Library
                                       
                                    </a>
                                    
                                </li>
                                
                             @endif
                             
							
                             <li class="mm-{{ request()->is('documentmanager*') ? 'active':''}}">
							  
                                            <a href="{{ route('documentmanager') }}">
                                             <i class="metismenu-icon pe-7s-file"></i>
                                               Central Document Manager
                                            </a>
                            </li>
                           
                             
                         @if ((auth()->user()->can('access', 'gantt visible')))
                                   <!-- Gantt --> 
                                   
                                   
                                <li class="mm-{{ (request()->is('gantt/index*')) ? 'active' : '' }}">
                                    <a href="{{ route('gantt.index') }}">
                                        <i class="metismenu-icon pe-7s-display1"></i>
                                       Gantt
                                       
                                    </a>
                                    
                                </li>
                                
                             @endif
                             
                         
                            @if ((auth()->user()->can('access', 'timesheets visible')))
                                   
                               
                                
                                
                                 <li class="mm">
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-date"></i>
                                       Timesheet Manager
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('timesheets.staff') }}" class="mm-{{ (request()->is('timesheets/staff*')) ? 'active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Staff Timesheet
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('timesheets.labour') }}" class="mm-{{ (request()->is('timesheets/labour*')) ? 'active' : '' }}" >
                                                <i class="metismenu-icon"></i>
                                                Site Operative Timesheet
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('timesheets.staff.weekly') }}" class="mm-{{ (request()->is('timesheets/staff-weekly*')) ? 'active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Weekly Staff Timesheet
                                            </a>
                                        </li>
                                        
                                         <li>
                                            <a href="{{ route('timesheets.labour.weekly') }}" class="mm-{{ (request()->is('timesheets/labour-weekly*')) ? 'active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Weekly Site Ops Timesheet
                                            </a>
                                        </li>
                                    </ul>
                               </li>
                                
                             @endif
                             
                             
                             @if ((auth()->user()->can('access', 'reports visible')))
                                   <!-- Reports --> 
                                   
                                   
                                <li class="mm-{{ (request()->is('reports*')) ? 'active' : '' }}">
                                    <a href="{{ route('reports.index') }}">
                                        <i class="metismenu-icon pe-7s-news-paper"></i>
                                       Reports
                                       
                                    </a>
                                    
                                </li>
                                
                             @endif
                                
                             
                             
   @if(auth()->user()->month != "14 DAYS FREE TRAIL" && (Auth::user()->roles->first()->slug == 'super_admin' || Auth::user()->roles->first()->slug == 'admin'))
                                  
                                   <!-- Default Permission -->                                    
                                   
                                <li class="mm-{{(Request::segment(1)=='permissions')?'active':''}}">
                                    <a href="{{ route('permissions.index') }}">
                                        <i class="metismenu-icon pe-7s-target"></i>
                                       Default Permission                                       
                                    </a>
                                    
                                </li>
                                
                             @endif
                                
                                
                                
                       @if(Auth::user()->roles->first()->slug=='super_admin')
                                   
                                <li class="mm-{{ in_array(\Request::route()->getName(), ['emailtemplates'])?'active':''}}">
                                    <a href="{{ route('emailtemplates') }}">
                                        <i class="metismenu-icon pe-7s-mail"></i>
                                       Email Templates
                                       
                                    </a>
                                    
                                </li>
                                
                             @endif
                             
                           
                                   
                                <li class="mm-{{ in_array(\Request::route()->getName(), ['helpdesk'])?'active':''}}">
                                    <a href="{{ route('helpdesk') }}">
                                        <i class="metismenu-icon pe-7s-phone"></i>
                                       ICost Help Desk
                                       
                                    </a>
                                    
                                </li>
                                
                          
                             
                             
                             
                             @if(auth()->user()->month != "14 DAYS FREE TRAIL")
                                   
                                <li class="mm-{{ in_array(\Request::route()->getName(), ['users.password']) ? 'active' : '' }}">
                                    <a href="{{ route('users.password') }}">
                                        <i class="metismenu-icon pe-7s-key"></i>
                                       Change Password
                                       
                                    </a>
                                    
                                </li>
                                
                             @endif
                             
                             
                       
                                   
                                <li class="mm-{{ in_array(\Request::route()->getName(), ['logout']) ? 'active' : '' }}">
                                    <a href="#" data-toggle="modal" data-target="#Logout">
                                        <i class="metismenu-icon pe-7s-power"></i>
                                       {{ __('Logout') }}
                                       
                                    </a>
                                    
                                </li>
                                
                             
                            </ul>
                        </div>
                    </div>
                </div>

