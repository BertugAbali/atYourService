@extends('layouts.app')

<!-- This view for showing the auth user. Also ff he/she is service provider, it shows details. -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Owned Services') }}</div>

                @foreach ($services as $service)

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            {{ __('Title: ') }} {{ $service->title }}

                        </div>
                        <div class="col-md-2">
                            {{ __('Price: ') }} {{ $service->price }}

                        </div>
                        <div class="col-md-3">
                            <form method="GET" enctype="multipart/form-data" id="showService" action="{{ route('show.service', ['service' => $service]) }}">
                                @csrf

                                <button type="submit" class="btn btn-primary" id="submit">See this service</button>

                            </form>
                        </div>
                        <div class="col-md-3">
                            <form method="POST" enctype="multipart/form-data" id="deleteService" action="{{ route('delete.service', ['service' => $service]) }}">
                                @csrf

                                <button type="submit" class="btn btn-danger" id="submit">Delete this service</button>

                            </form>
                        </div>
                    </div>
                </div>



                @endforeach


            </div>
        </div>
    </div>
</div>
@endsection