@extends('layouts.app')

<!-- This view for showing the auth user. Also ff he/she is service provider, it shows details. -->

@section('content')
<section style="background-color: #eee;">
    <div class="container py-5">

    @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Error:</strong>  {{ $errors->first() }} 
                </div>
              
                @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <a href="#icon"  data-bs-toggle="modal"><img src=" @if (Auth::user()->avatar){{asset('storage/users-avatar/'. 
                        Auth::user()->avatar)}} @else {{asset('storage/users-avatar/avatar.png')}} @endif" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;"></a>

                        <h5 class="my-3">{{Auth::user()->name}}</h5>
                        @if (Auth::user()->is_provider)
                        <p class="text-muted mb-3">{{Auth::user()->area}}</p>
                        @endif

                        <form method="GET" enctype="multipart/form-data" id="deleteUser" action="{{ url('edit/user')}}">
                            @csrf
                            <button type="submit" class="btn btn-outline-success mb-3" id="submit">Edit Profile</button>

                        </form>

                        @if (!Auth::user()->is_provider)
                        <form method="POST" enctype="multipart/form-data" id="becomeProvider" action="{{ route('becomeProvider') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-success mb-3" id="submit">Become Service Provider</button>

                        </form>
                        @endif

                        <form method="POST" enctype="multipart/form-data" id="deleteUser" action="{{ route('destroy.user',['user' => Auth::user()]) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger " id="submit">Delete Your Profile</button>

                        </form>

                    </div>
                </div>
                @if (Auth::user()->is_provider)
                <div class="card mb-4 mb-lg-0">
                    <div class="card-body text-center">
                        @if (!Auth::user()->completed_stripe_onboarding)
                        <div class="col">
                            <p class="text-danger"> Not Connected </p>
                            <p class="text-muted">After it's connected then you can create and see your services. </p>

                            <form method="POST" enctype="multipart/form-data" id="startNewService" action="{{ route('redirect.stripe',['id' => Auth::user()->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-warning" id="submit">Connect Stripe Account</button>
                            </form>
                        </div>

                        @else

                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0"><strong>Stripe Id</strong></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{Auth::user()->stripe_connect_id}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0"><strong>Balance</strong></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">€ {{$balance}}</p>
                            </div>
                        </div>
                        <hr>
                        <p class="text-success"> Connected </p>
                        <form method="POST" enctype="multipart/form-data" id="startNewService" action="{{ route('redirect.stripe', ['id' => Auth::user()->id]) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary" id="submit">View Stripe Account</button>
                        </form>


                        @endif

                    </div>
                </div>
                @endif
            </div>


            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{Auth::user()->name}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{Auth::user()->email}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Country</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{Auth::user()->country}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Address</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{Auth::user()->address}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Phone</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{Auth::user()->phone}}</p>
                            </div>
                        </div>
                        @if (Auth::user()->is_provider)
                        <hr>

                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">About</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{Auth::user()->about}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Exprience</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{Auth::user()->exprience}} Year</p>
                            </div>
                        </div>
                        @if (Auth::user()->completed_stripe_onboarding)
                        <hr>
                        <div class="row text-center">
                            <div class="col-sm-6">
                                <form method="POST" enctype="multipart/form-data" id="createService" action="{{ route('create.service') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary" id="submit">Start New Service</button>

                                </form>
                            </div>
                            <div class="col-sm-6">
                                <form method="POST" enctype="multipart/form-data" id="showServices" action="{{ route('show.ownedServices',['user' => Auth::user()]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary" id="submit">See Your Services</button>

                                </form>
                            </div>
                        </div>

                        @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>
</section>
@include('partials.iconUpload')
@endsection