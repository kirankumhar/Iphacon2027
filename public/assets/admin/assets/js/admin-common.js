/**
 * Admin Common Script (IPHACON 2027)
 * Centralizes header clock, submit loader, generic table filtering,
 * modal triggers, and media lightbox without inline scripts.
 */
(function (window, document) {
    'use strict';

    // 1. Header Live Clock & Date
    function updateHeaderClock() {
        const clockEl = document.getElementById('headerLiveClock');
        const dateEl = document.getElementById('headerLiveDate');
        if (!clockEl && !dateEl) return;

        const now = new Date();
        const timeOptions = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        const dateOptions = {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        };

        if (clockEl) clockEl.innerText = now.toLocaleTimeString('en-US', timeOptions);
        if (dateEl) dateEl.innerText = '• ' + now.toLocaleDateString('en-US', dateOptions);
    }

    // 2. Generic Table Filter
    function initTableFilters() {
        document.addEventListener('input', function (e) {
            const input = e.target.closest('[data-table-filter]');
            if (!input) return;

            const tableSelector = input.getAttribute('data-table-filter');
            const targetTable = document.querySelector(tableSelector);
            if (!targetTable) return;

            const query = input.value.toLowerCase().trim();
            const rows = targetTable.querySelectorAll('tbody tr:not(.no-filter)');
            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(query) > -1 ? '' : 'none';
            });
        });
    }

    // 3. Media Lightbox Controls
    let currentZoom = 1;

    function openMediaLightbox(url, type, title) {
        const modalEl = document.getElementById('mediaLightboxModal');
        if (!modalEl) return;

        const titleEl = document.getElementById('lightboxTitle');
        const imgEl = document.getElementById('lightboxImage');
        const pdfEl = document.getElementById('lightboxPdf');
        const dlLink = document.getElementById('lightboxDownloadLink');
        const zoomControls = document.getElementById('lightboxZoomControls');

        if (titleEl) titleEl.textContent = title || 'Document Preview';
        if (dlLink) dlLink.href = url;

        currentZoom = 1;
        if (imgEl) imgEl.style.transform = 'scale(1)';

        if (type === 'pdf') {
            if (imgEl) imgEl.classList.add('d-none');
            if (zoomControls) zoomControls.classList.add('d-none');
            if (pdfEl) {
                pdfEl.classList.remove('d-none');
                pdfEl.src = url;
            }
        } else {
            if (pdfEl) {
                pdfEl.classList.add('d-none');
                pdfEl.src = '';
            }
            if (zoomControls) zoomControls.classList.remove('d-none');
            if (imgEl) {
                imgEl.classList.remove('d-none');
                imgEl.src = url;
            }
        }

        if (window.bootstrap && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function zoomLightboxImage(factor) {
        const imgEl = document.getElementById('lightboxImage');
        if (!imgEl) return;
        currentZoom = Math.max(0.5, Math.min(3.0, currentZoom * factor));
        imgEl.style.transform = `scale(${currentZoom})`;
    }

    function resetLightboxImage() {
        const imgEl = document.getElementById('lightboxImage');
        if (!imgEl) return;
        currentZoom = 1;
        imgEl.style.transform = 'scale(1)';
    }

    // 4. Modal Triggers (Revert / Reject)
    function openRevertModal(id, ackId, name) {
        const regInput = document.getElementById('modal_revert_registration_id') || document.getElementById('revertRegistrationId');
        const ackInput = document.getElementById('modal_revert_acknowledgement_id') || document.getElementById('revertAcknowledgementId');
        const infoEl = document.getElementById('modal_revert_delegate_info') || document.getElementById('revertModalText');
        const reasonInput = document.getElementById('modal_revert_reason') || document.getElementById('revertReason');
        if (regInput) regInput.value = id || '';
        if (ackInput) ackInput.value = ackId || '';
        if (infoEl) infoEl.textContent = (name || '') + (ackId ? ' (Ack: ' + ackId + ')' : '');
        if (reasonInput) reasonInput.value = '';

        const modalEl = document.getElementById('revertModal');
        if (modalEl && window.bootstrap) {
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function openRejectModal(id, ackId, name) {
        const regInput = document.getElementById('modal_reject_registration_id') || document.getElementById('rejectRegistrationId');
        const ackInput = document.getElementById('modal_reject_acknowledgement_id') || document.getElementById('rejectAcknowledgementId');
        const infoEl = document.getElementById('modal_reject_delegate_info') || document.getElementById('rejectModalText');
        const reasonInput = document.getElementById('modal_reject_reason') || document.getElementById('rejectReason');
        if (regInput) regInput.value = id || '';
        if (ackInput) ackInput.value = ackId || '';
        if (infoEl) infoEl.textContent = (name || '') + (ackId ? ' (Ack: ' + ackId + ')' : '');
        if (reasonInput) reasonInput.value = '';

        const modalEl = document.getElementById('rejectModal');
        if (modalEl && window.bootstrap) {
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    // 4b. DataTable Auto-Init
    function initDataTables() {
        if (typeof $ !== 'undefined' && $.fn && $.fn.DataTable) {
            $.fn.dataTable.ext.errMode = 'none';
            const $tables = $('.table-datatable, #customSimpleTable');
            $tables.each(function () {
                const $t = $(this);
                if ($t.find('tbody tr:first td').length > 1 && !$.fn.DataTable.isDataTable(this)) {
                    $t.DataTable({
                        responsive: true,
                        pageLength: 10,
                        order: [],
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search delegates..."
                        },
                        drawCallback: function () {
                            const api = this.api();
                            const pageInfo = api.page.info();
                            const wrapper = $(api.table().container());
                            if (pageInfo.pages <= 1) {
                                wrapper.find('.dataTables_paginate').hide();
                            } else {
                                wrapper.find('.dataTables_paginate').show();
                            }
                        }
                    });
                }
            });
        }
    }

    // 5. Global Delegated Click Listeners
    document.addEventListener('click', function (e) {
        // Lightbox open trigger
        const lbTrigger = e.target.closest('[data-lightbox-url]');
        if (lbTrigger) {
            e.preventDefault();
            const url = lbTrigger.getAttribute('data-lightbox-url');
            const type = lbTrigger.getAttribute('data-lightbox-type') || 'image';
            const title = lbTrigger.getAttribute('data-lightbox-title') || 'Document Preview';
            openMediaLightbox(url, type, title);
            return;
        }

        // Lightbox zoom buttons
        if (e.target.closest('#btnZoomIn, .js-zoom-in')) {
            zoomLightboxImage(1.25);
            return;
        }
        if (e.target.closest('#btnZoomOut, .js-zoom-out')) {
            zoomLightboxImage(0.8);
            return;
        }
        if (e.target.closest('#btnZoomReset, .js-zoom-reset')) {
            resetLightboxImage();
            return;
        }

        // Revert modal trigger
        const revertBtn = e.target.closest('[data-action="open-revert-modal"]');
        if (revertBtn) {
            e.preventDefault();
            const id = revertBtn.getAttribute('data-id');
            const ackId = revertBtn.getAttribute('data-ack-id');
            const name = revertBtn.getAttribute('data-name');
            openRevertModal(id, ackId, name);
            return;
        }

        // Reject modal trigger
        const rejectBtn = e.target.closest('[data-action="open-reject-modal"]');
        if (rejectBtn) {
            e.preventDefault();
            const id = rejectBtn.getAttribute('data-id');
            const ackId = rejectBtn.getAttribute('data-ack-id');
            const name = rejectBtn.getAttribute('data-name');
            openRejectModal(id, ackId, name);
            return;
        }
    });

    // 5b. Avatar File Upload Preview
    document.addEventListener('change', function (e) {
        if (e.target && e.target.id === 'upload') {
            const files = e.target.files;
            if (files && files[0]) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const avatar = document.getElementById('uploadedAvatar');
                    if (avatar) avatar.src = ev.target.result;
                };
                reader.readAsDataURL(files[0]);
            }
        }
    });

    // 6. Form Submission Loader
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form && !form.classList.contains('no-loader')) {
            const loader = document.getElementById('loader');
            const textEl = document.getElementById('loading-text');
            if (loader) loader.classList.remove('d-none');
            if (textEl) textEl.textContent = 'Please Wait...';
        }
    });

    // Initialize on DOM Ready
    document.addEventListener('DOMContentLoaded', function () {
        updateHeaderClock();
        setInterval(updateHeaderClock, 1000);
        initTableFilters();
        initDataTables();

        if (window.location.pathname.includes('/dashboard') || document.querySelector('.hero-banner-card, .moderator-hero-card')) {
            window.history.pushState(null, '', window.location.href);
            window.onpopstate = function () {
                window.history.go(1);
            };
        }
    });

    // Expose for backward compatibility
    window.openMediaLightbox = openMediaLightbox;
    window.zoomLightboxImage = zoomLightboxImage;
    window.resetLightboxImage = resetLightboxImage;
    window.openRevertModal = openRevertModal;
    window.openRejectModal = openRejectModal;

})(window, document);
