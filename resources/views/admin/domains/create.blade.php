@extends('layouts.admin')

@section('title', __('messages.add_domain'))

@section('content')
@include('admin.partials.commons._content_header', ['title' => __('messages.add_domain')])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('domains.store') }}" method="POST">
                            @csrf
                            <table class="table table-bordered">
                                <tr>
                                    <td class="input-label bg-gray-light">ドメイン</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?: '' }}" required></input>
                                            @error('name')
                                            <span id="name-error" class="error invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">IP</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="text" name="ip" id="ip" class="form-control @error('ip') is-invalid @enderror" value="{{ old('ip') ?: '' }}"required></input>
                                            @error('ip')
                                            <span id="ip-error" class="error invalid-feedback">
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