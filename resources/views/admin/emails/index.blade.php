@extends('layouts.admin')

@section('title', __('messages.emails_list'))

@section('content')
@include('admin.partials.commons._content_header', ['title' => __('messages.emails_list')])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('emails.store') }}" method="POST">
                            @csrf
                            <table class="table table-bordered">
                                <tr>
                                    <td class="input-label bg-gray-light">登録日</td>
                                    <td>
                                        <div class="row px-2">
                                            <div class="col">
                                                <select name="start_year" id="start_year">
                                                    <option value="0">--</option>
                                                    @for($i = 1900; $i < 12; $i++) <option value="{{ $i }}">
                                                        $i
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select name="start_month" id="start_month">
                                                    <option value="0">--</option>
                                                    @for($i = 1; $i < 12; $i++) <option value="{{ $i }}">
                                                        $i
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select name="start_day" id="start_day">
                                                    <option value="0">--</option>
                                                    @for($i = 1; $i < 12; $i++) <option value="{{ $i }}">
                                                        $i
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                            <span class="mt-2">~</span>
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">更新日</td>
                                    <td>
                                        <div class="col-4">
                                            <input type="text" class="form-control" name=" update_date">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">メールアドレス</td>
                                    <td>
                                        <div class="col-8">
                                            <input type="email" class="form-control" name=" mail_address">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">ok数</td>
                                    <td>
                                        <div class="row px-2">
                                            <div class="col-4">
                                                <input type="text" class="form-control" name="is_ok">
                                            </div>
                                            <span class="mt-2">~</span>
                                            <div class="col-4">
                                                <input type="text" class="form-control" name="is_ok">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">ng数</td>
                                    <td>
                                        <div class="row px-2">
                                            <div class="col-4">
                                                <input type="text" class="form-control" name="is_ng">
                                            </div>
                                            <span class="mt-2">~</span>
                                            <div class="col-4">
                                                <input type="text" class="form-control" name="is_ng">
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">OS</td>
                                    <td>
                                        <div class="form-inline">
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="0" type="radio" name="os" checked>
                                                <label class="form-check-label">全て</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="1" type="radio" name="os" @if(request()->query('os') == 1) checked @endif>
                                                <label class="form-check-label">android</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input class="form-check-input" value="2" type="radio" name="os" @if(request()->query('os') == 2) checked @endif>
                                                <label class="form-check-label">ios</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="mt-4">
                                <input type="submit" value="追加" class="btn btn-default bg-gray-light">
                            </div>
                        </form>
                        <div id="filtered-results">
                            <!-- @php
                            $startItems = $emails->currentPage() !== 1
                            ? $emails->currentPage() * $emails->perPage()
                            : $emails->currentPage();
                            $endItems = $startItems !== 1
                            ? $startItems + count($emails)
                            : count($emails);
                            @endphp -->
                            <!-- <p>{{ $emails->total() }}件中 {{ $startItems }}件~{{ $endItems }}件 を表示</p> -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center bg-gray-light">
                                        <th>maid</th>
                                        <th>メアド</th>
                                        <th>登録日</th>
                                        <th>更新日</th>
                                        <th>ok数</th>
                                        <th>ng数</th>
                                        <th>OS</th>
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
                                            @case(3)
                                            オペレータ1
                                            @break
                                            @case(4)
                                            オペレータ2
                                            @break
                                            @default
                                            システム管理者
                                            @break
                                            @endswitch
                                        </td>
                                        <td>
                                            {{ $operator->permitted_ip }}
                                        </td>
                                        <td>
                                            <a href="{{ route('operators.edit', $operator->operator_id) }}">
                                                編集
                                            </a>
                                            <a href="javascript:void(0);" role="button" onclick="event.preventDefault(); document.getElementById('delete-form').submit();">
                                                削除
                                                <form id="delete-form" action="{{ route('operators.destroy', $operator->operator_id) }}" method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <p>データーがない</p>
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
                                    <u>ページ上部へ</u>
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