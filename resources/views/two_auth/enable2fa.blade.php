@extends('layouts.app')
@section('title')
    {{ __('messages.two_factor_auth.enable_two_factor') }}
@endsection

@section('content')
    <div class="container-fluid">
        @include('layouts.errors')
        @include('flash::message')

        <div class="card">
            <div class="d-flex justify-content-center card-body">
                <div class="">
                    <h2>{{ __('messages.two_factor_auth.enable_two_factor') }}</h2>

                    <p id="setup-text" style="display: none;">{{ __('messages.two_factor_auth.scan_qr_code') }} : <strong
                            id="secret-code"></strong>
                    </p>

                    <p id="loading-text">
                        <i class="fa fa-spinner fa-spin"></i> {{ __('messages.two_factor_auth.loading') }}
                    </p>

                    <div class="qr-code-box d-flex justify-content-center align-items-center">
                        <div id="generateQrCodeImg" class="text-center"></div>
                    </div>

                    <form method="POST" action="{{ route('2fa.enable') }}" id="enable-form" class="mt-4"
                        style="display:none;">
                        @csrf

                        <div class="form-group">
                            {{ Form::label('otp', __('messages.two_factor_auth.enter_otp') . ':', ['class' => 'form-label']) }}
                            <span class="required"></span>
                            {{ Form::number('otp', null, ['class' => 'form-control', 'required', 'maxlength' => 6, 'minlength' => 6, 'id' => 'verification_code', 'placeholder' => __('messages.two_factor_auth.enter_otp')]) }}
                        </div>

                        <button class="btn btn-primary mt-2" type="submit">
                            {{ __('messages.two_factor_auth.verify_and_enable') }}
                        </button>
                    </form>

                    <div id="recovery-section" style="display:none;" class="mt-4">
                        <h4>{{ __('messages.two_factor_auth.recovery_codes') }}</h4>

                        <p class="text-danger">
                            {{ __('messages.two_factor_auth.recovery_codes_warning') }}
                        </p>

                        <ul id="recovery-list" class="list-group mb-3"></ul>

                        <button class="btn btn-success" id="download-btn">
                            {{ __('messages.two_factor_auth.download_codes') }}
                        </button>

                        <button class="btn btn-warning" id="regenerate-btn">
                            {{ __('messages.two_factor_auth.regenerate_codes') }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            let recoveryCodes = [];
            $('#loading-text').show();
            $.ajax({
                url: '{{ route('2fa.generate') }}',
                type: 'POST',
                success: function(resultult) {
                    let qrImage = resultult.data.qrImage;
                    $("#generateQrCodeImg").html(qrImage);
                    setTimeout(function() {
                        $('#loading-text').hide();
                        $('#setup-text').fadeIn();
                        $('#enable-form').fadeIn();
                    }, 300);

                    $('#secret-code').text(resultult.data.secret);
                },
                error: function() {
                    $('#loading-text').html(
                        '<span class="text-danger">{{ __('messages.two_factor_auth.qr_error') }}</span>'
                    );
                }
            });

            $('#enable-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('2fa.enable') }}',
                    type: 'POST',
                    data: $(this).serialize(),

                    success: function(result) {
                        if (!result.success) {
                            displayErrorMessage(result.message);
                            $('#enable-form')[0].reset();
                            return;
                        }
                        recoveryCodes = result.codes;
                        showRecoveryCodes(recoveryCodes);

                        $('#enable-form').hide();
                        $('#setup-text').hide();
                        $('#generateQrCodeImg').hide();
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                        $('#enable-form')[0].reset();
                    }
                });
            });

            function showRecoveryCodes(codes) {
                let html = '';

                codes.forEach(code => {
                    html += `<li>${code}</li>`;
                });

                $('#recovery-list').html(html);
                $('#recovery-section').fadeIn();
            }

            $('#download-btn').click(function() {
                let content = "{{ __('messages.two_factor_auth.recovery_codes') }}:\n\n" + recoveryCodes
                    .join("\n");
                let blob = new Blob([content], {
                    type: 'text/plain'
                });
                let url = window.URL.createObjectURL(blob);

                let a = document.createElement('a');
                a.href = url;
                a.download = 'recovery-codes.txt';
                a.click();

                window.URL.revokeObjectURL(url);
            });

            $('#regenerate-btn').click(function() {
                $.ajax({
                    url: '{{ route('2fa.regenerate') }}',
                    type: 'POST',
                    success: function(result) {
                        recoveryCodes = result.codes;
                        showRecoveryCodes(recoveryCodes);
                        displaySuccessMessage(result.message);
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    }
                });

            });

        });
    </script>
@endsection
