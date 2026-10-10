/**
 * Abstract Submission Portal Script
 * CSP Compliant (no inline scripts, no inline event handlers)
 */

(function () {
    'use strict';

    const MAX_CO_AUTHORS = 10;
    let coAuthorCount = 0;
    let isTestingModeActive = false;

    function countWordsInText(str) {
        if (!str) return 0;
        return str.trim().split(/\s+/).filter(w => w.length > 0).length;
    }

    function updateCoAuthorBadge() {
        const container = document.getElementById('coAuthorsContainer');
        if (!container) return;
        const existingBoxes = container.querySelectorAll('.co-author-box');
        const count = existingBoxes.length;

        const badge = document.getElementById('coAuthorCountBadge');
        if (badge) {
            badge.innerText = `${count} / ${MAX_CO_AUTHORS} Co-Authors`;
            if (count >= MAX_CO_AUTHORS) {
                badge.className = 'badge bg-danger text-white border ms-1 font-monospace';
            } else {
                badge.className = 'badge bg-light text-dark border ms-1 font-monospace';
            }
        }

        const btn = document.getElementById('addCoAuthorBtn');
        if (btn) {
            if (count >= MAX_CO_AUTHORS) {
                btn.disabled = true;
                btn.classList.add('disabled');
                btn.title = 'Maximum limit of 10 co-authors reached';
            } else {
                btn.disabled = false;
                btn.classList.remove('disabled');
                btn.title = '';
            }
        }
    }

    function addCoAuthorRow() {
        const container = document.getElementById('coAuthorsContainer');
        if (!container) return;
        const existingBoxes = container.querySelectorAll('.co-author-box');

        if (existingBoxes.length >= MAX_CO_AUTHORS) {
            alert(`You can add a maximum of ${MAX_CO_AUTHORS} co-authors.`);
            return;
        }

        if (existingBoxes.length > 0) {
            const lastBox = existingBoxes[existingBoxes.length - 1];
            const requiredInputs = lastBox.querySelectorAll('input[required]');
            let hasEmpty = false;

            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    hasEmpty = true;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (hasEmpty) {
                alert('Please fill out all required fields (Full Name, Designation, Department, Institution) for the current co-author before adding a new co-author.');
                const firstEmpty = lastBox.querySelector('.is-invalid');
                if (firstEmpty) firstEmpty.focus();
                return;
            }
        }

        coAuthorCount++;
        const boxNumber = existingBoxes.length + 1;
        const box = document.createElement('div');
        box.className = 'co-author-box';
        box.id = `coAuthorBox_${coAuthorCount}`;

        box.innerHTML = `
            <button type="button" class="btn-remove-author" data-action="remove-co-author" title="Remove Author">
                <i class="fas fa-trash-alt me-1"></i> Remove
            </button>
            <div class="fw-bold text-secondary mb-2.5 co-author-header" style="font-size: 0.85rem;">
                <i class="fas fa-user-plus me-1 text-primary"></i> Co-Author #${boxNumber}
            </div>
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-semibold text-dark small mb-1">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="co_author_name[]" placeholder="Co-author full name" required>
                </div>
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-semibold text-dark small mb-1">Designation <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="co_author_designation[]" placeholder="Designation" required>
                </div>
                <div class="col-md-6 col-lg-4">
                    <label class="form-label fw-semibold text-dark small mb-1">Department <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="co_author_department[]" placeholder="Department" required>
                </div>
                <div class="col-md-6 col-lg-6">
                    <label class="form-label fw-semibold text-dark small mb-1">Institution <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="co_author_institution[]" placeholder="Institution" required>
                </div>
                <div class="col-md-6 col-lg-6">
                    <label class="form-label fw-semibold text-dark small mb-1">Email <span class="text-muted fw-normal">(Optional)</span></label>
                    <input type="email" class="form-control form-control-sm" name="co_author_email[]" placeholder="Email address">
                </div>
            </div>
        `;
        container.appendChild(box);
        updateCoAuthorBadge();

        const newFirstInput = box.querySelector('input');
        if (newFirstInput) newFirstInput.focus();
    }

    function removeCoAuthorRow(box) {
        if (box) {
            box.remove();
        }

        const container = document.getElementById('coAuthorsContainer');
        if (container) {
            const remainingBoxes = container.querySelectorAll('.co-author-box');
            remainingBoxes.forEach((b, index) => {
                const label = b.querySelector('.co-author-header');
                if (label) {
                    label.innerHTML = `<i class="fas fa-user-plus me-1 text-primary"></i> Co-Author #${index + 1}`;
                }
            });
        }
        updateCoAuthorBadge();
    }

    function toggleOtherCategory(selectEl) {
        const wrapper = document.getElementById('other_category_wrapper');
        const input = document.getElementById('other_category_text');
        if (!wrapper || !input) return;

        if (selectEl.value === 'Other') {
            wrapper.classList.remove('d-none');
            input.setAttribute('required', 'required');
        } else {
            wrapper.classList.add('d-none');
            input.removeAttribute('required');
        }
    }

    function countKeywords() {
        const input = document.getElementById('keywordsInput');
        const text = input ? input.value : '';
        const keywords = text.split(',').map(k => k.trim()).filter(k => k.length > 0);
        const count = keywords.length;
        const badge = document.getElementById('keywordsCountBadge');
        const err = document.getElementById('keywordsError');

        if (badge) badge.innerText = count;
        if (count >= 3 && count <= 5) {
            if (badge) badge.className = 'fw-bold text-success';
            if (err) err.classList.add('d-none');
        } else {
            if (badge) badge.className = 'fw-bold text-danger';
            if (err && count > 0) err.classList.remove('d-none');
        }
        return count;
    }

    function countTitleWords() {
        const titleEl = document.getElementById('abstract_title');
        const val = titleEl ? titleEl.value : '';
        const count = countWordsInText(val);
        const badge = document.getElementById('titleWordCount');
        if (badge) badge.innerText = count;
        return count;
    }

    function updateTotalWordCount() {
        const textareas = document.querySelectorAll('.abstract-body-part');
        let total = 0;
        textareas.forEach(ta => {
            total += countWordsInText(ta.value);
        });

        const badge = document.getElementById('totalWordCountBadge');
        if (badge) {
            badge.innerText = `${total} / 300 Words`;
            if (total > 300) {
                badge.className = 'word-counter-badge badge-exceeded-limit';
            } else {
                badge.className = 'word-counter-badge badge-within-limit';
            }
        }
        return total;
    }

    function openPreviewModal() {
        const form = document.getElementById('abstractForm');
        if (!form) return;
        const formData = new FormData(form);

        let coAuthorsHtml = '';
        const names = formData.getAll('co_author_name[]');
        const desgs = formData.getAll('co_author_designation[]');
        const depts = formData.getAll('co_author_department[]');
        const insts = formData.getAll('co_author_institution[]');

        for (let i = 0; i < names.length; i++) {
            if (names[i].trim() !== '') {
                coAuthorsHtml += `<li><strong>${names[i]}</strong> (${desgs[i] || ''}, ${depts[i] || ''}, ${insts[i] || ''})</li>`;
            }
        }

        const html = `
            <div class="border-bottom pb-3 mb-3">
                <h4 class="fw-bold text-primary mb-2">${formData.get('abstract_title') || 'Untitled Abstract'}</h4>
                <div class="small text-secondary mb-1">
                    <strong>Presenting Author:</strong> ${formData.get('presenting_author_name') || ''} (${formData.get('presenting_author_designation') || ''}, ${formData.get('presenting_author_institution') || ''})
                </div>
                ${coAuthorsHtml ? `<div class="small text-secondary mb-1"><strong>Co-Authors:</strong> <ol class="mb-0 ps-3">${coAuthorsHtml}</ol></div>` : ''}
                <div class="d-flex gap-2 mt-2 flex-wrap">
                    <span class="badge bg-primary text-white">${formData.get('presentation_mode') || 'N/A'}</span>
                    <span class="badge bg-info text-white">${formData.get('presenter_category') || 'N/A'}</span>
                    <span class="badge bg-success text-white">${formData.get('conference_theme') || 'N/A'}</span>
                </div>
            </div>

            <div class="mb-3">
                <strong class="text-dark">Keywords:</strong> <span class="fst-italic text-secondary">${formData.get('keywords') || 'N/A'}</span>
            </div>

            <div class="d-flex flex-column gap-3">
                <div><h6 class="fw-bold text-dark mb-1">Background</h6><p class="small text-secondary mb-0">${formData.get('abstract_background') || '-'}</p></div>
                <div><h6 class="fw-bold text-dark mb-1">Objectives</h6><p class="small text-secondary mb-0">${formData.get('abstract_objectives') || '-'}</p></div>
                <div><h6 class="fw-bold text-dark mb-1">Methodology</h6><p class="small text-secondary mb-0">${formData.get('abstract_methodology') || '-'}</p></div>
                <div><h6 class="fw-bold text-dark mb-1">Results</h6><p class="small text-secondary mb-0">${formData.get('abstract_results') || '-'}</p></div>
                <div><h6 class="fw-bold text-dark mb-1">Conclusion</h6><p class="small text-secondary mb-0">${formData.get('abstract_conclusion') || '-'}</p></div>
            </div>
        `;

        const modalContent = document.getElementById('modalPreviewContent');
        if (modalContent) modalContent.innerHTML = html;

        const modalEl = document.getElementById('abstractPreviewModal');
        if (modalEl) {
            if (modalEl.parentNode !== document.body) {
                document.body.appendChild(modalEl);
            }
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function enableTestingModeAndAutoFill() {
        isTestingModeActive = true;

        const fieldset = document.getElementById('abstractFormFieldset');
        if (fieldset) {
            fieldset.removeAttribute('disabled');
        }

        const testingInput = document.getElementById('testing_mode_input');
        if (testingInput) {
            testingInput.value = '1';
        }

        const badge = document.getElementById('testingBadgeStatus');
        if (badge) {
            badge.className = 'badge bg-success text-white font-monospace';
            badge.innerText = 'ACTIVE 🧪';
        }

        const help = document.getElementById('testingBadgeHelp');
        if (help) {
            help.innerHTML = '<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Testing Mode Activated! Submission unlocked & test abstract auto-filled.</span>';
        }

        const form = document.getElementById('abstractForm');
        if (form) {
            const setVal = (name, val) => {
                const el = form.querySelector(`[name="${name}"]`);
                if (el) el.value = val;
            };

            const defaultName = form.dataset.userName || 'Dr. Kieran Kumar (Test)';
            const defaultEmail = form.dataset.userEmail || 'test.author@iphacon2027.com';

            setVal('presenting_author_name', defaultName);
            setVal('presenting_author_designation', 'Associate Professor');
            setVal('presenting_author_department', 'Department of Community Medicine');
            setVal('presenting_author_institution', 'RIMS, Ranchi');
            setVal('presenting_author_city', 'Ranchi');
            setVal('presenting_author_state', 'Jharkhand');
            setVal('presenting_author_country', 'India');
            setVal('presenting_author_email', defaultEmail);
            setVal('presenting_author_mobile', '+91 9876543210');
            setVal('medical_council_reg_no', 'MCI/2026/98765');

            const oralRadio = form.querySelector('input[name="presentation_mode"][value="Oral Presentation"]');
            if (oralRadio) oralRadio.checked = true;

            const catSelect = document.getElementById('presenter_category');
            if (catSelect) {
                catSelect.value = 'Faculty';
                toggleOtherCategory(catSelect);
            }

            const themeSelect = document.getElementById('conference_theme');
            if (themeSelect) {
                themeSelect.value = 'Health Systems, Policy & Governance';
            }

            const titleInput = document.getElementById('abstract_title');
            if (titleInput) titleInput.value = 'Efficacy of Digital Surveillance in Public Health Outbreak Response';

            const kwInput = document.getElementById('keywordsInput');
            if (kwInput) kwInput.value = 'Surveillance, Public Health, Digital Health, Epidemiology, Outbreak';

            setVal('abstract_background', 'Public health surveillance is critical for early detection and response to infectious disease outbreaks in rural and urban healthcare settings.');
            setVal('abstract_objectives', 'To evaluate the speed, accuracy, and epidemiological impact of integrated digital disease surveillance systems in secondary care hospitals.');
            setVal('abstract_methodology', 'A multi-center prospective observational study was conducted across 15 district health centers over a 12-month period using real-time mobile reporting tools.');
            setVal('abstract_results', 'Digital reporting reduced median outbreak detection latency from 14 days to 2.5 days (p < 0.001) and increased reporting completeness to 96.8%.');
            setVal('abstract_conclusion', 'Integrated digital surveillance significantly enhances public health response capabilities and reduces response delays during epidemic outbreaks.');

            const coAuthorContainer = document.getElementById('coAuthorsContainer');
            if (coAuthorContainer && coAuthorContainer.querySelectorAll('.co-author-box').length === 0) {
                addCoAuthorRow();
                const names = form.querySelectorAll('[name="co_author_name[]"]');
                const desgs = form.querySelectorAll('[name="co_author_designation[]"]');
                const depts = form.querySelectorAll('[name="co_author_department[]"]');
                const insts = form.querySelectorAll('[name="co_author_institution[]"]');
                const emails = form.querySelectorAll('[name="co_author_email[]"]');
                if (names.length > 0) names[0].value = 'Dr. Ananya Roy';
                if (desgs.length > 0) desgs[0].value = 'Assistant Professor';
                if (depts.length > 0) depts[0].value = 'Department of Epidemiology';
                if (insts.length > 0) insts[0].value = 'AIIMS New Delhi';
                if (emails.length > 0) emails[0].value = 'ananya.roy@test.com';
            }

            const confirmChk = document.getElementById('confirmReview');
            if (confirmChk) confirmChk.checked = true;

            countTitleWords();
            countKeywords();
            updateTotalWordCount();
        }

        const formEl = document.getElementById('abstractForm');
        if (formEl) {
            formEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function saveAsDraft() {
        const form = document.getElementById('abstractForm');
        if (!form) return;

        const canSubmit = form.dataset.canSubmit === '1';
        if (!canSubmit && !isTestingModeActive) {
            alert('Abstract submission is restricted until your registration is Approved by the organizing committee.');
            return;
        }

        const formData = new FormData(form);
        formData.append('action', 'save_draft');
        if (isTestingModeActive) {
            formData.set('testing_mode', '1');
        }

        const storeUrl = form.dataset.storeUrl || form.action;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]')?.value;

        fetch(storeUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Abstract draft saved successfully!');
            } else {
                alert(data.message || 'Error saving draft.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while saving draft.');
        });
    }

    function handleAbstractSubmit(event) {
        if (event) event.preventDefault();
        const form = document.getElementById('abstractForm');
        if (!form) return;

        const canSubmit = form.dataset.canSubmit === '1';
        if (!canSubmit && !isTestingModeActive) {
            alert('Abstract submission is restricted until your registration is Approved by the organizing committee.');
            return;
        }

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const titleVal = document.getElementById('abstract_title')?.value || '';
        const titleWords = countWordsInText(titleVal);
        if (titleWords > 25) {
            alert(`Abstract title exceeds maximum limit of 25 words. Current title word count: ${titleWords} words.`);
            document.getElementById('abstract_title')?.focus();
            return;
        }

        const kwCount = countKeywords();
        if (kwCount < 3 || kwCount > 5) {
            alert('Please enter between 3 and 5 keywords separated by commas.');
            document.getElementById('keywordsError')?.classList.remove('d-none');
            document.getElementById('keywordsInput')?.focus();
            return;
        }

        const total = updateTotalWordCount();
        if (total > 300) {
            alert(`Your structured abstract exceeds the maximum 300-word limit. Current count: ${total} words. Please shorten your text before submitting.`);
            return;
        }

        const confirmReview = document.getElementById('confirmReview');
        if (confirmReview && !confirmReview.checked) {
            alert('Please confirm that you have reviewed the abstract and author details by checking the declaration checkbox.');
            confirmReview.focus();
            return;
        }

        const formData = new FormData(form);
        if (isTestingModeActive) {
            formData.set('testing_mode', '1');
        }

        const storeUrl = form.dataset.storeUrl || form.action;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]')?.value;

        fetch(storeUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const ackDisplay = document.getElementById('ackIDDisplay');
                if (ackDisplay) ackDisplay.innerText = data.acknowledgement_id || 'ABS-2027-SUCCESS';

                const modalEl = document.getElementById('acknowledgementModal');
                if (modalEl) {
                    if (modalEl.parentNode !== document.body) {
                        document.body.appendChild(modalEl);
                    }
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            } else {
                if (data.errors) {
                    const firstErr = Object.values(data.errors)[0];
                    alert(Array.isArray(firstErr) ? firstErr[0] : firstErr);
                } else {
                    alert(data.message || 'Validation error while submitting abstract.');
                }
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while submitting the abstract.');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('abstractForm');

        // Initialize co-authors counter and badge
        const coAuthorsContainer = document.getElementById('coAuthorsContainer');
        if (coAuthorsContainer) {
            coAuthorCount = coAuthorsContainer.querySelectorAll('.co-author-box').length;
            updateCoAuthorBadge();
        }

        // Add Co-Author button
        const addCoAuthorBtn = document.getElementById('addCoAuthorBtn');
        if (addCoAuthorBtn) {
            addCoAuthorBtn.addEventListener('click', addCoAuthorRow);
        }

        // Delegated listener for removing co-authors & removing invalid state on input
        if (coAuthorsContainer) {
            coAuthorsContainer.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('[data-action="remove-co-author"]');
                if (removeBtn) {
                    e.preventDefault();
                    removeCoAuthorRow(removeBtn.closest('.co-author-box'));
                }
            });

            coAuthorsContainer.addEventListener('input', function (e) {
                if (e.target.matches('input')) {
                    e.target.classList.remove('is-invalid');
                }
            });
        }

        // Category change listener
        const presenterCat = document.getElementById('presenter_category');
        if (presenterCat) {
            presenterCat.addEventListener('change', function () {
                toggleOtherCategory(this);
            });
        }

        // Title word count listener
        const titleInput = document.getElementById('abstract_title');
        if (titleInput) {
            titleInput.addEventListener('input', countTitleWords);
        }

        // Keywords count listener
        const keywordsInput = document.getElementById('keywordsInput');
        if (keywordsInput) {
            keywordsInput.addEventListener('input', countKeywords);
        }

        // Structured abstract word counts
        const textareas = document.querySelectorAll('.abstract-body-part');
        textareas.forEach(ta => {
            ta.addEventListener('input', updateTotalWordCount);
        });

        // Testing mode buttons
        document.querySelectorAll('.js-testing-autofill').forEach(btn => {
            btn.addEventListener('click', enableTestingModeAndAutoFill);
        });

        // Save Draft button
        const saveDraftBtn = document.getElementById('saveDraftBtn');
        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', saveAsDraft);
        }

        // Preview button
        const previewBtn = document.getElementById('openPreviewBtn');
        if (previewBtn) {
            previewBtn.addEventListener('click', openPreviewModal);
        }

        // Form submit
        if (form) {
            form.addEventListener('submit', handleAbstractSubmit);
        }

        // Initial word counts on load
        countTitleWords();
        countKeywords();
        updateTotalWordCount();
    });
})();
