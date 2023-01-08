@foreach ($services as $service)
<div class="col-md-3">
    <div class="card h-100">
    <a href="{{ route('show.service', ['service' => $service]) }}">
            <img src="{{asset('storage/images/'.$service->path)}}" alt="{{$service->name}}" class="card-img-top">
        </a>
        <div class="card-body">
        <a href="{{ route('show.service', ['service' => $service]) }}">
                <h5 class="card-title">{{$service->title}}</h5>
                <p class="card-text">{{$service->detail}}</p>
            </a>
        </div>
        <div class="card-footer">
            <a href="{{ route('show.profile', ['id' => $service->owner_id]) }}">
                <small class="text-muted">{{$service->owner}}</small>
            </a>
        </div>

    </div>
</div>

@endforeach