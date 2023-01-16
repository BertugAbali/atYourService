@extends('layouts.app')

<!-- This view for showing the chosed service. -->


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <img src="{{asset('storage/images/'.$service->path)}}" alt="{{$service->name}}" class="card-img-top">
                <div class="card-body">
                    <h3 class="card-title">{{$service->title}}</h3>
                    <p class="card-text">{{$service->detail}}</p>
                    <div class="row">
                        <p class="card-text"><strong>Service Owner:</strong> <a href="{{ route('show.profile', ['id' => $service->owner_id]) }}">
                                {{$ownerName}}
                            </a></p>
                    </div>
                    <br>
                    <div class="d-flex justify-content-start gap-2">

                        <form method="POST" enctype="multipart/form-data" id="stripe" action="{{ route('stripe', ['service' => $service]) }}">
                        @csrf
                            <button type="submit" class="btn btn-outline-primary" id="submit">Buy</button>

                        </form>

                        <form method="GET" enctype="multipart/form-data" id="chat" action="{{ url('/chat/'.$service->owner_id) }}">
                        @csrf
                            <button type="submit" class="btn btn-outline-success" id="submit">Message</button>

                        </form>
                        <!--
                        <form method="GET" enctype="multipart/form-data" id="chat" action="{{ url('/chat/'.$service->owner_id) }}">
                        @csrf
                            <button type="submit" class="btn btn-outline-danger" id="submit">Report</button>

                        </form>
-->
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection