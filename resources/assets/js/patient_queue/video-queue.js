(function () {
    "use strict";

    var intervalSec = 20;
    var ringCircumference = 100.53;

    $(document).ready(function () {
        var $clockTimeEl = $("#clock-time");
        var $clockDateEl = $("#clock-date");

        var $queueBody = $("#queue-body");
        if (!$queueBody.length) return;

        var $fullScreenBtn = $("#fullscreen-btn");
        var $fsLabel = $("#fullscreen-label");

        var $ringEl = $("#ring-circle");
        var $ringNumEl = $("#countdown-num");
        var $ringTextEl = $("#countdown-text");

        var $refreshIcon = $("#refresh-icon");
        var $refreshLabel = $("#refresh-label");

        var $servingEl = $("#stat-serving");
        var $waitingEl = $("#stat-waiting");
        var $totalEl = $("#stat-total");

        var $patientList = $();
        var $rows = $();

        var fullscreenText = window.fullscreenText || "Fullscreen";
        var exitFullscreenText = window.exitFullscreenText || "Exit Fullscreen";
        var refreshingInText = window.refreshingInText || "Refreshing in";
        var refreshingText = window.refreshingText || "Refreshing...";
        var refreshNowText = window.refreshNowText || "Refresh now";

        var refreshUrl = window.refreshUrl || "/patient-queue-refresh";

        var busy = false;
        var seconds = intervalSec;

        function cacheList() {
            $patientList = $("#patient-list");
            $rows = $patientList.find(".patient-row[data-status]");
        }

        function fullscreenEnabled() {
            return !!(
                document.fullscreenEnabled ||
                document.webkitFullscreenEnabled ||
                document.mozFullScreenEnabled ||
                document.msFullscreenEnabled
            );
        }

        function fullscreenElement() {
            return (
                document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement ||
                null
            );
        }

        function setFullscreenLabel() {
            if (!$fsLabel.length) return;
            $fsLabel.text(
                fullscreenElement() ? exitFullscreenText : fullscreenText,
            );
        }

        function requestFullscreen(el) {
            if (el.requestFullscreen) return el.requestFullscreen();
            if (el.webkitRequestFullscreen) return el.webkitRequestFullscreen();
            if (el.mozRequestFullScreen) return el.mozRequestFullScreen();
            if (el.msRequestFullscreen) return el.msRequestFullscreen();
        }

        function exitFullscreen() {
            if (document.exitFullscreen) return document.exitFullscreen();
            if (document.webkitExitFullscreen)
                return document.webkitExitFullscreen();
            if (document.mozCancelFullScreen)
                return document.mozCancelFullScreen();
            if (document.msExitFullscreen) return document.msExitFullscreen();
        }

        function toggleFullscreen() {
            try {
                if (fullscreenElement()) {
                    exitFullscreen();
                } else {
                    requestFullscreen(document.documentElement);
                }
            } catch (_) {}
        }

        function updateClock() {
            var now = new Date();
            if ($clockTimeEl.length) {
                $clockTimeEl.text(
                    now.toLocaleTimeString([], {
                        hour: "2-digit",
                        minute: "2-digit",
                    }),
                );
            }
            if ($clockDateEl.length) {
                $clockDateEl.text(
                    now.toLocaleDateString([], {
                        weekday: "long",
                        day: "numeric",
                        month: "long",
                        year: "numeric",
                    }),
                );
            }
        }

        function setRefreshing(state) {
            if ($refreshIcon.length) $refreshIcon.toggleClass("spin", state);
            if ($refreshLabel.length)
                $refreshLabel.text(state ? refreshingText : refreshNowText);
            if ($patientList.length) $patientList.toggleClass("fading", state);
        }

        function updateStatsFromDom() {
            var serving = 0;
            var waiting = 0;

            $rows.each(function () {
                var status = $(this).data("status");
                if (status === "serving") serving++;
                if (status === "waiting") waiting++;
            });

            if ($servingEl.length) $servingEl.text(serving);
            if ($waitingEl.length) $waitingEl.text(waiting);
            if ($totalEl.length) $totalEl.text($rows.length);
        }

        function doneRefresh() {
            seconds = intervalSec;
            ring();
            setRefreshing(false);
            busy = false;
        }

        function refreshQueue() {
            if (busy) return;
            busy = true;
            setRefreshing(true);

            $.ajax({
                url: refreshUrl,
                type: "GET",
                success: function (html) {
                    $queueBody.html(html);
                    cacheList();
                    updateStatsFromDom();
                },
                complete: function () {
                    doneRefresh();
                },
            });
        }

        function ring() {
            var fraction = seconds / intervalSec;
            var offset = ringCircumference * (1 - fraction);

            if ($ringEl.length)
                $ringEl.css("stroke-dashoffset", offset.toFixed(2));
            if ($ringNumEl.length) $ringNumEl.text(seconds);
            if ($ringTextEl.length)
                $ringTextEl.text(refreshingInText + " " + seconds + "s");
        }

        function manualRefresh() {
            refreshQueue();
            seconds = intervalSec;
            ring();
        }

        function bindFullscreenEvents() {
            if (!$fullScreenBtn.length) return;
            var enabled = fullscreenEnabled();
            if (!enabled) return $fullScreenBtn.hide();

            $fullScreenBtn.on("click", toggleFullscreen);
            $(document).on(
                "fullscreenchange webkitfullscreenchange",
                setFullscreenLabel,
            );
            setFullscreenLabel();
        }

        window.manualRefresh = manualRefresh;

        updateClock();
        setInterval(updateClock, 1000);
        cacheList();
        bindFullscreenEvents();
        updateStatsFromDom();
        ring();

        window.addEventListener("storage", function (e) {
            if (e.key === "queueRefresh") window.location.reload();
        });

        setInterval(function () {
            seconds--;

            if (seconds <= 0) refreshQueue();
            if (seconds <= 0) seconds = intervalSec;

            ring();
        }, 1000);
    });
})();
