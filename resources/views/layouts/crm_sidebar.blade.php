<div class="iq-sidebar">
            <div class="iq-sidebar-logo d-flex justify-content-between">
               <a href="{{ url('/index1') }}">
               <img src="{{ asset ('moa/images/logo.gif')}}" class="img-fluid" alt="">
               <span>MOA</span>
               </a>
               <div class="iq-menu-bt-sidebar">
                     <div class="iq-menu-bt align-self-center">
                        <div class="wrapper-menu">
                           <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                           <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                        </div>
                     </div>
                  </div>
            </div>
            <div id="sidebar-scrollbar">
               <nav class="iq-sidebar-menu">
                  <ul id="iq-sidebar-toggle" class="iq-menu">
                     <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Dashboard</span></li>
                     <li>
                        <a href="{{ url('/index1') }}" class="iq-waves-effect"><i class="ri-home-4-line"></i><span>Dashboard 1</span></a>
                     </li>
                     <li>
                        <a href="{{ url('/index2') }}" class="iq-waves-effect"><i class="ri-home-3-line"></i><span>Dashboard 2</span></a>
                     </li>
                     <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Main</span></li>
                     <li>
                        <a href="#menu-admin" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-menu-3-line"></i><span>Admin Setting</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                           <ul id="menu-admin" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                              <li><a href="{{ route('users.index') }}"><i class="ri-user-line"></i>User & Roles</a></li>
                              <li><a href="User & Group"><i class="ri-user-5-line"></i>Roles & Permission</a></li>
                           </ul>   
                     </li>
                     <li>
                        <a href="#employeeinfo" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ion-android-people"></i><span>Employees</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="employeeinfo" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="{{ route('employee.index') }}"><i class="ri-profile-line"></i>All Employee</a></li>
                           <li><a href="{{ route('emp_status.index') }}"><i class="ri-file-edit-line"></i>Employee Status</a></li>
                           <li><a href="{{ route('designations.index') }}"><i class="ri-file-edit-line"></i>Designations</a></li>
                           <li><a href="{{ route('jobtitel.index') }}"><i class="ri-file-edit-line"></i>Job Titel</a></li>
                           <li><a href="{{ route('marital.index') }}"><i class="ri-file-edit-line"></i>Marital</a></li>
                          
                        </ul>
                     </li>
		               <li>
                        <a href="#masterfileinfo" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ion-soup-can"></i><span>Master Data</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="masterfileinfo" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="{{ route('entity.index') }}"><i class="ri-home-4-line"></i>Entity</a></li>
                           <li><a href="{{ route('project.index') }}"><i class="ri-building-2-line "></i>Project</a></li>
                           <li><a href="{{ route('department.index') }}"><i class="ri-group-line"></i>Department</a></li>
                        </ul>
                     </li>
                     <li>
                        <a href="#generalledgerinfo" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ion-soup-can"></i><span>General Ledger</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="generalledgerinfo" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="{{ route('masteraccount.index') }}"><i class="ri-home-4-line"></i>Master Account</a></li>
                           <li><a href=""><i class="ri-building-2-line "></i>Chart of Account</a></li>
                           <li><a href=""><i class="ri-group-line"></i>Department</a></li>
                        </ul>
                     </li>
                     <li>
                     <li>
                        <a href="#employeeperformance" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-user-line"></i><span>Performance</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="employeeperformance" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="kpi"><i class="ri-profile-line"></i>Performance Indicator</a></li>
                           <li><a href="empreview"><i class="ri-file-edit-line"></i>Performance Review</a></li>
                           <li><a href="empappraisal"><i class="ri-user-add-line"></i>Performance Appraisal</a></li>
                        </ul>
                     </li>
                     <li><a href="calendar.html" class="iq-waves-effect"><i class="ri-calendar-2-line"></i><span>Calendar</span></a></li>
                     <li><a href="chats" class="iq-waves-effect"><i class="ri-message-line"></i><span>Chats</span></a></li>
                     <li><a href="todo.html" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>File Manager</span></a></li>
                     <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Pipeline</span></li>
                     <li><a href="todo.html" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>Leads</span></a></li>
                     <li><a href="todo.html" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>Clients</span></a></li>
                     <li><a href="todo.html" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>Projects</span></a></li>
                     <li><a href="todo.html" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>Task</span></a></li>
                     <li><a href="todo.html" class="iq-waves-effect" aria-expanded="false"><i class="ri-chat-check-line"></i><span>Task Board</span></a></li>
                     <li>
                        <a href="#forms" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-profile-line"></i><span>Letters</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="forms" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="loo"><i class="ri-tablet-line"></i>Letter of Offer</a></li>
                           <li><a href="loi"><i class="ri-device-line"></i>Letter of Intent</a></li>
                           <li><a href="lease"><i class="ri-toggle-line"></i>Lease Aggrements</a></li>
                           <li><a href="booking"><i class="ri-checkbox-line"></i>Bookings</a></li>
                           <li><a href="salesorder"><i class="ri-radio-button-line"></i>Sales Order</a></li>
                        </ul>
                     </li>
                     <li>
                        <a href="#forms" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-profile-line"></i><span>Contracts</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="forms" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="contract"><i class="ri-tablet-line"></i>All Contract</a></li>
                           <li><a href="contractrenewal"><i class="ri-toggle-line"></i>Contract Renewal</a></li>
                           <li><a href="contractterminate"><i class="ri-checkbox-line"></i>Contract Terminate</a></li>
                        </ul>
                     </li>
                     <li><a href="backups" class="ri-device-line"><i class="ri-tablet-line"></i><span>Backup</span></a></li>
                     <li><a href="about" class="ri-device-line"><i class="ri-tablet-line"></i><span>About</span></a></li>
                  </ul>
               </nav>
               <div class="p-3"></div>
            </div>
         </div>
