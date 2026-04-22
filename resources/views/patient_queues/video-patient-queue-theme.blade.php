@php
    $locale = session('locale', 'en');
    App::setLocale($locale);
    $settings = getSettingValue();
    $video = $settings['patient_queue_theme_video']['value'] ?? 'images/videos/hospital-bg.mp4';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset(getSettingValue()['favicon']['value']) }}" type="image/png">
    <title>{{ __('messages.queue.patient_queue_system') }}</title>
    <link href="{{ mix('assets/css/video-queue-theme.css') }}" rel="stylesheet" type="text/css" />

</head>

<body>

    <video id="bg-video" class="bg-video" autoplay muted loop playsinline>
        <source src="{{ asset($video) }}" type="video/mp4" />
    </video>

    <div class="overlay-right"></div>
    <div class="overlay-top"></div>

    <div id="clock-area" class="clock-area">
        <div id="clock-time" class="clock-time"></div>
        <div id="clock-date" class="clock-date"></div>
        <div id="live-badge" class="live-badge">
            <div class="pulse-dot">
                <div class="ping"></div>
                <div class="core"></div>
            </div>
            <span class="label">{{ __('messages.queue.live_updates') }}</span>
        </div>
        <button id="fullscreen-btn" class="fullscreen-badge" type="button" aria-label="Toggle fullscreen">
            <svg class="fullscreen-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M9 3H5a2 2 0 00-2 2v4m16-4v4a2 2 0 01-2 2h-4M3 15v4a2 2 0 002 2h4m10 0h-4a2 2 0 01-2-2v-4" />
            </svg>
            <span id="fullscreen-label" class="label">{{ __('messages.queue.fullscreen') }}</span>
        </button>
    </div>

    <div id="brand" class="brand">
        <div class="brand-icon">
            <a href="{{ url('/') }}" data-toggle="tooltip" data-placement="right"
                class="text-decoration-none sidebar-logo" title="{{ getAppName() }}" target="_blank">
                <img src="{{ asset(getSettingValue()['app_logo']['value']) }}" alt="Logo" class="image" />
            </a>
        </div>
        <div>
            <div class="brand-name">{{ getAppName() }}</div>
            <div class="brand-sub">{{ __('messages.queue.patient_queue_system') }}</div>
        </div>
    </div>

    <div id="queue-panel" class="queue-panel">
        <div class="glass panel-header" id="panel-header">
            <div class="header-left">
                <div class="live-dot"></div>
                <span class="panel-title">{{ __('messages.queue.live_patient_queue') }}</span>
            </div>
            <div class="countdown-wrap">
                <div class="ring-container">
                    <svg width="44" height="44" viewBox="0 0 40 40">
                        <circle cx="20" cy="20" r="16" fill="none" stroke="rgba(255,255,255,0.08)"
                            stroke-width="3" />
                        <circle id="ring-circle" cx="20" cy="20" r="16" fill="none" stroke="#34d399"
                            stroke-width="3" stroke-dasharray="100.53" stroke-dashoffset="0" stroke-linecap="round"
                            style="transition: stroke-dashoffset 1s linear;" />
                    </svg>
                    <span id="countdown-num" class="countdown-num">20</span>
                </div>
                <div class="countdown-label">
                    <div class="top">{{ __('messages.queue.auto_refresh') }}</div>
                    <div class="bottom" id="countdown-text">{{ __('messages.queue.auto_refresh') }}: 20s</div>
                </div>
            </div>
        </div>

        <div class="glass-panel-body">
            <div class="queue-content">
                <div style="height: 100%">
                    @php
                        $servingCount = $patientQueue
                            ->filter(
                                fn($q) => $q?->appointment?->is_completed === \App\Models\Appointment::STATUS_CHECK_IN,
                            )
                            ->count();
                        $waitingCount = $patientQueue->count() - $servingCount;
                    @endphp
                    <div class="glass stats-card" id="stats-card">
                        <div class="stats-row">
                            <div class="stat">
                                <div class="stat-num green" id="stat-serving">{{ $servingCount }}</div>
                                <div class="stat-label">{{ __('messages.queue.serving') }}</div>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat">
                                <div class="stat-num amber" id="stat-waiting">{{ $waitingCount }}</div>
                                <div class="stat-label">{{ __('messages.queue.waiting') }}</div>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat">
                                <div class="stat-num white" id="stat-total">{{ $patientQueue->count() }}</div>
                                <div class="stat-label">{{ __('messages.common.total') }}</div>
                            </div>
                        </div>
                    </div>

                    <div id="queue-body">
                        @include('patient_queues.video_patient_queue_list', [
                            'patientQueue' => $patientQueue,
                        ])
                    </div>
                </div>

                <button id="refresh-btn" class="refresh-btn" onclick="manualRefresh()" type="button">
                    <svg id="refresh-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span id="refresh-label">{{ __('messages.queue.refresh_now') }}</span>
                </button>
            </div>
        </div>

    </div>
    <script>
        window.fullscreenText = @json(__('messages.queue.fullscreen'));
        window.exitFullscreenText = @json(__('messages.queue.exit_fullscreen'));
        window.refreshingInText = @json(__('messages.queue.refreshing_in'));
        window.refreshingText = @json(__('messages.queue.refreshing'));
        window.refreshNowText = @json(__('messages.queue.refresh_now'));
        window.refreshUrl = @json(url('/patient-queue-refresh'));
    </script>
    <script src="{{ mix('assets/js/third-party.js') }}"></script>
    <script src="{{ mix('assets/js/video-queue.js') }}"></script>
</body>

</html>
