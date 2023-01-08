@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Become Service Provider') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('user/update/'.Auth::user()->id) }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="area" class="col-md-4 col-form-label text-md-end">{{ __('Service Area') }}</label>


                            <div class="col-md-6">
                                <select class="form-select" aria-label="Default select example" id="area"  name="area">
                                    @foreach ($areas as $area)
                                    <option value="{{$area->name}}">{{$area->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <input id="is_provider" hidden type="number" class="form-control" name="is_provider" value="1" required>

                        <div class="row mb-3">
                            <label for="exprience" class="col-md-4 col-form-label text-md-end">{{ __('Exprience') }}</label>


                            <div class="col-md-6">
                                <select class="form-select" aria-label="Default select example" id="exprience"  name="exprience">
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Year</option>
                                    <option value="3">3 Year</option>
                                    <option value="4">4 Year</option>
                                    <option value="5">5+ Year</option>
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <label for="about" class="col-md-4 col-form-label text-md-end">{{ __('About Yourself') }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <textarea required class="form-control @error('about') is-invalid @enderror" id="about"  name="about"></textarea>
                                </div>

                                @error('about')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Start Now ') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection