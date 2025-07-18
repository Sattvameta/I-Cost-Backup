
<div class="app-wrapper-footer">
                        <div class="app-footer">
                            <div class="app-footer__inner">
                                <div class="app-footer-left">
                                    <div class="footer-dots">
                                     
                                       
                                        <div class="dots-separator"></div>
                                        <div class="dropdown">
                                            <a class="dot-btn-wrapper dd-chart-btn-2" aria-haspopup="true"
                                                data-toggle="dropdown" aria-expanded="false">
                                                <i class="dot-btn-icon lnr-pie-chart icon-gradient bg-love-kiss"></i>
                                                <div class="badge badge-dot badge-abs badge-dot-sm badge-warning">About</div>
                                            </a>
                                            <div tabindex="-1" role="menu" aria-hidden="true"
                                                class="dropdown-menu-xl rm-pointers dropdown-menu">
                                                <div class="dropdown-menu-header">
                                                    <div class="dropdown-menu-header-inner bg-premium-dark">
                                                        <div class="menu-header-image" style="background-image: url('images/dropdown-header/abstract4.jpg');"></div>
                                                        <div class="menu-header-content text-white">
                                                            <h5 class="menu-header-title"><a href="{{ route('dashboard')}}">{{ config("get.SYSTEM_APPLICATION_NAME") }}</a></h5>
                                                            <h6 class="menu-header-subtitle"> <b>Version</b> {{ app()::VERSION }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="widget-chart">
                                                    <div class="widget-chart-content">
                                                        <div class="icon-wrapper rounded-circle">
                                                            <div class="icon-wrapper-bg opacity-9 bg-focus"></div>
                                                            <i class="lnr-users text-white"></i>
                                                        </div>
                                                       
                                                        <div class="widget-subheading pt-2"> <strong>Copyright &copy; {{date("Y")}} <a href="{{ route('dashboard')}}">{{ config("get.SYSTEM_APPLICATION_NAME") }}</a>.</strong> All rights
      reserved.</div>
                                                        
                                                    </div>
                                                    <div class="widget-chart-wrapper">
                                                        <div id="dashboard-sparkline-carousel-4-pop"></div>
                                                    </div>
                                                </div>
                                             
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

