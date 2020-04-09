@extends('layouts.admin')

@section('title', __('messages.dashboard'))

@section('content')
@include('admin.partials.commons._content_header', ['title' => __('messages.dashboard')])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" id="js-server-resources-partial-target">
                        <!-- Server Resources -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script>
    function getPartialServerResources() {
        axios.get('/backend/partials/server-resources')
            .then(res => res.data)
            .then(html => {
                document.querySelector('#js-server-resources-partial-target').innerHTML = html;
            })
            .catch(e => console.log(e));
    }
    getPartialServerResources();
    setInterval(function() {
        getPartialServerResources();
    }, 60 * 1000);
</script>
@endsection