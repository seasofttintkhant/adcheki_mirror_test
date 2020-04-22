 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
         <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
         <span class="brand-text font-weight-light">Ad-Cheki</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">
         <div class="os-resize-observer-host">
             <div class="os-resize-observer observed"></div>
         </div>
         <div class="os-size-auto-observer">
             <div class="os-resize-observer observed"></div>
         </div>
         <div class="os-content-glue"></div>
         <div class="os-padding">
             <div class="os-viewport os-viewport-native-scrollbars-invisible os-viewport-native-scrollbars-overlaid">
                 <div class="os-content">
                     <!-- Sidebar Menu -->
                     <nav class="mt-2">
                         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                             <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                             <li class="nav-item">
                                 <a href="{{ route('admin.dashboard') }}" class="nav-link @if (request()->is('backend')) active @endif">
                                     <i class="nav-icon fas fa-tachometer-alt"></i>
                                     <p>
                                         {{ __('messages.dashboard') }}
                                     </p>
                                 </a>
                             </li>
                             <li class="nav-item has-treeview">
                                 <a href="javascript:void(0);" class="nav-link @if (request()->is('backend/domains') || request()->is('backend/domains/create')) active @endif">
                                     <i class="nav-icon fas fa-at"></i>
                                     <p>
                                         {{ __('messages.domain') }}
                                         <i class="right fas fa-angle-left"></i>
                                     </p>
                                 </a>
                                 <ul class="nav nav-treeview">
                                     <li class="nav-item">
                                         <a href="{{ route('domains.index') }}" class="nav-link @if (request()->is('backend/domains')) active @endif">
                                             <i class="fas fa-list nav-icon"></i>
                                             <p>{{ __('messages.domains_list') }}</p>
                                         </a>
                                     </li>
                                     <li class="nav-item">
                                         <a href="{{ route('domains.create') }}" class="nav-link @if (request()->is('backend/domains/create')) active @endif">
                                             <i class="fas fa-plus nav-icon"></i>
                                             <p>{{ __('messages.add_domain') }}</p>
                                         </a>
                                     </li>
                                 </ul>
                             </li>
                             <li class="nav-item has-treeview">
                                 <a href="javascript:void(0);" class="nav-link @if (request()->is('backend/emails') || request()->is('backend/emails/create')) active @endif">
                                     <i class="nav-icon fas fa-envelope"></i>
                                     <p>
                                         {{ __('messages.email') }}
                                         <i class="right fas fa-angle-left"></i>
                                     </p>
                                 </a>
                                 <ul class="nav nav-treeview">
                                     <li class="nav-item">
                                         <a href="{{ route('emails.index') }}" class="nav-link @if (request()->is('backend/emails')) active @endif">
                                             <i class="fas fa-list nav-icon"></i>
                                             <p>{{ __('messages.emails_list') }}</p>
                                         </a>
                                     </li>
                                     <li class="nav-item">
                                         <a href="{{ route('emails.create') }}" class="nav-link @if (request()->is('backend/emails/create')) active @endif">
                                             <i class="fas fa-plus nav-icon"></i>
                                             <p>{{ __('messages.add_email') }}</p>
                                         </a>
                                     </li>
                                 </ul>
                             </li>
                            @if(Auth::guard('admin')->user()->role === 1)
                            <li class="nav-item has-treeview">
                                <a href="javascript:void(0);" class="nav-link @if (request()->is('backend/operators') || request()->is('backend/operators/create')) active @endif">
                                    <i class="nav-icon fas fa-user-shield"></i>
                                    <p>
                                        {{ __('messages.operator') }}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('operators.index') }}" class="nav-link @if (request()->is('backend/operators')) active @endif">
                                            <i class="fas fa-list nav-icon"></i>
                                            <p>{{ __('messages.operators_list') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('operators.create') }}" class="nav-link @if (request()->is('backend/operators/create')) active @endif">
                                            <i class="fas fa-plus nav-icon"></i>
                                            <p>{{ __('messages.add_operator') }}</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif
                         </ul>
                     </nav>
                     <!-- /.sidebar-menu -->
                 </div>
             </div>
         </div>
         <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
             <div class="os-scrollbar-track">
                 <div class="os-scrollbar-handle"></div>
             </div>
         </div>
         <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
             <div class="os-scrollbar-track">
                 <div class="os-scrollbar-handle"></div>
             </div>
         </div>
         <div class="os-scrollbar-corner"></div>
     </div>
     <!-- /.sidebar -->
 </aside>