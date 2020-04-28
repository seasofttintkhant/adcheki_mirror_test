@extends('layouts.admin')

@section('title', __('messages.edit_email'))

@section('content')
@include('admin.partials.commons._content_header', ['title' => __('messages.edit_email')])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p>メール情報を編集する。</p>
                        <form action="{{ route('emails.update', $email->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <table class="table table-bordered">
                                <tr>
                                    <td class="input-label bg-gray-light">メールアドレス</td>
                                    <td>
                                        <div class="col-8">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?: $email->email }}" required>
                                            @error('email')
                                            <span id="email-error" class="error invalid-feedback">
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
                                            <input type="text" class="form-control @error('is_valid') is-invalid @enderror" name="is_valid" value="{{ old('is_valid') ?: $email->is_valid }}" required />
                                            @error('is_valid')
                                            <span id="is-valid-error" class="error invalid-feedback">
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
                                            <input type="text" class="form-control @error('status') is-invalid @enderror" name="status" value="{{ old('status') ?: $email->status }}" required>
                                            @error('status')
                                            <span id="status-error" class="error invalid-feedback">
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