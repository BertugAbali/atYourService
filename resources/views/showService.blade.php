@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <img src="{{asset('storage/images/'.$service->path)}}" alt="{{$service->name}}" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">{{$service->title}}</h5>
                    <p class="card-text">{{$service->detail}}</p>
                    <form method="POST" enctype="multipart/form-data" id="stripe" action="{{ route('stripe', ['service' => $service]) }}">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" id="submit">Buy This Service</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection