@role('Admin|Receptionist')
    <div class="d-flex align-items-center">
        @if ($row->status == 2)
            <span class="badge bg-light-success my-3">{{ __('messages.complaints.resolved') }}</span>
        @elseif ($row->status == 3)
            <span class="badge bg-light-danger my-3">{{ __('messages.complaints.rejected') }}</span>
        @else
            <select class="form-select complaint-status-change" data-id="{{ $row->id }}" style="min-width: 160px;">
                @if ($row->status == 0)
                    <option value="0" {{ $row->status == 0 ? 'selected' : '' }}>
                        {{ __('messages.complaints.pending') }}
                    </option>
                @endif
                <option value="1" {{ $row->status == 1 ? 'selected' : '' }}>
                    {{ __('messages.complaints.in_progress') }}
                </option>
                <option value="4" {{ $row->status == 4 ? 'selected' : '' }}>
                    {{ __('messages.complaints.hold') }}
                </option>
                <option value="2" {{ $row->status == 2 ? 'selected' : '' }}>
                    {{ __('messages.complaints.resolved') }}
                </option>
                <option value="3" {{ $row->status == 3 ? 'selected' : '' }}>
                    {{ __('messages.complaints.rejected') }}
                </option>
                
            </select>
        @endif
    </div>
@endrole
@role('Patient')
    @if($row->status == 0)
        <span class="badge bg-light-info">{{ __('messages.complaints.pending') }}</span>
    @elseif ($row->status == 1)
        <span class="badge bg-light-primary">{{ __('messages.complaints.in_progress') }}</span>
    @elseif ($row->status == 2)
        <span class="badge bg-light-success">{{ __('messages.complaints.resolved') }}</span>
    @elseif ($row->status == 3)
        <span class="badge bg-light-danger">{{ __('messages.complaints.rejected') }}</span>
    @elseif ($row->status == 4)
        <span class="badge bg-light-warning">{{ __('messages.complaints.hold') }}</span>
    @endif
@endrole