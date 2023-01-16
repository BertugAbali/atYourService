@extends('layouts.app')

<!-- This view for showing the auth user. Also ff he/she is service provider, it shows details. -->

@section('content')

<section style="background-color: #eee;">
    <div class="container py-5">


        <div class="card mb-4">
            <div class="card-header">{{ __('Owned Services') }}</div>
            <div class="card-body">
                @foreach ($services as $service)

                <div class="row">
                    <div class="col-sm-3">

                        <p class="mb-0"><strong>Title:</strong> {{ $service->title }}</p>
                    </div>
                    <div class="col-sm-3">
                        <p class=" mb-0"><strong>Price:</strong> €{{ $service->price }}</p>
                    </div>
                    <div class="col-sm-3">
                        <form method="GET" enctype="multipart/form-data" id="showService" action="{{ route('show.service', ['service' => $service]) }}">
                            @csrf

                            <button type="submit" class="btn btn-outline-primary" id="submit">See this service</button>

                        </form>
                    </div>
                    <div class="col-sm-3">
                        <form method="POST" enctype="multipart/form-data" id="deleteService" action="{{ route('delete.service', ['service' => $service]) }}">
                            @csrf

                            <button type="submit" class="btn btn-outline-danger" id="submit">Delete this service</button>

                        </form>
                    </div>
                </div>
                @if(!($loop->last))
                <hr>
                @endif
              

                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection