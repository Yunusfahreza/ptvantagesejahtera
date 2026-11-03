/**
 * LMS SMAN 10 Garut â€” Interactive Micro-interactions & SaaS UI Engine
 * Pure Vanilla JS, zero-build, responsive, accessible, cross-browser.
 */

(function () {
    'use strict';

    /** Token CSRF dari meta tag, dipakai semua request AJAX. */
    window.csrfToken = function () {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    };

    /**
     * 1. Animated Number Counters
     * Smoothly animates numbers in stat cards from 0 to target value on load.
     */
    function initCounters() {
        var counters = document.querySelectorAll('[data-counter], .spatial-number, .stat-counter');
        if (!counters.length) return;

        counters.forEach(function (el) {
            var rawText = el.getAttribute('data-counter') || el.textContent.trim();
            var target = parseInt(rawText.replace(/[^0-9]/g, ''), 10);
            if (isNaN(target) || target <= 0) return;

            var start = 0;
            var duration = 900; // ms
            var startTime = null;

            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                // Ease-out cubic
                var ease = 1 - Math.pow(1 - progress, 3);
                var current = Math.floor(ease * target);
                el.textContent = current;
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.textContent = target;
                }
            }
            window.requestAnimationFrame(step);
        });
    }

    /**
     * 2. Instant Table & Grid Card Live Filter
     * Works on any input with data-instant-filter="#targetId"
     */
    function initInstantSearch() {
        document.addEventListener('input', function (ev) {
            var input = ev.target;
            if (!input.hasAttribute('data-instant-filter')) return;

            var targetSelector = input.getAttribute('data-instant-filter');
            var container = document.querySelector(targetSelector);
            if (!container) return;

            var query = input.value.toLowerCase().trim();
            
            // If container is a table tbody
            if (container.tagName === 'TBODY') {
                var rows = container.querySelectorAll('tr');
                rows.forEach(function (row) {
                    var text = row.textContent.toLowerCase();
                    row.style.display = (query === '' || text.indexOf(query) !== -1) ? '' : 'none';
                });
            } else {
                // If container holds cards (.card or .filterable-item)
                var items = container.querySelectorAll('.filterable-item, .col-12, .col-md-6, .col-lg-4, .card');
                items.forEach(function (item) {
                    var text = item.textContent.toLowerCase();
                    item.style.display = (query === '' || text.indexOf(query) !== -1) ? '' : 'none';
                });
            }
        });
    }

    /**
     * 3. Interactive Filter Tabs (e.g. "Semua", "Menunggu", "Selesai")
     * Elements with data-filter-tab="category" inside a tab container
     */
    function initFilterTabs() {
        document.addEventListener('click', function (ev) {
            var tab = ev.target.closest('[data-filter-value]');
            if (!tab) return;

            var containerSelector = tab.getAttribute('data-filter-target');
            var filterValue = tab.getAttribute('data-filter-value');
            var container = document.querySelector(containerSelector);
            if (!container) return;

            // Update active state on tab buttons in same group
            var parentNav = tab.closest('.filter-nav-group, .nav');
            if (parentNav) {
                parentNav.querySelectorAll('[data-filter-value]').forEach(function (btn) {
                    btn.classList.remove('active', 'btn-primary');
                    btn.classList.add('btn-outline-secondary');
                });
                tab.classList.remove('btn-outline-secondary');
                tab.classList.add('active', 'btn-primary');
            }

            // Filter items inside container
            var items = container.querySelectorAll('[data-category]');
            items.forEach(function (item) {
                var category = item.getAttribute('data-category');
                if (filterValue === 'all' || category === filterValue) {
                    item.style.display = '';
                    item.style.opacity = '1';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    /**
     * 4. Micro-interaction Click Feedback (Button ripple/scale)
     */
    function initButtonFeedback() {
        document.addEventListener('mousedown', function (ev) {
            var btn = ev.target.closest('.btn, .spatial-sidebar-link, .card-action-btn');
            if (!btn) return;
            btn.style.transform = 'scale(0.97)';
        });

        document.addEventListener('mouseup', function (ev) {
            var btn = ev.target.closest('.btn, .spatial-sidebar-link, .card-action-btn');
            if (!btn) return;
            btn.style.transform = '';
        });
    }

    /**
     * 5. Enhanced File Input Preview
     */
    function initFileInputs() {
        document.addEventListener('change', function (ev) {
            var input = ev.target;
            if (!(input instanceof HTMLInputElement) || input.type !== 'file') {
                return;
            }

            var target = input.parentElement
                ? input.parentElement.querySelector('[data-nama-file]')
                : null;

            if (target) {
                if (input.files && input.files.length) {
                    var f = input.files[0];
                    var sizeKb = Math.round(f.size / 1024);
                    target.innerHTML = '<strong>' + f.name + '</strong> (' + sizeKb + ' KB)';
                    target.classList.add('text-success');
                } else {
                    target.textContent = '';
                }
            }
        });
    }

    /**
     * 6. Konfirmasi sebelum submit form
     */
    function initConfirmSubmits() {
        document.addEventListener('submit', function (ev) {
            var form = ev.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            var pesan = form.getAttribute('data-konfirmasi');
            if (pesan && !window.confirm(pesan)) {
                ev.preventDefault();
            }
        });
    }

    /**
     * 7. Bootstrap Tooltips auto-init
     */
    function initTooltips() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                if (!tooltipTriggerEl.getAttribute('data-bs-toggle') && tooltipTriggerEl.getAttribute('title')) {
                    // avoid tooltips on giant container elements
                    if (tooltipTriggerEl.tagName === 'BODY' || tooltipTriggerEl.tagName === 'DIV') return;
                }
                try {
                    new bootstrap.Tooltip(tooltipTriggerEl, { boundary: 'window' });
                } catch (e) {}
            });
        }
    }

    /**
     * Initialize on DOM Ready
     */
    document.addEventListener('DOMContentLoaded', function () {
        initCounters();
        initInstantSearch();
        initFilterTabs();
        initButtonFeedback();
        initFileInputs();
        initConfirmSubmits();
        initTooltips();
    });
}());
