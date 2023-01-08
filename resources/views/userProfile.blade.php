@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>

                

                <div class="card-body">
                    {{ __('First Name:  ') }} {{ $user->firstName }}

                </div>

                <div class="card-body">
                    {{ __('Last Name: ') }} {{ $user->lastName }}

                </div>

                <div class="card-body">
                    {{ __('Email: ') }} {{ $user->email }}

                </div>

                @if ($user->is_provider)

                <div class="card-body">
                    {{ __('Area: ') }} {{ $user->area }}

                </div>

                <div class="card-body">
                    {{ __('Exrience: ') }} {{ $user->exprience }} Year

                </div>

                <div class="card-body">
                    {{ __('About Yourself: ') }} {{ $user->about }}

                </div>


                @endif



            </div>
        </div>
    </div>
</div>
@endsection