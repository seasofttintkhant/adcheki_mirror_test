@extends('layouts.admin')

@section('title', __('messages.domains_list'))

@section('content')
@include('admin.partials.commons._content_header', ['title' => __('messages.domains_list')])
@php
    function checkIsMatch($domainName, $ip)
    {
        $dnsIp = null;
        $dnsRecord = dns_get_record($domainName, DNS_A);
        if ($dnsRecord) {
            $dnsIp = $dnsRecord[0]['ip'];
        }
        return $ip === $dnsIp;
    }
@endphp
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
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center bg-gray-light">
                                    <th>ドメイン名</th>
                                    <th>ip(def)</th>
                                    <th>ip(dns)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($domains) && count($domains) > 0)
                                @foreach($domains as $domain)
                                <tr @unless($domain->is_match && checkIsMatch($domain->name, $domain->default_ip)) class="bg-red" @endunless>
                                    <td>{{ $domain->name }}</td>
                                    <td>{{ $domain->default_ip }}</td>
                                    <td>{{ $domain->dns_ip ?: '-' }}</td>
                                     <td>
                                        <a href="{{ route('domains.edit', $domain->id) }}">
                                            編集
                                        </a>
                                        <a href="javascript:void(0);" role="button" class="remove" data-id="{{ $domain->id }}" data-content="{{ $domain->name }}" data-type="domains">
                                            削除
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p>表示するデータがありません</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        @if(isset($domains) && count($domains) > 0)
                        <div class="d-flex justify-content-center">
                            {{ $domains->links() }}
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
</section>
@endsection
@section('js')
@endsection