@extends('layouts.app')

@section('content')
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="{{ asset('images/other/login.png') }}" class="img-fluid" alt="Login">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-center pb-4">
                        <img src="{{ asset('images/other/cdo-seal.png') }}" alt="cdo-seal" height="140px;">
                        <img src="{{ asset('images/other/risev2.png') }}" alt="cdo-rise" height="140px;">
                    </div>

                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-center">
                        <p class="lead fw-normal mb-0 me-3 text-center" style="color: #0d3858; font-family: 'Oswald'; font-size: 85px; line-height: 82px; text-shadow: 2px 2px 8px #69829f;">
                            <span style="font-size: 120px;">DOCUMENT</span>
                            TRACKING SYSTEM
                        </p>
                    </div>

                    <div class="divider d-flex align-items-center my-4">

                    </div>

                    <!-- Email input -->
                    @if (session('error'))
                    <div class="mb-2">
                        <span class="text-danger">
                            {{ session('error') }}
                        </span>
                    </div>
                    @endif

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Enter a valid email address" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                        <label class="form-label" for="email">Email address</label>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Password input -->
                    <div data-mdb-input-init class="form-outline mb-3">
                        <input type="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Enter password" name="password" required autocomplete="current-password" />
                        <label class="form-label" for="password">Password</label>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem; background-color: #0d3858;">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection