@extends('admin.layouts.simple')

@section('title', 'Login')

@push('head')
    <link rel="stylesheet" href="{{ url('assets/vendor/css/pages/page-auth.css') }}" />
@endpush

@section('content')
    <div class="container-xxl animate__animated animate__backInDown">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Login -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ url('/login') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{ url('assets/img/logo-zazara.png') }}?v={{ \Carbon\Carbon::now()->timestamp }}"
                                        alt="Logo" style="height:30px;" />
                                </span>
                                <span class="app-brand-text demo text-body fw-bolder text-uppercase">
                                    Zazara-ERP
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->

                        <h4 class="mb-1">Welcome to Zazara-ERP! 👋</h4>
                        <p class="mb-6">Please sign-in to your account and start the adventure</p>

                        <!-- Alert Message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <!-- /Alert Message -->

                        <form class="mb-3" action="{{ url('/login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Username</label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}"
                                    class="form-control" placeholder="Enter your username" autofocus />
                                @error('email')
                                    <div class="d-block invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                    {{-- <a href="auth-forgot-password-basic.html"><small>Forgot Password?</small></a> --}}
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <div class="d-block invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember-me" id="remember-me" />
                                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Login -->
            </div>
        </div>
    </div>
@endsection
