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
                                 <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                                     <i class="nav-icon fas fa-tachometer-alt"></i>
                                     <p>
                                         {{ __('messages.dashboard') }}
                                     </p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                     <i class="nav-icon fas fa-tachometer-alt"></i>
                                     <p>
                                         {{ __('messages.domain_list') }}
                                     </p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                     <i class="nav-icon fas fa-tachometer-alt"></i>
                                     <p>
                                         {{ __('messages.email_list') }}
                                     </p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                     <i class="nav-icon fas fa-tachometer-alt"></i>
                                     <p>
                                         {{ __('messages.operator_list') }}
                                     </p>
                                 </a>
                             </li>
                             <li class="nav-item has-treeview">
                                 <a href="#" class="nav-link">
                                     <i class="nav-icon fas fa-tree"></i>
                                     <p>
                                         Tree Menu
                                         <i class="right fas fa-angle-left"></i>
                                     </p>
                                 </a>
                                 <ul class="nav nav-treeview">
                                     <li class="nav-item">
                                         <a href="#" class="nav-link">
                                             <i class="far fa-circle nav-icon"></i>
                                             <p>Menu 1</p>
                                         </a>
                                     </li>
                                     <li class="nav-item">
                                         <a href="#" class="nav-link active">
                                             <i class="far fa-circle nav-icon"></i>
                                             <p>Menu 2</p>
                                         </a>
                                     </li>
                                     <li class="nav-item">
                                         <a href="#" class="nav-link">
                                             <i class="far fa-circle nav-icon"></i>
                                             <p>Menu 3</p>
                                         </a>
                                     </li>
                                 </ul>
                             </li>

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