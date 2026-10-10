/**
 * Delegate Registration Page Script (IPHACON 2027)
 * CSP Compliant - Externalized script with event listeners and class-based styling.
 */

(function () {
    'use strict';

    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        if (!passwordInput || !toggleIcon) return;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function refreshCaptcha() {
        const spinner = document.getElementById('captchaSpinner');
        const captchaImage = document.getElementById('captchaImage');
        const captchaInput = document.getElementById('captcha');

        if (spinner) spinner.classList.add('fa-spin');

        if (captchaImage) {
            const baseUrl = captchaImage.getAttribute('data-captcha-src') || captchaImage.src.split('?')[0];
            const separator = baseUrl.includes('?') ? '&' : '?';
            captchaImage.src = baseUrl + separator + 't=' + Date.now();
        }

        if (captchaInput) {
            captchaInput.value = '';
        }

        setTimeout(() => {
            if (spinner) spinner.classList.remove('fa-spin');
        }, 600);
    }

    // Expose functions globally if needed
    window.togglePassword = togglePassword;
    window.refreshCaptcha = refreshCaptcha;

    document.addEventListener('DOMContentLoaded', function () {
        // 1. Password Visibility Toggle
        const toggleBtn = document.getElementById('togglePasswordBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', togglePassword);
        }

        // 2. CAPTCHA Refresh Handlers
        const captchaWrapper = document.getElementById('captchaImgWrapper');
        if (captchaWrapper) {
            captchaWrapper.addEventListener('click', refreshCaptcha);
        }
        const captchaRefreshBtn = document.getElementById('captchaRefreshBtn');
        if (captchaRefreshBtn) {
            captchaRefreshBtn.addEventListener('click', refreshCaptcha);
        }

        // 3. Delegate Type and Country Selection
        const delegateRadios = document.querySelectorAll('input[name="delegate_type"]');
        const countrySelect = document.getElementById('country_id');

        function updateCardState() {
            delegateRadios.forEach(radio => {
                const card = document.getElementById('card-' + radio.id);
                if (card) {
                    if (radio.checked) {
                        card.classList.add('active');
                    } else {
                        card.classList.remove('active');
                    }
                }
            });
        }

        function handleCountryDropdown(isUserChange) {
            if (!countrySelect) return;
            const selectedRadio = document.querySelector('input[name="delegate_type"]:checked');
            const isIndian = !selectedRadio || selectedRadio.value === 'Indian';

            if (isIndian) {
                // Auto-select India
                for (let i = 0; i < countrySelect.options.length; i++) {
                    const option = countrySelect.options[i];
                    const txt = option.text.trim().toLowerCase();
                    if (txt === 'india' || txt.startsWith('india') || txt.includes('india')) {
                        option.selected = true;
                        countrySelect.value = option.value;
                        break;
                    }
                }
                // Lock country dropdown via CSS class
                countrySelect.classList.add('country-select-locked');
                countrySelect.setAttribute('tabindex', '-1');
                countrySelect.title = 'Locked: India is auto-selected for Indian delegates';
            } else {
                // Unlock country dropdown
                countrySelect.classList.remove('country-select-locked');
                countrySelect.removeAttribute('tabindex');
                countrySelect.title = 'Select your country of origin';

                // When selecting International, start with first country starting with letter 'A'
                const currentTxt = countrySelect.options[countrySelect.selectedIndex] ?
                    countrySelect.options[countrySelect.selectedIndex].text.trim().toLowerCase() : '';
                if (isUserChange || currentTxt.includes('india') || !countrySelect.value) {
                    for (let j = 0; j < countrySelect.options.length; j++) {
                        const option = countrySelect.options[j];
                        if (option.value && option.text.trim().toUpperCase().startsWith('A')) {
                            option.selected = true;
                            countrySelect.value = option.value;
                            break;
                        }
                    }
                }
            }
        }

        // Initialize active state & country dropdown
        updateCardState();
        handleCountryDropdown(false);

        delegateRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                updateCardState();
                handleCountryDropdown(true);
            });
        });

        // 4. Live Password Strength and Match Checker
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const matchFeedback = document.getElementById('pw-match-feedback');

        function updateReqBadge(badgeEl, isMet, label) {
            if (!badgeEl) return;
            if (isMet) {
                badgeEl.className = 'badge bg-success-subtle text-success border border-success-subtle fw-medium transition-all py-1 px-1.5';
                badgeEl.innerHTML = '<i class="fas fa-check-circle me-1 text-success"></i>' + label;
            } else {
                badgeEl.className = 'badge bg-light text-secondary border fw-normal transition-all py-1 px-1.5';
                badgeEl.innerHTML = '<i class="far fa-circle me-1 opacity-50"></i>' + label;
            }
        }

        function setBarState(bar, stateClass) {
            if (!bar) return;
            bar.classList.remove('is-danger', 'is-short-0', 'is-short-1', 'is-short-2', 'is-short-3', 'is-weak', 'is-good', 'is-strong');
            if (stateClass) {
                bar.classList.add(stateClass);
            }
        }

        function checkPasswordStrength(val) {
            const wrapper = document.getElementById('pw-strength-wrapper');
            const bar = document.getElementById('pw-strength-bar');
            const text = document.getElementById('pw-strength-text');
            const lengthBadge = document.getElementById('pw-length-badge');

            const reqLength = document.getElementById('req-length');
            const reqLetters = document.getElementById('req-letters');
            const reqUpper = document.getElementById('req-uppercase');
            const reqNumber = document.getElementById('req-number');
            const reqSymbol = document.getElementById('req-symbol');

            if (!val || val.length === 0) {
                if (wrapper) wrapper.classList.add('d-none');
                return;
            }

            if (wrapper) wrapper.classList.remove('d-none');

            const hasMinLen = val.length >= 8;
            const letterCount = (val.match(/[a-zA-Z]/g) || []).length;
            const has4Letters = letterCount >= 4;
            const hasUpper = /[A-Z]/.test(val);
            const hasNum = /\d/.test(val);
            const hasValidSym = /[!@#$]/.test(val);
            const hasInvalidSym = /[^a-zA-Z0-9!@#$]/.test(val);
            const hasSym = hasValidSym && !hasInvalidSym;

            updateReqBadge(reqLength, hasMinLen, '8+ Chars');
            updateReqBadge(reqLetters, has4Letters, '4+ Letters');
            updateReqBadge(reqUpper, hasUpper, 'Uppercase');
            updateReqBadge(reqNumber, hasNum, 'Number');

            if (hasInvalidSym) {
                if (reqSymbol) {
                    reqSymbol.className = 'badge bg-danger-subtle text-danger border border-danger-subtle fw-medium transition-all py-1 px-1.5';
                    reqSymbol.innerHTML = '<i class="fas fa-times-circle me-1 text-danger"></i>Only !@#$ Allowed';
                }
            } else {
                updateReqBadge(reqSymbol, hasSym, 'Symbol (!@#$)');
            }

            let passedCount = (hasMinLen ? 1 : 0) + (has4Letters ? 1 : 0) + (hasUpper ? 1 : 0) + (hasNum ? 1 : 0) + (hasSym ? 1 : 0);
            if (val.length >= 12 && !hasInvalidSym) passedCount++;

            if (lengthBadge) {
                lengthBadge.textContent = val.length + '/8 min';
                lengthBadge.className = hasMinLen && has4Letters && !hasInvalidSym ? 'extra-small text-success fw-bold pw-length-badge' : 'extra-small text-danger fw-medium pw-length-badge';
            }

            if (hasInvalidSym) {
                setBarState(bar, 'is-danger');
                if (text) {
                    text.className = 'extra-small fw-bold text-danger pw-strength-text';
                    text.innerHTML = '<i class="fas fa-times-circle me-1"></i> Invalid Symbol! Only !, @, #, $ allowed';
                }
            } else if (!has4Letters) {
                setBarState(bar, 'is-danger');
                if (text) {
                    text.className = 'extra-small fw-bold text-danger pw-strength-text';
                    text.innerHTML = '<i class="fas fa-times-circle me-1"></i> Min 4 letters required (' + letterCount + '/4)';
                }
            } else if (!hasMinLen) {
                const step = Math.min(Math.floor(val.length / 2), 3);
                setBarState(bar, 'is-short-' + step);
                if (text) {
                    text.className = 'extra-small fw-bold text-danger pw-strength-text';
                    text.innerHTML = '<i class="fas fa-times-circle me-1"></i> Too Short (Min 8)';
                }
            } else if (passedCount <= 3) {
                setBarState(bar, 'is-weak');
                if (text) {
                    text.className = 'extra-small fw-bold text-warning pw-strength-text';
                    text.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Weak Strength';
                }
            } else if (passedCount === 4) {
                setBarState(bar, 'is-good');
                if (text) {
                    text.className = 'extra-small fw-bold text-info pw-strength-text';
                    text.innerHTML = '<i class="fas fa-shield-alt me-1"></i> Good Strength';
                }
            } else {
                setBarState(bar, 'is-strong');
                if (text) {
                    text.className = 'extra-small fw-bold text-success pw-strength-text';
                    text.innerHTML = '<i class="fas fa-check-circle me-1"></i> Strong Password';
                }
            }
        }

        function checkPasswordMatch() {
            if (!confirmInput || !passwordInput || !matchFeedback) return;

            if (confirmInput.value.length === 0) {
                matchFeedback.classList.add('d-none');
                confirmInput.classList.remove('is-invalid', 'is-valid');
                return;
            }

            matchFeedback.classList.remove('d-none');
            if (confirmInput.value === passwordInput.value && passwordInput.value.length >= 8) {
                confirmInput.classList.remove('is-invalid');
                confirmInput.classList.add('is-valid');
                matchFeedback.className = 'extra-small mt-1 text-success font-semibold';
                matchFeedback.innerHTML = '<i class="fas fa-check-circle me-1"></i> Passwords match';
            } else {
                confirmInput.classList.remove('is-valid');
                confirmInput.classList.add('is-invalid');
                matchFeedback.className = 'extra-small mt-1 text-danger font-semibold';
                matchFeedback.innerHTML = '<i class="fas fa-times-circle me-1"></i> Passwords do not match';
            }
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });
        }

        if (confirmInput) {
            confirmInput.addEventListener('input', checkPasswordMatch);
        }

        // 5. Auto-focus next field on Enter key
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach((input, index) => {
            input.addEventListener('keypress', function (e) {
                if (e.key === 'Enter' && input.type !== 'submit' && index < inputs.length - 1) {
                    e.preventDefault();
                    inputs[index + 1].focus();
                }
            });
        });
    });
})();
