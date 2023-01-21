@extends('layouts.app')

<!-- This view for showing the profile of chosed user. -->

@section('content')
<section style="background-color: #eee;">
    <div class="container py-5">


        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src=" @if (isset($user->avatar)){{asset('storage/users-avatar/'. $user->avatar)}} @else {{asset('storage/users-avatar/avatar.png')}} @endif" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="my-3">{{ $user->name}}</h5>
                        @if ( $user->is_provider)
                        <p class="text-muted mb-3">{{ $user->area}}</p>
                        @endif


                        <div class="d-flex justify-content-center mb-2">

                            <form method="GET" enctype="multipart/form-data" id="chat" action="{{ url('/chat/'. $user->id) }}">

                                <button type="submit" class="btn btn-outline-primary ms-1">Message</button>

                            </form>

                        </div>

                    </div>
                </div>
                </div>
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Full Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ $user->name}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ $user->email}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">About</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ $user->about??''}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Country</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ $user->country??''}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Phone</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ $user->phone??''}}</p>
                                </div>
                            </div>
                            @if ( $user->is_provider)
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Exprience</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{ $user->exprience}} Year</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
</section>


@endsection