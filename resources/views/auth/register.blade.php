@extends('layouts.auth_app')

@php
    App::setLocale(session('languageName'));
@endphp
@section('title')
    {{ __('web.register') }}
@endsection
@section('content')
    <!--begin::Authentication - Sign-up -->
    @php
        $style = 'style=background-image:url(' . asset('assets/img/progress-hd.png') . ')';
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

    <div class="d-flex flex-column flex-column-fluid align-items-center justify-content-center p-4">
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
            <h1 class="text-center mb-7">{{ __('auth.registration.patient_registration') }}</h1>
            <form method="POST" action="{{ url('/register') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-sm-7 mb-4">
                        <label for="formInputFirstName" class="form-label">
                            {{ __('messages.profile.first_name') . ':' }}<span class="required"></span>
                        </label>
                        <input name="first_name" type="text" class="form-control" id="first_name"
                            placeholder=" {{ __('messages.profile.first_name') }}" aria-describedby="firstName"
                            value="{{ old('first_name') }}"
                            onkeypress='if (/\s/g.test(this.value)) this.value = this.value.replace(/\s/g,"")' required>
                    </div>
                    <div class="col-md-6 mb-sm-7 mb-4">
                        <label for="last_name" class="form-label">
                            {{ __('messages.profile.last_name') . ':' }}<span class="required"></span>
                        </label>
                        <input name="last_name" type="text" class="form-control" id="last_name"
                            placeholder=" {{ __('messages.profile.last_name') }}" aria-describedby="lastName"
                            onkeypress='if (/\s/g.test(this.value)) this.value = this.value.replace(/\s/g,"")' required
                            value="{{ old('last_name') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-sm-7 mb-4">
                        <label for="email" class="form-label">
                            {{ __('auth.email') . ':' }}<span class="required"></span>
                        </label>
                        <input name="email" type="email" class="form-control" id="email" aria-describedby="email"
                            placeholder=" {{ __('auth.email') }}" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6 mb-sm-7 mb-4">
                        {{ Form::label('phone', __('messages.user.phone') . ': ', ['class' => 'form-label required']) }}
                        <br>
                        {{ Form::tel('phone', getCountryCode() ?? old('phone'), ['class' => 'form-control phoneNumber', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")']) }}
                        {{ Form::hidden('prefix_code', null, ['class' => 'prefix_code']) }}
                        <span class="text-success valid-msg d-none fw-400 fs-small mt-2">✓ &nbsp;
                            {{ __('messages.valid') }}</span>
                        <span class="text-danger error-msg d-none fw-400 fs-small mt-2"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-sm-7 mb-4">
                        <label for="password" class="form-label">
                            {{ __('auth.password') . ':' }}<span class="required"></span>
                        </label>
                        <div class="mb-3 position-relative">
                            <input type="password" name="password" class="form-control" id="password"
                                onkeypress='if (/\s/g.test(this.value)) this.value = this.value.replace(/\s/g,"")'
                                placeholder=" {{ __('auth.password') }}" aria-describedby="password" required
                                aria-label="Password" data-toggle="password">
                        </div>
                    </div>
                    <div class="col-md-6 mb-sm-7 mb-4">
                        <label for="password_confirmation" class="form-label">
                            {{ __('auth.confirm_password') . ':' }}<span class="required"></span>
                        </label>
                        <div class="mb-3 position-relative">
                            <input name="password_confirmation" type="password" class="form-control"
                                placeholder=" {{ __('auth.confirm_password') }}" id="password_confirmation"
                                aria-describedby="confirmPassword" required aria-label="Password"
                                onkeypress='if (/\s/g.test(this.value)) this.value = this.value.replace(/\s/g,"")'
                                data-toggle="password">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-sm-7 mb-4">
                    <div class="form-group mb-5">
                        <label class="form-label">{{ __('messages.user.gender') }}
                            <span class="required"></span> &nbsp;<br>
                        </label>
                        <br>
                        <div class="d-flex align-items-center">

                            <div class="form-check me-5">
                                <input class="form-check-input" type="radio" name="gender" value="0" checked
                                    id="male" />
                                <label class="form-check-label" for="male">
                                    {{ __('messages.user.male') }}
                                </label>
                            </div>

                            <div class="form-check me-10">
                                <input class="form-check-input" type="radio" name="gender" value="1"
                                    id="female" />
                                <label class="form-check-label" for="female">
                                    {{ __('messages.user.female') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">{{ __('auth.submit') }}</button>
                </div>

                <div class="d-flex align-items-center mt-4">
                    <span class="text-gray-700 me-2">{{ __('auth.already_user') }}</span>
                    <a href="{{ route('login') }}" class="link-info fs-6 text-decoration-none">
                        {{ __('auth.sign_in') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            if (!$('.phoneNumber').length) {
                return false
            }

            let input = document.querySelector('.phoneNumber'),
                errorMsg = document.querySelector('.error-msg'),
                validMsg = document.querySelector('.valid-msg')

            let errorMap = [
                $('.invalidNumber').val(),
                $('.invalidCountryNumber').val(),
                $('.tooShort').val(),
                $('.tooLong').val(),
                $('.invalidNumber').val(),
            ]

            // initialise plugin
            let intl = window.intlTelInput(input, {
                initialCountry: 'auto',
                separateDialCode: true,
                geoIpLookup: function(success, failure) {
                    $.get('https://ipinfo.io', function() {}, 'jsonp').always(function(resp) {
                        var countryCode = (resp && resp.country) ?
                            resp.country :
                            'in'
                        success(countryCode)
                    })
                },
                utilsScript: $('.utilsScript').val(),
            })

            let reset = function() {
                input.classList.remove('error')
                errorMsg.innerHTML = ''
                errorMsg.classList.add('d-none')
                validMsg.classList.add('d-none')
            }

            input.addEventListener('blur', function() {
                reset()
                if (input.value.trim()) {
                    if (intl.isValidNumber()) {
                        validMsg.classList.remove('d-none')
                    } else {
                        input.classList.add('error')
                        var errorCode = intl.getValidationError()
                        errorMsg.innerHTML = errorMap[errorCode] || $('.invalidNumber').val()
                        errorMsg.classList.remove('d-none')
                    }
                }
            })

            // on keyup / change flag: reset
            input.addEventListener('change', reset)
            input.addEventListener('keyup', reset)
            const phoneNo = $('.phoneNo').val()
            if (typeof phoneNo != 'undefined' && phoneNo !== '') {
                setTimeout(function() {
                    $('.phoneNumber').trigger('change')
                }, 500)
            }

            $(document).on('blur keyup change countrychange', function() {
                if (typeof phoneNo != 'undefined' && phoneNo !== '') {
                    intl.setNumber('+' + phoneNo)
                    phoneNo = ''
                }
                let getCode = intl.selectedCountryData['dialCode']
                $('.prefix_code').val(getCode)
            })

            if ($('.isEdit').val()) {
                let getCode = intl.selectedCountryData['dialCode']
                $('.prefix_code').val(getCode)
            }

            $('.phoneNumber').focus()
            $('.phoneNumber').blur()
            let getPhoneNumber = $('.phoneNumber').val()
            let removeSpacePhoneNumber = getPhoneNumber.replace(/\s/g, '')
            $('.phoneNumber').val(removeSpacePhoneNumber)
        });
    </script>
@endsection
