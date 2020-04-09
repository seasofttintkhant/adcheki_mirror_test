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

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
@endsection