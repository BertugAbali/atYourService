@extends('layouts.app')

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

                @if (Auth::user()->is_provider)

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



            </div>
        </div>
    </div>
</div>
@endsection