@extends('layouts.app')

<!-- This view for creating new service. -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Become Service Provider') }}</div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="upload-image" action="{{ route('create.service') }}">
                        @csrf
                        <div class="row">

                            <div class="row mb-3">
                                <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Title') }}</label>

                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus>
                                    @error('title')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Price(€)') }}</label>

                                <div class="col-md-6">
                                    <input id="price" type="number" class="form-control " name="price" value="{{ old('price') }}" required autocomplete="price" autofocus>
                                </div>
                            </div>

                            <input hidden id="owner" type="text" class="form-control " name="owner" value="{{ Auth::user()->firstName . ' ' .  Auth::user()->lastName }}" required>

                            <input hidden id="owner_id" type="text" class="form-control " name="owner_id" value="{{ Auth::user()->id }}" required>

                            <input hidden id="area" type="text" class="form-control " name="area" value="{{ Auth::user()->area }}" required>

                            <div class="row mb-3">
                                <label for="detail" class="col-md-4 col-form-label text-md-end">{{ __('About Your Service') }}</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <textarea required class="form-control @error('detail') is-invalid @enderror" id="detail" name="detail"></textarea>
                                        @error('detail')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="image" class="col-md-4 col-form-label text-md-end">{{ __('Photo') }}</label>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="file" name="image" placeholder="Choose image" id="image" required>
                                        @error('image')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="" class="col-md-4 col-form-label text-md-end"></label>

                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary" id="submit">Start Your Service</button>
                                </div>
                            </div>




                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection