<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateComplaint;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Flash;
use App\Http\Requests\UpdateComplaintRequest;

class ComplaintController extends AppBaseController
{
    //
    public function index()
    {
        return view('complaints.index');
    }

    public function store(CreateComplaint $request){
        $input = $request->all();

        Complaint::create([
            'patient_id'   => Auth::id(),
            'title'        => $input['title'],
            'description'  => $input['description'],
            'status'       => 0,
            'response'     => null,
            'resolved_by'  => null,
            'resolved_at'  => null,
        ]);

        return $this->sendSuccess(__('messages.complaints.complaint_submitted_successfully'));
    }

    public function edit(Complaint $complaint)
    {
        return $this->sendResponse($complaint, 'Complaint retrieved successfully.');
    }

    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {

        $complaint->update($request->only([
            'title',
            'description'
        ]));

        return $this->sendSuccess(
            __('messages.complaints.complaint_updated_successfully')
        );
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return $this->sendSuccess(
            __('messages.complaints.complaint_deleted_successfully')
        );
    }

    public function updateStatusResponse(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required',
            'status' => 'required',
            'response' => 'required|string',
        ]);

        $complaint = Complaint::findOrFail($request->complaint_id);

        $updateData = [
            'status' => $request->status,
            'response' => $request->response,
        ];

        if (in_array($request->status, [
            Complaint::STATUS_PENDING,
            Complaint::STATUS_RESOLVED,
            Complaint::STATUS_REJECT,
        ])) {
            $updateData['resolved_by'] = auth()->id();
            $updateData['resolved_at'] = now();
        }

        $complaint->update($updateData);

        return $this->sendSuccess(
            __('messages.complaints.complaint_updated_successfully')
        );
    }

    public function responseEdit(Complaint $complaint)
    {
        return $this->sendResponse($complaint, 'Complaint fetched successfully.');
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('patient');

        $data = [
            'title'        => $complaint->title,
            'description'  => $complaint->description,
            'response'     => $complaint->response ?? __('messages.complaints.no_response_available'),
            'created_at'   => $complaint->created_at->format('d M Y h:i A'),
            'patient_name' => optional($complaint->patient)->full_name,
        ];

        return $this->sendResponse(
            $data,
            'Complaint details fetched successfully.'
        );
    }
}
