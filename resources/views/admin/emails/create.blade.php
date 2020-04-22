@extends('layouts.admin')

@section('title', __('messages.add_email'))

@section('content')
@include('admin.partials.commons._content_header', ['title' => __('messages.add_email')])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p>「メールアドレス情報」を追加します。</p>
                        <form action="{{ route('emails.store') }}" method="POST">
                            @csrf
                            <table class="table table-bordered">
                                <tr>
                                    <td class="input-label bg-gray-light">登録日</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="date" class="form-control @error('registration_date') is-invalid @enderror" name="registration_date" value="{{ old('registration_date') ?: date('Y-m-d') }}" required>
                                            @error('registration_date')
                                            <span id="registration-date-error" class="error invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">更新日</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="date" class="form-control @error('update_date') is-invalid @enderror" name="update_date" value="{{ old('update_date') ?: date('Y-m-d') }}" required>
                                            @error('update_date')
                                            <span id="update-date-error" class="error invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">メールアドレス</td>
                                    <td>
                                        <div class="col-8">
                                            <input type="email" class="form-control @error('mail_address') is-invalid @enderror" name="mail_address" value="{{ old('mail_address') ?: '' }}" required>
                                            @error('mail_address')
                                            <span id="mail-address-error" class="error invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">ok数</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="text" class="form-control @error('is_ok') is-invalid @enderror" name="is_ok" value="{{ old('is_ok') ?: '' }}" required>
                                            @error('is_ok')
                                            <span id="is-ok-error" class="error invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">ng数</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="text" class="form-control @error('is_ng') is-invalid @enderror" name="is_ng" value="{{ old('is_ng') ?: '' }}" required>
                                            @error('is_ng')
                                            <span id="is-ng-error" class="error invalid-feedback">
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