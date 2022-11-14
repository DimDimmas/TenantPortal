@if(empty(Auth::user()))
   <script>
      window.location = "{{ route('logout') }}";
   </script>
@else

<style>
  @media only screen and (max-width: 600px){
    .iq-light-logo{
      display: unset;
    }

    .iq-light-logo img{
      height: 60px;
      width: 80px;
    }
    .iq-menu-horizontal{
      top: 100%;
    }
    .iq-page-menu-horizontal .iq-top-navbar .iq-navbar-custom .iq-menu-bt {
      right: 60px;
    }
  }
  @media only screen and (max-width: 768px){
    .iq-light-logo{
      display: unset;
    }
    .iq-light-logo img{
      height: 60px
      width: 80px;
    }
    .iq-menu-horizontal{
      top: 100%;
    }
    .iq-page-menu-horizontal .iq-top-navbar .iq-navbar-custom .iq-menu-bt {
      right: 60px;
    }
  }
  @media only screen and (min-width: 768px){
    .iq-light-logo{
      display: none;
    }
  }
</style>

<div class="iq-top-navbar">
  <div class="iq-navbar-custom d-flex align-items-center justify-content-between">
    
    <div class="iq-sidebar-logo">
      <div class="top-logo">
        <a href="{{ route('news') }}" class="logo">
          <div class="iq-light-logo">
            <img src="{{ asset('moa/images/mmp-logo-transp.png')}}" class="img-fluid" alt="Logo">
          </div>
          <span><img src="{{ asset('moa/images/TenantPortal.png')}}" class="img-fluid" alt="Logo"></span>
        </a>
      </div>
    </div>

    <div class="iq-menu-horizontal">
      <nav class="iq-sidebar-menu">
        <ul id="iq-sidebar-toggle" class="iq-menu d-flex">


          <li class="{{ str_contains(Request::url(), 'news') ? 'active' : '' }}">
            <a href="{{ route('news') }}" class="iq-waves-effect collapsed" aria-expanded="false"><i class="ri-article-line"></i><span>News</span></a>
          </li>


          <li class="{{ str_contains(Request::url(), 'tracking-loading') ? 'active' : '' }}">
            <a href="#tracking-loading" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="fa fa-truck" aria-hidden="true"></i><span>Tracking Loading</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>  
            <ul id="tracking-loading" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
              <li class="{{ request()->is('tracking-loading') ? 'active' : '' }}">
                <a href="{{ route('history_tracking_loading') }}">History</a>
              </li>
              <!-- Tracking Loading Setting (Sidik) -->
              <li class="{{ request()->segment(2) == 'settings' ? 'active' : '' }}">
                <a href="/tracking-loading/settings">Setting</a>
              </li>
              <!-- Tracking Loading Setting (Sidik) -->
              <li class="{{ request()->is('tracking-loading/not-scan-out') ? 'active' : '' }}">
                <a href="{{ route('notscanout_tracking_loading') }}">Form Lost Ticket</a>
              </li>
              <!-- Tracking Loading Report Summary (Sidik) -->
              <li class="{{ request()->segment(2) == 'report-summary' ? 'active' : '' }}">
                <a href="/tracking-loading/report-summary">Report Summary</a>
              </li>
              <!-- Tracking Loading Report Summary (Sidik) -->
            </ul>
          </li>

          <!-- Preventive Maintenance khusus lazada -->
          {{-- @if(in_array(auth()->user()->tenant_code, ['MDP002', 'MDPO1', 'MDPO2', 'MDPW01', 'MDPW02', 'MDP02'])) --}}
          <li class="{{ request()->segment(2) == 'preventive' ? 'active' : '' }}">
            <a href="#preventive" class="iq-waves-effect collapse" data-toggle="collapse" aria-expanded="false">
              <i class="fas fa-wrench" aria-hidden="true"></i><span>Preventive</span><i class="ri-arrow-right-s-line iq-arrow-right"></i>
            </a>
            <ul id="preventive" class="iq-submenu collapse" data-parent="#id-sidebar-toggle">
              <li class="{{ request()->segment(3) == "maintenances" ? 'active' : '' }}">
                <a href="{{ route('preventive.maintenances.index') }}">Maintenances</a>
              </li>
              <li class="{{ request()->segment(3) == "done-maintenances" ? 'active' : '' }}">
                <a href="{{ route('preventive.done_maintenances.index') }}">Done Maintenances</a>
              </li>
              <li class="{{ request()->segment(3) == "report-actual-vs-schedule" ? 'active' : '' }}">
                <a href="{{ route('preventive.report_actual_vs_schedules.index') }}">Report Actual VS Schedule</a>
              </li>
            </ul>
          </li>
          {{-- @endif --}}
          <!-- End Of Preventive Maintenance -->

          <li class="{{ str_contains(Request::url(), 'invoice') ? 'active' : '' }}">
            <a href="#invoice" class="iq-waves-effect collapse" data-toggle="collapse" aria-expanded="false"><i class="fas fa-file-invoice"></i><span>Invoice</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
            <ul id="invoice" class="iq-submenu collapse" data-parent="#id-sidebar-toggle">
              <li class="{{ request()->is('invoice') ? 'active' : '' }}">
                <a href="{{ route('invoice_index') }}">History</a>
              </li>
            </ul>
          </li>


          <li class="{{ str_contains(Request::url(), 'corrective') ? 'active' : '' }}">
            <a href="#corrective" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-community-line"></i><span>Helpdesk</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>  
            <ul id="corrective" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
              <li class="{{ str_contains(Request::url(), 'corrective/request-ticket') ? 'active' : '' }}">
                <a href="{{ route('request_ticket') }}">Request Ticket</a>
              </li>
              <li class="{{ str_contains(Request::url(), 'corrective/history-ticket') ? 'active' : '' }}">
                <a href="{{ route('history_ticket') }}">History</a>
              </li>
            </ul>
          </li>


          <li class="{{ str_contains(Request::url(), 'meter') ? 'active' : '' }}">
            <a href="#meter" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-keyboard-box-line"></i><span>Meter</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>  
            <ul id="meter" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
              <li class="{{ str_contains(Request::url(), 'meter/list-confirmation') || str_contains(Request::url(), 'meter/summary') ? 'active' : '' }}">
                <a href="{{ route('list_meter_summary') }}">Summary</a>
              </li>
              <li class="{{ str_contains(Request::url(), 'meter/history') ? 'active' : '' }}">
                <a href="{{ route('list_meter_history') }}">History</a>
              </li>
            </ul>
          </li>


          @if (Auth::user()->entity_project == '301502' && trim(Auth::user()->project_no) == '210101')
            <li class="{{ str_contains(Request::url(), 'overtime') ? 'active' : '' }}">
              <a href="#overtime" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="fa fa-building" aria-hidden="true"></i><span>Overtime</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>  
              <ul id="overtime" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                <li class="{{ str_contains(Request::url(), 'overtime/request-ticket') ? 'active' : '' }}">
                  <a href="{{ route('request_overtime') }}">Request Overtime</a>
                </li>
                <li class="{{ str_contains(Request::url(), 'overtime/history-ticket') ? 'active' : '' }}">
                  <a href="{{ route('history_overtime') }}">History</a>
                </li>
              </ul>
            </li>
          @endif


        </ul>
      </nav>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light p-0">
      <div class="iq-menu-bt align-self-center">
        <div class="wrapper-menu">
          <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
          <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
        </div>
      </div>
      <ul class="navbar-list">
        <li>
          <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center">
            <i class="ri-account-circle-fill"></i>
          </a>
          <div class="iq-sub-dropdown iq-user-dropdown">
            <div class="iq-card shadow-none m-0">
              <div class="iq-card-body p-0 ">
                <div class="bg-primary p-3">
                    <h5 class="mb-0 text-white line-height">Hello {{ Auth::user()->pic_name1 }}</h5>
                </div>
                <div class="d-inline-block w-100 p-3" style="font-size: 16px; border-bottom: 1px solid #dfe6e9">
                  <a href="/profile/{{ Auth::user()->tenant_code }}">
                      <i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp; Profile
                  </a>
                </div>
                <div class="d-inline-block w-100 p-3" style="font-size: 16px; border-bottom: 1px solid #dfe6e9">
                  <a href="/change_password/{{ Auth::user()->tenant_code }}">
                      <i class="fa fa-key" aria-hidden="true"></i>&nbsp; Change Password
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
 <!-- TOP Nav Bar END -->
@endif