@extends('layouts.app')

<!-- This view for showing the services based on your chosed category. If you didn't chosed any, it will show all of it. -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">


            <div class="card">

                <div class="card-header">
                    <ul class="navbar-nav ">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ __('Service Areas') }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                @foreach ($service_areas as $area)
                                <a class="dropdown-item" href="{{ route('search.area', ['area' => $area->name]) }}">
                                    {{$area->name}}
                                </a> @endforeach

                            </div>
                        </li>
                    </ul>
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