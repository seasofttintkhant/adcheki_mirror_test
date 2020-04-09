@extends('layouts.admin')

@section('title', 'オペレータの追加')
@section('content')
@include('admin.partials.commons._content_header', ['title' => 'オペレータの追加'])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p>「オペレータ情報」を追加します。</p>
                        <form action="{{ route('operators.store') }}" method="POST">
                            @csrf
                            <table class="table table-bordered">
                                <tr>
                                    <td class="input-label bg-gray-light">ログインID</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="text" class="form-control @error('login_id') is-invalid @enderror" name="login_id" value="{{ old('login_id') ?: '' }}" required>
                                            @error('login_id')
                                            <span id="login-id-error" class="error invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">パスワード</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                            @error('password')
                                            <span id="password-error" class="error invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">種別</td>
                                    <td>
                                        <div class="form-inline">
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="1" type="radio" name="role" @if(old('role') && old('role') == 1) checked @endif>
                                                <label class="form-check-label">システム管理者</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                @if(old('role'))
                                                <input class="form-check-input" value="2" type="radio" name="role" 
                                                @if(old('role') && old('role') == 2) checked @endif>
                                                @else
                                                 <input class="form-check-input" value="2" type="radio" name="role" checked>
                                                @endif
                                                <label class="form-check-label">サイト管理者</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="3" type="radio" name="role" @if(old('role') && old('role') == 3) checked @endif>
                                                <label class="form-check-label">オペレータ1</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="4" type="radio" name="role" @if(old('role') && old('role') == 4) checked @endif>
                                                <label class="form-check-label">オペレータ2</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">許可IP</td>
                                    <td>
                                        <div class="col-8">
                                            <input type="text" class="form-control @error('permitted_ip') is-invalid @enderror" name="permitted_ip" value="{{ old('permitted_ip') ?: '' }}" required>
                                            @error('permitted_ip')
                                            <span id="permitted-ip-error" class="error invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="mt-4">
                                <input type="submit" value="追加" class="btn btn-default bg-gray-light">
                            </div>
                        </form>
                        <div class="my-2 text-right">
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-underline">
                                <u>ページ上部へ</u>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
@endsection