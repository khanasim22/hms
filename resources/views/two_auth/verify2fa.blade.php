@extends('layouts.auth_app')
@section('title')
    {{ __('messages.two_factor_auth.verify_2fa') }}
@endsection

@section('content')

    @php
        $settingValue = getSettingValue();
        App::setLocale(checkLanguageSession());
    @endphp
    <ul class="nav nav-pills" style="justify-content: flex-end; cursor: pointer">
        <li class="nav-item dropdown">
            <a class="btn btn-primary w-150px mb-5 indicator m-3" data-bs-toggle="dropdown" href="javascript:void(0)"
                role="button" aria-expanded="false">{{ __('messages.language.' . getCurrentLanguageName()) }}</a>
            <ul class="dropdown-menu w-150px">
                @foreach (getLanguages() as $key => $value)
                    <li class="{{ checkLanguageSession() == $key ? 'active' : '' }}"><a
                            class="dropdown-item  px-5 language-select {{ checkLanguageSession() == $key ? 'bg-primary text-white' : 'text-dark' }}"
                            data-id="{{ $key }}">{{ $value }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
    <div class="d-flex flex-column flex-column-fluid align-items-center row justify-content-top p-4">
        <div class="col-12 text-center">
            <a href="{{ route('front') }}" class="image mb-7 mb-sm-10">
                <img alt="Logo" src="{{ $settingValue['app_logo']['value'] }}" class="img-fluid logo-fix-size">
            </a>
        </div>
        <div class="width-540">
            @include('flash::message')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="bg-white rounded-15 shadow-md width-540 px-5 px-sm-7 py-10 mx-auto">
            <h4 class="mb-3 text-center">{{ __('messages.two_factor_auth.verify_your_account') }}</h4>

            <form method="POST" action="{{ route('2fa.verify.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('messages.two_factor_auth.one_time_password') . ':' }}</label>
                    <input type="text" name="verification_code" class="form-control" required autofocus
                        autocomplete="off" placeholder="{{ __('messages.two_factor_auth.enter_verification_code') }}">
                </div>
                <button type="submit" class="btn btn-primary w-100">{{ __('messages.two_factor_auth.verify') }}</button>
            </form>

            <p class="mt-3 small text-muted text-center">
                {{ __('messages.two_factor_auth.code_not_found') }}
            </p>
        </div>
    </div>
@endsection
