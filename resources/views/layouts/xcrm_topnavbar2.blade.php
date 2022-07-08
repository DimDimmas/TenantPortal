<div class="iq-top-navbar">
    <div class="top-menu">
       <div class="container">
          <div class="row">
             <div class="col-sm-12">
             <div class="iq-navbar-custom-menu d-flex align-items-center justify-content-between">
               <div class="iq-sidebar-logo">
                   <div class="top-logo">
                    <a href="{{ route('news') }} " class="logo">
                        <img src="{{ asset('moa/images/TenantPortal.png')}}" class="img-fluid" alt="">
                        </a>
                   </div>
                </div>

                <div class="iq-menu-horizontal">
                  <nav class="iq-sidebar-menu">
                     <ul id="iq-sidebar-toggle" class="iq-menu d-flex">
                        {{-- <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                           <a href="{{ route('dashboard') }}" class="iq-waves-effect" aria-expanded="false"><i class="ri-home-line"></i><span>Dashboard</span></a>
                        </li> --}}
                        <li class="{{ request()->is('news') ? 'active' : '' }}">
                           <a href="{{ route('news') }}" class="iq-waves-effect" aria-expanded="false"><i class="ri-article-line"></i><span>News</span></a>
                        </li>
                        <li class="{{ request()->is('preventive') ? 'active' : '' }}">
                           <a href="#menu-preventive" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-group-line"></i><span>Preventive</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                           <ul id="menu-preventive" class="iq-submenu collapse show" data-parent="#iq-sidebar-toggle">
                              <li class="{{ request()->is('corrective/request-ticket') ? 'active' : '' }}"><a href="">-</a></li>
                              <li class="{{ request()->is('corrective/history-ticket') ? 'active' : '' }}"><a href="">-</a></li>
                           </ul>
                        </li>
                        <li class="{{ request()->is('corrective/request-ticket') ? 'active' : '' }} {{ request()->is('corrective/history-ticket') ? 'active' : '' }}">
                           <a href="#menu-design" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-group-line"></i><span>Corrective</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                           <ul id="menu-design" class="iq-submenu collapse show" data-parent="#iq-sidebar-toggle">
                              <li class="{{ request()->is('corrective/request-ticket') ? 'active' : '' }}"><a href="{{ route('request_ticket') }}">Request Ticket</a></li>
                              <li class="{{ request()->is('corrective/history-ticket') ? 'active' : '' }}"><a href="{{ route('history_ticket') }}">History</a></li>
                           </ul>
                        </li>
                        {{-- <li aria-expanded="true"> --}}
                        <li class="{{ request()->is('meter/list-confirmation') ? 'active' : '' }} {{ request()->is('meter/history') ? 'active' : '' }}" aria-expanded="true">
                           <a href="#menu-level" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-keyboard-box-line"></i><span>Meter</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                           <ul id="menu-level" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                              <li class="{{ request()->is('meter/list-confirmation') ? 'active' : '' }}"><a href="{{ route('list_meter_confirm') }}">Meter Confirmation</a></li>
                              <li class="{{ request()->is('meter/history') ? 'active' : '' }}"><a href="{{ route('list_meter_history') }}">History</a></li>
                           </ul>
                        </li>
                        <li class="{{ request()->is('overtime/request-ticket') ? 'active' : '' }} {{ request()->is('overtime/history-ticket') ? 'active' : '' }}">
                           <a href="#menu-design" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="fa fa-building" aria-hidden="true"></i><span>Overtime</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                           <ul id="menu-design" class="iq-submenu collapse show" data-parent="#iq-sidebar-toggle">
                              <li class="{{ request()->is('overtime/request-ticket') ? 'active' : '' }}"><a href="{{ route('request_overtime') }}">Request Overtime</a></li>
                              <li class="{{ request()->is('overtime/history-ticket') ? 'active' : '' }}"><a href="{{ route('history_overtime') }}">History</a></li>
                           </ul>
                        </li>
                       <!-- <li>
                           <a href="#ui-elements" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-pencil-ruler-line"></i><span>Overtime</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                           <ul id="ui-elements" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="height: 300px; overflow-y: scroll;">
                              <li><a href="ui-colors.html">Request</a></li>
                              <li><a href="ui-typography.html">Summarry</a></li>
                              <li><a href="ui-typography.html">History</a></li>
                           </ul>
                        </li> -->
                        
                     </ul>
                  </nav>
               </div>

               <nav class="navbar navbar-expand-lg navbar-light p-0">
                  <ul class="navbar-list">
                     <li class="">
                        <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center"><span class="ripple rippleEffect" style="width: 129.167px; height: 129.167px; top: -9.5835px; left: 18.5831px;"></span>
                           <i class="ri-account-circle-fill"></i>
                           <div class="caption">
                              <h6 class="mb-0 line-height">{{ Auth::user()->tenant_person }}</h6>
                              <span style="font-size:0.7em">{{ date('l, jS F, Y') }}</span>
                           </div>
                        </a>
                        <div class="iq-sub-dropdown iq-user-dropdown">
                           <div class="iq-card shadow-none m-0">
                              <div class="iq-card-body p-0 ">
                                 <div class="bg-primary p-3">
                                    <h5 class="mb-0 text-white line-height">Hello {{ Auth::user()->tenant_person }}</h5>
                                 </div>
                                 <div class="d-inline-block w-100 p-3" style="font-size: 16px; border-bottom: 1px solid #dfe6e9">
                                    <a href="/profile/{{ Auth::user()->tenant_code }}">
                                       <i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp; Profile
                                    </a>
                                 </div>
                                 <div class="d-inline-block w-100 text-center p-3">
                                    <a class="bg-primary btn btn-primary iq-sign-btn" href="{{ route("logout") }}" role="button">Sign out<i class="ri-login-box-line ml-2"></i></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </li>
                  </ul>
               </nav>
             </div>
             </div>
          </div>
       </div>
    </div>
 </div>
 <!-- TOP Nav Bar END -->
