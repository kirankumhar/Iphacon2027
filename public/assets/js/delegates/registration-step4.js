/**
 * Registration Step 4 Script (IPHACON 2027)
 * Handles payment options, receipt preview modal for foreign delegates,
 * and gateway redirect for domestic delegates without inline scripts.
 */
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('payment_receipt');
    const previewThumb = document.getElementById('receiptPreviewThumb');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const modalBody = document.getElementById('receiptModalBody');
    const proceedBtn = document.getElementById('proceedToPaymentBtn');

    if (proceedBtn) {
        proceedBtn.addEventListener('click', function () {
            const redirectUrl = proceedBtn.getAttribute('data-gateway-url') || '/payment/gateway';
            window.location.href = redirectUrl;
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            // Validate file size (1MB)
            if (file.size > 1 * 1024 * 1024) {
                alert('File size must be less than 1MB!');
                this.value = '';
                if (previewThumb) previewThumb.style.display = 'none';
                if (fileInfo) fileInfo.style.display = 'none';
                return;
            }

            if (fileName) fileName.textContent = file.name;
            if (fileInfo) fileInfo.style.display = 'block';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewThumb) {
                        previewThumb.src = e.target.result;
                        previewThumb.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                if (previewThumb) {
                    previewThumb.style.display = 'none';
                    previewThumb.src = '';
                }
            } else {
                alert('Only PDF, JPG, JPEG, and PNG formats are allowed!');
                this.value = '';
                if (previewThumb) previewThumb.style.display = 'none';
                if (fileInfo) fileInfo.style.display = 'none';
            }
        });
    }

    // Delegated click for thumbnail / filename modal preview
    document.addEventListener('click', function (e) {
        if (e.target.closest('#receiptPreviewThumb') || e.target.closest('#fileInfo')) {
            if (!fileInput || !fileInput.files || !fileInput.files[0]) return;
            const file = fileInput.files[0];

            if (!modalBody) return;

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (evt) {
                    modalBody.innerHTML = '<img src="' + evt.target.result + '" class="img-fluid rounded shadow" alt="Receipt">';
                    showReceiptModal();
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                const pdfURL = URL.createObjectURL(file);
                modalBody.innerHTML = '<iframe src="' + pdfURL + '" class="w-100 border-0" height="600px"></iframe>';
                showReceiptModal();
            }
        }
    });

    function showReceiptModal() {
        const receiptModal = document.getElementById('receiptPreviewModal');
        if (receiptModal) {
            if (receiptModal.parentElement !== document.body) {
                document.body.appendChild(receiptModal);
            }
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(receiptModal).show();
            }
        }
    }

    // Auto redirect if domestic auto-redirect container is present
    const autoRedirect = document.getElementById('autoRedirectGateway');
    if (autoRedirect) {
        const url = autoRedirect.getAttribute('data-url');
        if (url) {
            setTimeout(function () {
                window.location.href = url;
            }, 1000);
        }
    }

    // If reverted, remove secondary cancel button
    if (document.body.getAttribute('data-reverted') === 'true') {
        const cancelBtn = document.querySelector('.btn.btn-outline-secondary.btn-lg.px-4');
        if (cancelBtn) cancelBtn.remove();
    }
});
