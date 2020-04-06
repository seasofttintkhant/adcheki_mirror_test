<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | {{ __('messages.admin') }} {{ __('messages.login') }}</title>
    <link rel="stylesheet" href="{{ asset('admin/css/admin.css') }}">
</head>

<body class="login-page" cz-shortcut-listen="true">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('admin.show_login') }}"><b>Ad-</b>Cheki</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="{{ route('admin.login') }}" method="post">
                    @csrf
                    @if(session()->has('invalidLogin'))
                    <div id="alert" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('invalidLogin') }}
                    </div>
                    @endif
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') ? is-invalid @enderror" placeholder="Email" value="{{ old('email') ?: '' }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                        <span id="email-error" class="error invalid-feedback">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') ? is-invalid @enderror" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span id="password-error" class="error invalid-feedback">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('messages.login') }}
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <script src="{{ asset('admin/js/admin.js') }}"></script>
</body>

</html>