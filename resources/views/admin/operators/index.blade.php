@extends('layouts.admin')

@section('title', __('messages.operators_list'))

@section('content')
@include('admin.partials.commons._content_header', ['title' => __('messages.operators_list')])
@includeWhen(session()->has('success'), 'admin.partials.commons._swal', [
    'title' => 'Success!',
    'type' => 'success', 
    'message' => session('success')
])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('operators.search') }}" method="GET">
                            <table class="table table-bordered">
                                <tr>
                                    <td class="input-label bg-gray-light">種別</td>
                                    <td>
                                        <div class="form-inline">
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="0" type="radio" name="role" checked>
                                                <label class="form-check-label">全て</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="1" type="radio" name="role" @if(request()->query('role') == 1) checked @endif>
                                                <label class="form-check-label">システム管理者</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="2" type="radio" name="role" @if(request()->query('role') == 2) checked @endif>
                                                <label class="form-check-label">サイト管理者</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="mt-2 mb-4">
                                <input type="submit" value="オペレータの一覧を表示" class="btn btn-default bg-gray-light">
                            </div>
                        </form>
                        <div id="filtered-results">
                            @php
                            $startItems = $operators->currentPage() !== 1
                            ? $operators->currentPage() * $operators->perPage()
                            : $operators->currentPage();
                            $endItems = $startItems !== 1
                            ? $startItems + count($operators)
                            : count($operators);
                            @endphp
                            <p>{{ $operators->total() }}件中 {{ $startItems }}件~{{ $endItems }}件 を表示</p>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center bg-gray-light">
                                        <th>opid</th>
                                        <th>ログインID</th>
                                        <th>種別</th>
                                        <th>許可IP</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($operators) && count($operators) > 0)
                                    @foreach($operators as $operator)
                                    <tr>
                                        <td>
                                            {{ $operator->operator_id }}
                                        </td>
                                        <td>
                                            {{ $operator->login_id}}
                                        </td>
                                        <td>
                                            @switch($operator->role)
                                            @case(1)
                                            システム管理者
                                            @break
                                            @case(2)
                                            サイト管理者
                                            @break
                                            @default
                                            システム管理者
                                            @break
                                            @endswitch
                                        </td>
                                        <td>
                                            {{ $operator->permitted_ip ?: 'ANY' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('operators.edit', $operator->id) }}">
                                                編集
                                            </a>
                                            <a href="javascript:void(0);" role="button" class="remove" data-id="{{ $operator->id }}" data-content="{{ $operator->login_id }}" data-type="operators">
                                                削除
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <p>表示するデータがありません</p>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if(isset($operators) && count($operators) > 0)
                            <div class="d-flex justify-content-center">
                                {{ $operators->links() }}
                            </div>
                            @endif
                            <div class="my-2 text-right">
                                <a href="{{ route('admin.dashboard') }}" class="text-decoration-underline">
                                    <u>トップへ戻る</u>
                                </a>
                            </div>
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