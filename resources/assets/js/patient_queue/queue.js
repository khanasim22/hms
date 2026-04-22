(function () {
    "use strict";

    var intervalSec = 20;
    var ringRadius = 15;
    var ringCircumference = 2 * Math.PI * ringRadius;

    $(document).ready(function () {
        var clockEl = $(".js-clock");
        var dateEl = $(".js-date");

        var bodyEl = $(".js-queue-body");
        if (!bodyEl.length) return;

        var refreshBtn = $(".js-refresh-btn");
        var refreshIcon = $(".js-refresh-icon");
        var refreshLabel = $(".js-refresh-label");

        var ringEl = $(".js-ring-progress");
        var ringNumEl = $(".js-ring-num");
        var ringTextEl = $(".js-refresh-countdown");

        var servingEl = $(".js-stat-serving");
        var waitingEl = $(".js-stat-waiting");
        var totalEl = $(".js-stat-total");

        var fullScreenBtn = $(".js-fullscreen-btn");
        var fsLabel = $(".js-fullscreen-label");

        var busy = false;
        var seconds = intervalSec;

        function clock() {
            var now = new Date();

            if (clockEl.length) {
                clockEl.text(
                    now.toLocaleTimeString([], {
                        hour: "2-digit",
                        minute: "2-digit",
                    }),
                );
            }

            if (dateEl.length) {
                dateEl.text(
                    now.toLocaleDateString([], {
                        weekday: "long",
                        year: "numeric",
                        month: "long",
                        day: "numeric",
                    }),
                );
            }
        }

        function updateStats() {
            var rows = bodyEl.find("tr[data-status]");

            var serving = 0;
            var waiting = 0;

            rows.each(function () {
                var status = $(this).attr("data-status");
                if (status === "serving") serving++;
                if (status === "waiting") waiting++;
            });

            if (servingEl.length) servingEl.text(serving);
            if (waitingEl.length) waitingEl.text(waiting);
            if (totalEl.length) totalEl.text(rows.length);
        }

        function ring() {
            var fraction = seconds / intervalSec;

            if (ringEl.length) {
                ringEl.css(
                    "stroke-dashoffset",
                    ringCircumference * (1 - fraction),
                );
            }

            if (ringNumEl.length) ringNumEl.text(seconds);
            if (ringTextEl.length > 0) {
                ringTextEl.text(refreshingText + " " + seconds + "s");
            }
        }

        function doneRefresh() {
            seconds = intervalSec;
            ring();
            updateStats();

            bodyEl.removeClass("fading");
            refreshIcon.removeClass("spin");
            refreshLabel.text(refreshText);

            busy = false;
        }

        function refresh() {
            if (busy) return;
            busy = true;

            bodyEl.addClass("fading");
            refreshIcon.addClass("spin");
            refreshLabel.text(refreshingText);

            var url = "/patient-queue-refresh";

            $.ajax({
                url: url,
                type: "GET",
                success: function (html) {
                    bodyEl.html(html);
                },
                complete: function () {
                    doneRefresh();
                },
            });
        }

        function fullScreenEl() {
            return (
                document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement ||
                null
            );
        }

        function setFsLabel() {
            if (!fsLabel.length) return;

            fsLabel.text(fullScreenEl() ? exitFullscreenText : fullscreenText);
        }

        function toggleFs() {
            try {
                if (fullScreenEl()) {
                    (
                        document.exitFullscreen ||
                        document.webkitExitFullscreen ||
                        document.mozCancelFullScreen ||
                        document.msExitFullscreen
                    ).call(document);
                } else {
                    var el = document.documentElement;
                    (
                        el.requestFullscreen ||
                        el.webkitRequestFullscreen ||
                        el.mozRequestFullScreen ||
                        el.msRequestFullscreen
                    ).call(el);
                }
            } catch (_) {}
        }

        // Clock
        clock();
        setInterval(clock, 1000);

        // Refresh click
        if (refreshBtn.length) refreshBtn.on("click", refresh);

        // Fullscreen
        if (fullScreenBtn.length) {
            var enabled =
                document.fullscreenEnabled ||
                document.webkitFullscreenEnabled ||
                document.mozFullScreenEnabled ||
                document.msFullscreenEnabled;

            if (!enabled) {
                fullScreenBtn.hide();
            } else {
                fullScreenBtn.on("click", toggleFs);
                $(document).on(
                    "fullscreenchange webkitfullscreenchange",
                    setFsLabel,
                );
                setFsLabel();
            }
        }

        // Initial UI
        ring();
        updateStats();

        // Refresh when another tab signals it (e.g. settings save)
        window.addEventListener("storage", function (e) {
            if (e.key === "queueRefresh") window.location.reload();
        });

        // Countdown + auto refresh
        setInterval(function () {
            seconds--;

            if (seconds <= 0) refresh();
            if (seconds <= 0) seconds = intervalSec;

            ring();
        }, 1000);
    });
})();
