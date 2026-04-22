@php
    $locale = session('locale', 'en');
    App::setLocale($locale);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.queue.patient_queue_system') }}</title>
    <link rel="icon" href="{{ getLogoUrl() }}" type="image/png">
    <link href="{{ mix('assets/css/queue.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <header>
        <div class="logo-area">
            <div class="logo-icon">
                <a href="{{ url('/') }}" data-toggle="tooltip" data-placement="right"
                    class="text-decoration-none sidebar-logo" title="{{ getAppName() }}" target="_blank">
                    <img src="{{ asset(getSettingValue()['app_logo']['value']) }}" alt="Logo" width="50px"
                        height="50px" class="image" />
                </a>
            </div>
            <div>
                <div class="logo-text">{{ getAppName() }}</div>
                <div class="logo-sub">{{ __('messages.queue.patient_queue_system') }}</div>
            </div>
        </div>
        <div class="header-right">
            <div>
                <div class="header-clock js-clock"></div>
                <div class="header-date js-date">—</div>
            </div>
            <div class="live-indicator">
                <div class="live-dot"></div>
                <span class="live-label">{{ __('messages.queue.live') }}</span>
            </div>
        </div>
    </header>

    <div class="stats-bar">
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="stat-num stat-serving js-stat-serving">0</div>
                <div class="stat-label">{{ __('messages.queue.currently_serving') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="stat-num stat-waiting js-stat-waiting">0</div>
                <div class="stat-label">{{ __('messages.queue.waiting') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <div class="stat-num stat-total js-stat-total">0</div>
                <div class="stat-label">{{ __('messages.queue.total_in_queue') }}</div>
            </div>
        </div>
        <div class="refresh-info">
            <div class="countdown-ring">
                <svg width="42" height="42" viewBox="0 0 40 40">
                    <circle class="ring-track" cx="20" cy="20" r="15" fill="none" stroke="#e2e8f0"
                        stroke-width="3" />
                    <circle class="ring-progress js-ring-progress" cx="20" cy="20" r="15" fill="none"
                        stroke="#2563eb" stroke-width="3" stroke-dasharray="94.25" stroke-dashoffset="0"
                        stroke-linecap="round" />
                </svg>
                <div class="ring-num js-ring-num">20</div>
            </div>
            <div class="refresh-meta">
                <strong class="refresh-countdown js-refresh-countdown">{{ __('messages.queue.auto_refresh') }}
                    20s</strong>
                {{ __('messages.queue.auto_refresh_active') }}
            </div>
        </div>
    </div>

    <div class="tabs-row">
        <div class="tab-list">
        </div>
        <div class="queue-actions">
            <button class="fullscreen-btn js-fullscreen-btn" type="button" aria-label="Toggle fullscreen">
                <svg class="fullscreen-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M9 3H5a2 2 0 00-2 2v4m16-4v4a2 2 0 01-2 2h-4M3 15v4a2 2 0 002 2h4m10 0h-4a2 2 0 01-2-2v-4" />
                </svg>
                <span class="fullscreen-label js-fullscreen-label">{{ __('messages.queue.fullscreen') }}</span>
            </button>

            <button class="refresh-btn js-refresh-btn" type="button">
                <svg class="refresh-icon js-refresh-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="refresh-label js-refresh-label">{{ __('messages.queue.refresh') }}</span>
            </button>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.queue.patient_name') }}</th>
                    <th>{{ __('messages.queue.attending_doctors') }}</th>
                    <th>{{ __('messages.queue.status') }}</th>
                </tr>
            </thead>
            <tbody class="table-body js-queue-body">
                @include('patient_queues.patient_queue_list', ['patientQueue' => $patientQueue])
            </tbody>
        </table>
    </div>
    <script>
        window.refreshingText = @json(__('messages.queue.refreshing_in'));
        window.refreshText = @json(__('messages.queue.refresh'));
        window.exitFullscreenText = @json(__('messages.queue.exit_fullscreen'));
        window.fullscreenText = @json(__('messages.queue.fullscreen'));
    </script>
    <script src="{{ mix('assets/js/third-party.js') }}"></script>
    <script src="{{ mix('assets/js/queue.js') }}"></script>

</body>

</html>
