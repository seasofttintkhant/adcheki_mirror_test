@extends('layouts.admin')

@section('title', 'オペレーターの編集')
@section('content')
@include('admin.partials.commons._content_header', ['title' => 'オペレーターの編集'])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p>オペレーター情報を編集する。</p>
                        <form action="{{ route('operators.update', $operator->operator_id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="id" value="{{ $operator->id }}">
                            <table class="table table-bordered">
                                <tr>
                                    <td class="input-label bg-gray-light">ログインID</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="text" class="form-control @error('login_id') is-invalid @enderror" name="login_id" value="{{ old('login_id') ?: $operator->login_id }}" required>
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
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
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
                                                <input class="form-check-input" value="1" type="radio" name="role" @if(old('role') && old('role') == 1) checked @elseif ($operator->role == 1) checked @endif>
                                                <label class="form-check-label">システム管理者</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                @if(old('role'))
                                                <input class="form-check-input" value="2" type="radio" name="role" 
                                                @if(old('role') && old('role') == 2) checked @elseif ($operator->role == 2) checked @endif>
                                                @else
                                                 <input class="form-check-input" value="2" type="radio" name="role" @if ($operator->role == 2) checked @endif>
                                                @endif
                                                <label class="form-check-label">サイト管理者</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="3" type="radio" name="role" @if(old('role') && old('role') == 3) checked @elseif ($operator->role == 3) checked @endif>
                                                <label class="form-check-label">オペレータ1</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="4" type="radio" name="role" @if(old('role') && old('role') == 4) checked @elseif ($operator->role == 4) checked @endif>
                                                <label class="form-check-label">オペレータ2</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">許可IP</td>
                                    <td>
                                        <div class="col-8">
                                            <input type="text" class="form-control @error('permitted_ip') is-invalid @enderror" name="permitted_ip" value="{{ old('peprmitted_ip') ?: $operator->permitted_ip }}" required>
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