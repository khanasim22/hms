@extends('layouts.app')
@section('title')
    {{ __('Enable Two-Factor Authentication') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('layouts.errors')
        @include('flash::message')
        <div class="card">
            <div class="row justify-content-center card-body">
                <div class="col-lg-4 col-xl-5 col-md-6 col-sm-12">
                    <h2>{{ __('messages.two_factor_auth.disable_two_factor') }}</h2>
                    <p>{{ __('messages.two_factor_auth.two_factor_protected') }}</p>
                    <hr>
                    <form method="POST" action="{{ route('2fa.disable') }}">
                        @csrf
                        <button class="btn btn-danger mt-3">
                            {{ __('messages.two_factor_auth.disable_2fa') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
