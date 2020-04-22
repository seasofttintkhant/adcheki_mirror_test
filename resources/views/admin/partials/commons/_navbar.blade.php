<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @auth('admin')
        <li class="nav-item d-flex flex-row align-items-center">
            {{ __('messages.welcome', ['name' => Auth::guard('admin')->user()->login_id ]) }} :
            <a class="nav-link" href="javascript:void(0);" role="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('messages.logout') }}
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </a>
        </li>
        @endauth
    </ul>
</nav>