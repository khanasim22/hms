@php
    use App\Models\Appointment;

    $hasPatients = isset($patientQueue) && $patientQueue->count() > 0;
@endphp

<div id="patient-list" class="patient-list" style="{{ $hasPatients ? '' : 'display: none;' }}">
    @foreach ($patientQueue as $queue)
        @php
            $appointment = $queue->appointment;
            $isServing = $appointment?->is_completed === Appointment::STATUS_CHECK_IN;
            $statusClass = $isServing ? 'serving' : 'waiting';
        @endphp
        <div class="patient-row {{ $statusClass }}" data-status="{{ $statusClass }}">
            <div class="queue-badge {{ $statusClass }}">#{{ $queue->no }}</div>
            <div class="patient-info">
                <div class="patient-name">{{ $appointment?->patient?->patientUser?->full_name ?? '' }}</div>
                <div class="patient-meta">
                    <span class="doctor">{{ $appointment?->doctor?->doctorUser?->full_name ?? '' }}</span>
                    <div class="meta-dot"></div>
                    <span>{{ $appointment?->department?->title ?? '' }}</span>
                </div>
            </div>
            @if ($isServing)
                <div class="status-pill serving">
                    <div class="dot"></div>
                    <span class="status-text">{{ __('messages.queue.serving') }}</span>
                </div>
            @else
                <div class="status-pill waiting">
                    <div class="dot"></div>
                    <span class="status-text">{{ __('messages.queue.waiting') }}</span>
                </div>
            @endif
        </div>
    @endforeach
</div>

<div class="empty-state" style="{{ $hasPatients ? 'display: none;' : 'display: flex;' }}">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
    <p>{{ __('messages.queue.no_patients_in_queue') }}</p>
</div>
