@extends('layouts.admin')

@section('title', __('messages.dashboard'))

@section('content')
@include('admin.partials.commons._content_header', ['title' => __('messages.dashboard')])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Resource Monitor</h4>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection