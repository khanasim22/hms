'use strict'
listenSubmit('#addComplaintForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#complaintSave');
    loadingButton.button('loading');
    $.ajax({
        url: route('complaints.store'),
        type: 'POST',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#add_complaint_modal').modal('hide');
                Livewire.dispatch('refresh');
            }
        },
        error: function (result) {
            printErrorMessage('#complaintErrorsBox', result);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });

});

function renderComplaintData(id) {
    $.ajax({
        url: route('complaints.edit', id),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let complaint = result.data;
                $('#complaintId').val(complaint.id);
                $('#editComplaintTitle').val(complaint.title);
                $('#editComplaintDescription').val(complaint.description);
                $('#edit_complaint_modal').modal('show');
                ajaxCallCompleted();
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        },
    });

}
listen('click', '.complaint-edit-btn', function (event) {
    if ($('.ajaxCallIsRunning').val()) {
        return;
    }
    ajaxCallInProgress();
    let complaintId = $(event.currentTarget).attr('data-id');
    renderComplaintData(complaintId);

});

listenSubmit('#editComplaintForm', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#editComplaintSave');
    loadingButton.button('loading');
    var id = $('#complaintId').val();
    console.log(id);
    
    $.ajax({
        url: route('complaints.update', id),
        type: 'put',
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                $('#edit_complaint_modal').modal('hide');
                Livewire.dispatch('refresh');
            }
        },
        error: function (result) {
            printErrorMessage('#complaintErrorsBox', result);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });

});

listen('click', '.complaint-delete-btn', function (event) {
    let complaintId = $(event.currentTarget).attr('data-id');
    let complaintTitle = $(event.currentTarget).data('title');
    deleteItem(
        // $('#complaintDeleteRoute').val().replace(':id', complaintId),
        route('complaints.destroy', complaintId),
        complaintTitle,
        complaintTitle
    );

});

listenHiddenBsModal('#add_complaint_modal', function () {
    resetModalForm('#addComplaintForm', '#complaintErrorsBox');
});

listenHiddenBsModal('#edit_complaint_modal', function () {
    resetModalForm('#editComplaintForm', '#editComplaintErrorsBox');
});


let originalStatus = null;
let activeStatusSelect = null;
let isStatusSubmitted = false;

$(document).on('focus', '.complaint-status-change', function () {
    originalStatus = $(this).val();
});

listenChange('.complaint-status-change', function () {
    let complaintId = $(this).data('id');
    let selectedStatus = $(this).val();

    activeStatusSelect = $(this);
    isStatusSubmitted = false;

    $.ajax({
        url: route('complaints.responseEdit', complaintId),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                let complaint = result.data;

                $('#responseComplaintId').val(complaint.id);
                $('#responseComplaintStatus').val(selectedStatus);
                $('#complaintResponse').val(complaint.response ?? '');

                $('#complaint_response_modal').modal('show');
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        }
    });
});

listenSubmit('#complaintResponseForm', function (event) {
    event.preventDefault();

    let form = $(this);

    $.ajax({
        url: route('complaints.update.status.response'),
        type: 'POST',
        data: form.serialize(),

        success: function (result) {
            if (result.success) {
                isStatusSubmitted = true;

                displaySuccessMessage(result.message);

                $('#complaint_response_modal').modal('hide');

                Livewire.dispatch('refresh');
            }
        },

        error: function (result) {
            manageAjaxErrors(result);
        }
    });
});

// If modal closes without submit -> restore original value
listenHiddenBsModal('#complaint_response_modal', function () {
    if (!isStatusSubmitted && activeStatusSelect) {
        activeStatusSelect.val(originalStatus).trigger('change.select2');
    }

    originalStatus = null;
    activeStatusSelect = null;
    isStatusSubmitted = false;
});


listenClick('.response-view-btn', function (event) {
    let complaintId = $(event.currentTarget).data('id');

    $.ajax({
        url: route('complaints.edit', complaintId),
        type: 'GET',


        success: function (result) {
            if (result.success) {
                let complaint = result.data;

                $('#viewComplaintResponse').text(
                    complaint.response
                        ? complaint.response
                        : 'No response available.'
                );

                $('#view_response_modal').modal('show');
            }
        },

        error: function (result) {
            manageAjaxErrors(result);
        }
    });

});

listenHiddenBsModal('#complaint_response_modal', function () {
    $('#complaintResponse').val('');
    $('#complaintResponse').prop('readonly', false);
    $('#saveComplaintResponse').show();

});


listenChange('#complaintsStatusArr', function () {
    let status = $(this).val();

    Livewire.dispatch('changeStatusFilter', {
        value: status
    });

});

listenClick('#complaintResetFilter', function () {
    $('#complaintsStatusArr').val('').trigger('change');

    Livewire.dispatch('changeStatusFilter', {
        value: ''
    });

});

listenClick('.view-full-complaint', function (event) {
    let complaintId = $(event.currentTarget).data('id');

    $.ajax({
        url: route('complaints.show', complaintId),
        type: 'GET',
        success: function (result) {
            if (result.success) {

                let data = result.data;
                
                $('#viewComplaintTitle').text(data.title);
                $('#viewComplaintDescription').text(data.description);
                $('#viewFullComplaintResponse').text(data.response);
                $('#viewComplaintCreatedAt').text(data.created_at);

                if ($('#viewPatientName').length) {
                    $('#viewPatientName').text(data.patient_name);
                }

                $('#viewComplaintModal').modal('show');
            }
        },
        error: function (result) {
            manageAjaxErrors(result);
        }
    });
});