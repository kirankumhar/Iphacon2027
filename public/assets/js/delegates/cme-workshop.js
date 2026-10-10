/**
 * CME Workshop Pricing Toggle (IPHACON 2027)
 * Handles fee calculation and visual states without inline scripts or inline styles.
 */
document.addEventListener('DOMContentLoaded', function () {
    const cb = document.getElementById('cmeCheckbox');
    const breakdown = document.getElementById('pricingBreakdown');
    const unselected = document.getElementById('unselectedMessage');
    const btnSubmit = document.getElementById('btnSubmitPayment');
    const cmeBox = document.getElementById('cmeBox');

    function toggleCmePricing() {
        if (!cb) return;

        if (cb.checked) {
            if (breakdown) breakdown.classList.remove('d-none');
            if (unselected) unselected.classList.add('d-none');
            if (btnSubmit) btnSubmit.disabled = false;
            if (cmeBox) {
                cmeBox.classList.add('cme-box-active');
                cmeBox.classList.remove('cme-box-inactive');
            }
        } else {
            if (breakdown) breakdown.classList.add('d-none');
            if (unselected) unselected.classList.remove('d-none');
            if (btnSubmit) btnSubmit.disabled = true;
            if (cmeBox) {
                cmeBox.classList.remove('cme-box-active');
                cmeBox.classList.add('cme-box-inactive');
            }
        }
    }

    if (cb) {
        cb.addEventListener('change', toggleCmePricing);
        toggleCmePricing();
    }

    window.toggleCmePricing = toggleCmePricing;
});
