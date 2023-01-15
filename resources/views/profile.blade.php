@extends('layouts.app')

<!-- This view for showing the auth user. Also ff he/she is service provider, it shows details. -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>


                <div class="card-body">
                    {{ __('First Name:  ') }} {{ Auth::user()->firstName }}

                </div>

                <div class="card-body">
                    {{ __('Last Name: ') }} {{ Auth::user()->lastName }}

                </div>

                <div class="card-body">
                    {{ __('Email: ') }} {{ Auth::user()->email }}

                </div>

                @if ($errors->any())
                    <h4 class="bg-red-50 text-red-800 py-2 px-2 rounded-md shadow-md mb-2"> {{ $errors->first() }} </h4>
                @endif

                

                <img src=" @if (Auth::user()->avatar){{ Auth::user()->avatar }} @else {{asset('storage/images/logo/empty-logo.webp')}} @endif" alt="Profile Image" class="img-fluid rounded-circle w-25 h-25">

                @if (Auth::user()->is_provider)

                @if (!Auth::user()->completed_stripe_onboarding)
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="startNewService" action="{{ route('redirect.stripe',['id' => Auth::user()->id]) }}">
                        @csrf
                        <div class="row">

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-warning" id="submit">Connect Stripe Account</button>
                            </div>
                            <div class="col-md-6">
                                <p> Not Connected </p>
                            </div>
                        </div>
                    </form>
                </div>

                @else
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="startNewService" action="{{ route('redirect.stripe', ['id' => Auth::user()->id]) }}">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" id="submit">View Stripe Account</button>
                            </div>
                            <div class="col-md-3">
                                <p> Connected </p>
                            </div>
                            <div class="col-md-3">
                                <p> € {{$balance}} </p>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

                <div class="card-body">
                    {{ __('Area: ') }} {{ Auth::user()->area }}

                </div>

                <div class="card-body">
                    {{ __('Exrience: ') }} {{ Auth::user()->exprience }} Year

                </div>

                <div class="card-body">
                    {{ __('About Yourself: ') }} {{ Auth::user()->about }}

                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="startNewService" action="{{ route('startNewService') }}">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" id="submit">Start New Service</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="showServices" action="{{ route('show.ownedServices',['user' => Auth::user()]) }}">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" id="submit">See Your Services</button>
                            </div>
                        </div>
                    </form>
                </div>

                @else

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="becomeProvider" action="{{ route('becomeProvider') }}">
                        @csrf
                        <div class="row">

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="submit">Become Service Provider</button>
                            </div>
                        </div>
                    </form>

                </div>

                @endif

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="deleteUser" action="{{ route('destroy.user',['user' => Auth::user()]) }}">
                        @csrf
                        <div class="row">

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-danger" id="submit">Delete Your Profile</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection