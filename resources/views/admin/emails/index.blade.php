@extends('layouts.admin')

@section('title', __('messages.emails_list'))

@section('content')
@include('admin.partials.commons._content_header', ['title' => __('messages.emails_list')])
@php
    function convertToDate($date)
    {
        return date('Y-m-d', strtotime($date));
    }
@endphp
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('emails.search') }}" method="GET">
                            <table class="table table-bordered">
                                <tr>
                                    <td class="input-label bg-gray-light">登録日</td>
                                    <td>
                                        <div class="row px-2">
                                            <div class="col-4">
                                                <input 
                                                    type="date" 
                                                    name="registration_start_date"
                                                    class="form-control @error('registration_start_date') is-invalid @enderror"
                                                    value="{{ 
                                                        old('registration_start_date') 
                                                        ? convertToDate(old('registration_start_date')) 
                                                        : (request()->query('registration_start_date') 
                                                            ? convertToDate(request()->query('registration_start_date')) 
                                                            : ''
                                                        ) 
                                                    }}"
                                                >
                                                @error('registration_start_date')
                                                <span id="registration-start-date-error" class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                            <span class="mt-2">~</span>
                                            <div class="col-4">
                                                <input 
                                                    type="date" 
                                                    name="registration_end_date"
                                                    class="form-control @error('registration_end_date') is-invalid @enderror"
                                                    value="{{ 
                                                        old('registration_end_date') 
                                                        ? convertToDate(old('registration_end_date')) 
                                                        : (request()->query('registration_end_date') 
                                                            ? convertToDate(request()->query('registration_end_date')) 
                                                            : ''
                                                        ) 
                                                    }}" 
                                                >
                                                @error('registration_end_date')
                                                <span id="registration-end-date-error" class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">更新日</td>
                                    <td>
                                        <div class="row px-2">
                                            <div class="col-4">
                                                <input 
                                                    type="date" 
                                                    name="update_start_date"
                                                    class="form-control @error('update_start_date') is-invalid @enderror"
                                                    value="{{ 
                                                        old('update_start_date') 
                                                        ? convertToDate(old('update_start_date')) 
                                                        : (request()->query('update_start_date') 
                                                            ? convertToDate(request()->query('update_start_date')) 
                                                            : ''
                                                        ) 
                                                    }}"
                                                >
                                                @error('update_start_date')
                                                <span id="update-start-date-error" class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                            <span class="mt-2">~</span>
                                            <div class="col-4">
                                                <input 
                                                    type="date" 
                                                    name="update_end_date"
                                                    class="form-control @error('update_end_date') is-invalid @enderror"
                                                    value="{{ 
                                                        old('update_end_date') 
                                                        ? convertToDate(old('update_end_date')) 
                                                        : (request()->query('update_end_date') 
                                                            ? convertToDate(request()->query('update_end_date')) 
                                                            : ''
                                                        ) 
                                                    }}"
                                                >
                                                @error('update_end_date')
                                                <span id="update-end-date-error" class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">メールアドレス</td>
                                    <td>
                                        <div class="col-8 pr-0">
                                            <input 
                                                type="email" 
                                                class="form-control @error('email') is-invalid @enderror"
                                                name="email"
                                                value="{{ old('email') ?: request()->query('email') }}"
                                            >
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
                                        <div class="row px-2">
                                            <div class="col-4">
                                                <input 
                                                    type="text" 
                                                    class="form-control @error('is_valid_start') is-invalid @enderror"
                                                    name="is_valid_start" 
                                                    value="{{ old('is_valid_start') ?: request()->query('is_valid_start') }}"
                                                >
                                                @error('is_valid_start')
                                                <span id="is-valid-start-error" class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                            <span class="mt-2">~</span>
                                            <div class="col-4">
                                                <input 
                                                    type="text" 
                                                    class="form-control @error('is_valid_end') is-invalid @enderror"
                                                    name="is_valid_end"
                                                    value="{{ old('is_valid_end') ?: request()->query('is_valid_end') }}"
                                                >
                                                @error('is_valid_end')
                                                <span id="is-valid-end-error" class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">ng数</td>
                                    <td>
                                        <div class="row px-2">
                                            <div class="col-4">
                                                <input 
                                                    type="text" 
                                                    class="form-control @error('status_start') is-invalid @enderror" 
                                                    name="status_start"
                                                    value="{{ old('status_start') ?: request()->query('status_start') }}"
                                                >
                                                @error('status_start')
                                                <span id="status-start-error" class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                            <span class="mt-2">~</span>
                                            <div class="col-4">
                                                <input 
                                                    type="text" 
                                                    class="form-control @error('status_end') is-invalid @enderror" 
                                                    name="status_end"
                                                    value="{{ old('status_end') ?: request()->query('status_end') }}"
                                                >
                                                @error('status_end')
                                                <span id="status-end-error" class="error invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="input-label bg-gray-light">OS</td>
                                    <td>
                                        <div class="form-inline">
                                            <div class="form-check mx-2">
                                                <input 
                                                    class="form-check-input" 
                                                    value="0" 
                                                    type="radio" 
                                                    name="os" 
                                                    checked
                                                >
                                                <label class="form-check-label">全て</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input 
                                                    class="form-check-input" 
                                                    value="1" 
                                                    type="radio" 
                                                    name="os"
                                                    @if(request()->query('os') == 1) checked @endif
                                                >
                                                <label class="form-check-label">android</label>
                                            </div>
                                            <div class="form-check mx-2">
                                                <input 
                                                    class="form-check-input" 
                                                    value="2" 
                                                    type="radio" 
                                                    name="os"
                                                    @if(request()->query('os') == 2) checked @endif
                                                >
                                                <label class="form-check-label">ios</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="row mt-4">
                                <div class="mx-2">
                                    <input type="submit" name="search" value="メールアドレスの表示" class="btn btn-default bg-gray-light">
                                </div>
                                @can('download', App\Models\Email::class)
                                <div class="mx-2">
                                    <input type="submit" name="download" value="CSVダウンロード" class="btn btn-default bg-gray-light">
                                </div>
                                @endcan
                            </div>
                           
                        </form>
                        <div id="filtered-results" class="mt-4">
                            @php
                            $startItems = $emails->currentPage() !== 1
                            ? $emails->currentPage() * $emails->perPage()
                            : $emails->currentPage();
                            $endItems = $startItems !== 1
                            ? $startItems + count($emails)
                            : count($emails);
                            @endphp
                            <p>{{ $emails->total() }}件中 {{ $startItems }}件~{{ $endItems }}件 を表示</p>
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
                                    @if(isset($emails) && count($emails) > 0)
                                    @foreach($emails as $email)
                                    <tr>
                                        <td>
                                            {{ $email->mail_address_id }}
                                        </td>
                                        <td>
                                            {{ $email->email}}
                                        </td>
                                        <td>
                                            {{ date('Y-m-d', strtotime($email->created_at)) }}
                                        </td>
                                        <td>
                                            {{ date('Y-m-d', strtotime($email->created_at)) }}
                                        </td>
                                        <td>
                                            {{ $email->is_valid }}
                                        </td>
                                        <td>
                                            {{ $email->status }}
                                        </td>
                                        <td>
                                            @if($email->device)
                                                @if($email->device->os === 1)
                                                    android 
                                                @elseif($email->device->os === 2)
                                                    ios
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @can('edit', $email)
                                            <a href="{{ route('emails.edit', $email->id) }}">
                                                編集
                                            </a>
                                            @endcan
                                            @can('remove', $email)
                                            <a href="javascript:void(0);" role="button"
                                                onclick="event.preventDefault(); document.getElementById('delete-form').submit();">
                                                削除
                                                <form id="delete-form"
                                                    action="{{ route('emails.destroy', $email->id) }}" method="POST"
                                                    style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <p>データーがない</p>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if(isset($emails) && count($emails) > 0)
                            <div class="d-flex justify-content-center">
                                {{ $emails->links() }}
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
<script>
    @if(session('success'))
    $(document).Toasts('create', {
        autohide: true,
        close: false,
        delay: 3000,
        class: 'bg-success toast-width m-4',
        title: 'Success',
        body: '{{ session("success") }}'
    });
    @endif
</script>
@endsection