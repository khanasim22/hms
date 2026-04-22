<div id="complaint_response_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">
                    {{ __('messages.complaints.response') }}
                </h3>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            {{ Form::open(['id' => 'complaintResponseForm']) }}

            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="complaintResponseErrorsBox"></div>

                {{-- Hidden complaint id --}}
                {{ Form::hidden('complaint_id', null, ['id' => 'responseComplaintId']) }}

                {{-- Hidden selected status --}}
                {{ Form::hidden('status', null, ['id' => 'responseComplaintStatus']) }}

                <div class="row">

                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('response', __('messages.complaints.response') . ':', ['class' => 'form-label']) }}
                        <span class="text-danger">*</span>

                        {{ Form::textarea('response', null, [
                            'class' => 'form-control',
                            'id' => 'complaintResponse',
                            'rows' => 4,
                            'required',
                            'placeholder' => __('messages.complaints.response')
                        ]) }}
                    </div>

                </div>
            </div>

            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), [
                    'type' => 'submit',
                    'class' => 'btn btn-primary m-0',
                    'id' => 'saveComplaintResponse',
                    'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."
                ]) }}

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    {{ __('messages.common.cancel') }}
                </button>
            </div>

            {{ Form::close() }}

        </div>
    </div>
</div>