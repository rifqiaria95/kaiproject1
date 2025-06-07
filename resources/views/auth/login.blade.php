@extends('layouts.auth.app')
@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6">
            <!-- Register Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-6">
                        <a href="index.html" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <img src="{{ url('assets/img/branding/logo.png') }}" alt="Logo" width="40">
                            </span>
                            <span class="app-brand-text demo menu-text fw-bold">Kainnova</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1">Welcome to Kainnova Digital Solutions</h4>
                    <p class="mb-6">Please login to continue</p>

                    <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="email" class="form-label">Email</label>
                            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>

                        <div class="my-8">
                            <div class="form-check mb-0 ms-2">
                                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
                                <label class="form-check-label" for="terms-conditions">
                                    I agree to
                                    <a href="javascript:void(0);">privacy policy & terms</a>
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100">Sign in</button>
                    </form>

                    <p class="text-center">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </p>

                    <div class="divider my-6">
                        <div class="divider-text">or</div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-facebook me-1_5">
                            <i class="tf-icons ti ti-brand-facebook-filled"></i>
                        </a>

                        <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-twitter me-1_5">
                            <i class="tf-icons ti ti-brand-twitter-filled"></i>
                        </a>

                        <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-github me-1_5">
                            <i class="tf-icons ti ti-brand-github-filled"></i>
                        </a>

                        <a href="javascript:;" class="btn btn-sm btn-icon rounded-pill btn-text-google-plus">
                            <i class="tf-icons ti ti-brand-google-filled"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Register Card -->
        </div>
    </div>
</div>
@endsection
