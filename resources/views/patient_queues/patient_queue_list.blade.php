@php
    use App\Models\Appointment;
@endphp

@forelse ($patientQueue as $queue)
    @php
        $appointment = $queue->appointment;
        $isServing = $appointment?->is_completed === Appointment::STATUS_CHECK_IN;
        $status = $isServing ? 'serving' : 'waiting';
    @endphp

    <tr class="{{ $isServing ? 'serving-row' : '' }}" data-status="{{ $status }}">
        <td>
            <div class="queue-num {{ $isServing ? 'serving' : '' }}">{{ $queue->no }}</div>
        </td>
        <td class="patient-name">
            {{ $appointment?->patient?->patientUser?->full_name ?? '' }}
        </td>
        <td>
            <div class="doctor-name">{{ $appointment?->doctor?->doctorUser?->full_name ?? '' }}</div>
            <div class="specialty">{{ $appointment?->doctor?->department?->title ?? '' }}</div>
        </td>
        <td>
            <span class="badge {{ $status }}">
                <span class="badge-dot"></span>
                {{ $isServing ? __('messages.queue.currently_serving') : __('messages.queue.waiting') }}
            </span>
        </td>
    </tr>
@empty
    <tr class="empty-row">
        <td colspan="4">{{ __('messages.queue.no_patients_in_queue') }}</td>
    </tr>
@endforelse
