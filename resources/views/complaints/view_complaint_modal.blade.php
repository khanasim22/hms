<!-- View Complaint Modal -->
<div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('messages.complaint')}} {{ __('messages.common.detail')}}:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="fw-bold">{{ __('messages.complaints.title')}}:</label>
                        <p id="viewComplaintTitle" class="mb-0"></p>
                    </div>
    
                    {{-- Patient Name only for Admin / Receptionist --}}
                    @role('Admin|Receptionist')
                        <div class="mb-3 col-md-6" id="patientNameSection">
                            <label class="fw-bold">{{ __('messages.death_report.patient_name')}}:</label>
                            <p id="viewPatientName" class="mb-0"></p>
                        </div>
                    @endrole
                </div>
                

                <div class="mb-3">
                    <label class="fw-bold">{{ __('messages.complaints.description') }}:</label>
                    <p id="viewComplaintDescription" class="mb-0"></p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">{{ __('messages.complaints.response') }}:</label>
                    <p id="viewFullComplaintResponse" class="mb-0"></p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">{{ __('messages.notice_board.created_at') }}:</label>
                    <p id="viewComplaintCreatedAt" class="mb-0"></p>
                </div>

            </div>
        </div>
    </div>
</div>