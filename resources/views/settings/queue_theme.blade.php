@extends('layouts.app')
@section('title')
    {{ __('messages.queue.patient_queue_theme') }}
@endsection
<style>
    .theme-img-radio {
        cursor: pointer;
        display: block;
    }

    .img-border {
        border: 3px solid #0b9ef7 !important;
        border-radius: 10px !important;
        position: relative;
    }
</style>

@section('content')
    @include('settings.upload-theme-video')
    {{-- queue-theme-update{} --}}

    {{ Form::open(['class' => 'patientQueueThemeForm', 'id' => 'patientQueueThemeForm']) }}
    <div class="container-fluid">
        @include('flash::message')
        <div class="card">
            <div class="d-flex justify-content-end pt-6 pe-10">
                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#upload_video">{{ __('messages.queue.upload_video') }}</a>
            </div>
            <div class="card-body">
                <div class="row ">
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-7">
                            <label
                                class="theme-img-radio {{ ($setting['patient_queue_theme']['value'] ?? 0) == 0 ? 'img-border' : '' }}">
                                <input type="radio" name="patient_queue_theme" value="0" class="d-none"
                                    {{ ($setting['patient_queue_theme']['value'] ?? 0) == 0 ? 'checked' : '' }}>

                                <img src="{{ asset('images/patient-queue-theme/normal-theme.png') }}" alt="Template"
                                    class="img-thumbnail p-0">
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-7">
                            <label
                                class="theme-img-radio {{ ($setting['patient_queue_theme']['value'] ?? 0) == 1 ? 'img-border' : '' }}">
                                <input type="radio" name="patient_queue_theme" value="1" class="d-none"
                                    {{ ($setting['patient_queue_theme']['value'] ?? 0) == 1 ? 'checked' : '' }}>

                                <img src="{{ asset('images/patient-queue-theme/video-theme.png') }}" alt="Template"
                                    class="img-thumbnail p-0">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mt-2 d-flex">
                    {{-- <button class="btn btn-primary {{ auth()->user()->language == 'ar' ? 'ms-3' : 'me-3' }} ">
                        {{ __('messages.common.save') }}
                    </button> --}}

                    {{ Form::button(__('messages.common.save'), [
                        'type' => 'submit',
                        'class' => 'btn btn-primary ' . (auth()->user()->language == 'ar' ? 'ms-3' : 'me-3'),
                        'id' => 'patientQueueThemeSave',
                        'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...",
                    ]) }}
                </div>
            </div>

        </div>
    </div>
    {{ Form::close() }}
@endsection
