/**
 * Registration Wizard Common Script (IPHACON 2027)
 * Handles draft saving, dynamic step navigation, and form validation triggers.
 */
document.addEventListener('DOMContentLoaded', function () {
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    const wizardForm = document.getElementById('wizardForm');

    if (saveDraftBtn && wizardForm) {
        saveDraftBtn.addEventListener('click', function () {
            const formData = new FormData(wizardForm);
            formData.append('action', 'save_draft');

            const postUrl = saveDraftBtn.getAttribute('data-url') || wizardForm.getAttribute('action');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                document.querySelector('input[name="_token"]')?.value || '';

            fetch(postUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.success) {
                        alert('Draft saved successfully!');
                    } else {
                        alert('Error saving draft. Please try again.');
                    }
                })
                .catch(function (error) {
                    console.error('Error:', error);
                    alert('Error saving draft. Please try again.');
                });
        });
    }

    // Expose for backward compatibility
    window.saveDraft = function () {
        if (saveDraftBtn) saveDraftBtn.click();
    };
});
