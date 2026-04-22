<div id="upload_video" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">{{ __('messages.queue.upload_video') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::hidden('uploadThemeVideoSaveUrl', route('settings.upload-theme-video'), ['class' => 'settingUploadThemeVideoUrl', 'id' => 'settingUploadThemeVideoUrl']) }}
            {{ Form::open(['id' => 'uploadThemeVideo', 'files' => true, 'class' => 'uploadThemeVideoForm']) }}
            <div class="modal-body pb-0">
                <div class="alert alert-danger d-none hide" id=""></div>
                <div class="row">
                    <div class="form-group mb-5">
                        {{ Form::label('file', __('messages.queue.video') . ':', ['class' => 'form-label required']) }}
                        <br>
                        <div class="d-block mb-2">
                            <?php
                            $style = 'style=';
                            $background = 'background-image:';
                            ?>

                            <div class="image-picker">
                                <div class="image previewImage" {{ $style }}"{{ $background }}
                                    url({{ asset('assets/img/video.png') }}">
                                    <span class="picker-edit rounded-circle text-gray-500 fs-small"
                                        title="{{ __('messages.queue.video') }}">
                                        <label>
                                            <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                            {{ Form::file('patient_queue_theme_video', ['id' => 'themeVideoInput', 'class' => 'd-none image-upload profileImage', 'accept' => '.mp4,.webm,.mov', 'required']) }}
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div id="videoFileName" class="mt-2 d-none">
                            <small class="text-success"><i class="fa-solid fa-check-circle"></i> <span id="selectedVideoName"></span></small>
                        </div>
                        <span>{{ __('messages.queue.allowed_video_types') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'uploadThemeVideoSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
