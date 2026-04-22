<div id="edit_complaint_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    {{ __('messages.complaints.edit_complaint') }}
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            {{ Form::open(['id' => 'editComplaintForm']) }}

            <div class="modal-body">
                <div class="alert alert-danger d-none hide" id="editComplaintErrorsBox"></div>

                {{ Form::hidden('complaint_id', null, ['id' => 'complaintId']) }}

                <div class="row">

                    {{-- Title --}}
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('title', __('messages.complaints.title') . ':', ['class' => 'form-label']) }}
                        <span class="required">*</span>
                        {{ Form::text('title', null, [
                            'id' => 'editComplaintTitle',
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('messages.complaints.title')
                        ]) }}
                    </div>

                    {{-- Description --}}
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('description', __('messages.complaints.description') . ':', ['class' => 'form-label']) }}
                        <span class="required">*</span>
                        {{ Form::textarea('description', null, [
                            'id' => 'editComplaintDescription',
                            'class' => 'form-control',
                            'rows' => 4,
                            'required',
                            'placeholder' => __('messages.complaints.description')
                        ]) }}
                    </div>


                </div>
            </div>

            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), [
                    'type' => 'submit',
                    'class' => 'btn btn-primary m-0',
                    'id' => 'editComplaintSave',
                    'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."
                ]) }}

                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    {{ __('messages.common.cancel') }}
                </button>
            </div>

            {{ Form::close() }}
        </div>
    </div>
</div>