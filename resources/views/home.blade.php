@extends('layouts.app')

<!-- This view for showing the services based on your chosed category. If you didn't chosed any, it will show all of it. -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">


            <div class="card">

                <div class="card-header">
                @if (isset($area))
                {{$area}}   {{ __('Services') }}
                @else
                {{ __('All Services') }}
                @endif
                               
                </div>

                <div class="card-group">

                    @include('partials.list')
                </div>
            </div>

        </div>
        <div class="d-flex justify-content-center">
            {!! $services->links('pagination::bootstrap-5') !!}
        </div>
    </div>
   
</div>
@endsection