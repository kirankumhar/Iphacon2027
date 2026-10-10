/**
 * Registration Step 1 Script (IPHACON 2027)
 * Handles profile photo upload, ID proof selection & dynamic validation,
 * document preview lightbox modal, and file format checks without inline scripts.
 */
(function (window, document) {
    'use strict';

    function formatIdProofNumber(input) {
        if (!input) return;
        const typeSelect = document.getElementById('id_proof_type');
        const selectedType = typeSelect ? typeSelect.value : '';
        if (selectedType === 'Aadhaar') {
            input.value = input.value.replace(/[^0-9]/g, '');
        } else if (selectedType === 'PAN' || selectedType === 'Voter-ID' || selectedType === 'Passport') {
            input.value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        } else if (selectedType === 'Driving License') {
            input.value = input.value.toUpperCase().replace(/[^A-Z0-9\/-]/g, '');
        }
    }

    function updateIdProofValidation() {
        const typeSelect = document.getElementById('id_proof_type');
        const numberInput = document.getElementById('id_proof_number');
        const label = document.getElementById('id_proof_number_label');
        if (!typeSelect || !numberInput) return;

        const selectedType = typeSelect.value;
        if (selectedType === 'Aadhaar') {
            if (label) label.innerHTML = 'Aadhaar Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter 12-digit Aadhaar Number';
            numberInput.maxLength = 12;
        } else if (selectedType === 'PAN') {
            if (label) label.innerHTML = 'PAN Card Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter 10-character PAN Number (e.g. ABCDE1234F)';
            numberInput.maxLength = 10;
        } else if (selectedType === 'Passport') {
            if (label) label.innerHTML = 'Passport Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter Passport Number';
            numberInput.maxLength = 12;
        } else if (selectedType === 'Voter-ID') {
            if (label) label.innerHTML = 'Voter ID Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter Voter ID Number';
            numberInput.maxLength = 12;
        } else if (selectedType === 'Driving License') {
            if (label) label.innerHTML = 'Driving License Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter Driving License Number';
            numberInput.maxLength = 20;
        } else {
            if (label) label.innerHTML = 'ID Proof Number <span class="required-star text-danger">*</span>';
            numberInput.placeholder = 'Enter ID Proof Number';
            numberInput.removeAttribute('maxlength');
        }
        formatIdProofNumber(numberInput);
    }

    function handlePhotoAreaClick(event) {
        if (event) event.stopPropagation();
        const photo = document.getElementById('photo');
        if (photo) photo.click();
    }

    function handleUploadAreaClick(event) {
        if (event) {
            if (event.target.closest('#docActionsPrompt') || event.target.id === 'idProofPreview') {
                return;
            }
        }
        const doc = document.getElementById('id_proof_document');
        if (doc) doc.click();
    }

    function triggerIdDocumentUpload(event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        const docInput = document.getElementById('id_proof_document');
        if (docInput) {
            docInput.click();
        }
    }

    function openPhotoModal(event) {
        if (event) event.stopPropagation();
        const photo = document.getElementById('photo');
        if (photo) photo.click();
    }

    function openDocumentModal(event) {
        if (event) event.stopPropagation();
        const container = document.getElementById('documentPreviewContainer');
        if (!container) return;

        const docPath = container.getAttribute('data-doc-path');
        const isPdf = container.getAttribute('data-is-pdf') === 'true';

        const modalImg = document.getElementById('modalDocumentPreview');
        const modalPdf = document.getElementById('modalPdfPreview');
        const downloadBtn = document.getElementById('modalDocDownloadBtn');

        if (downloadBtn) {
            if (docPath) {
                downloadBtn.href = docPath;
                downloadBtn.classList.remove('d-none');
            } else {
                downloadBtn.classList.add('d-none');
            }
        }

        if (isPdf) {
            if (modalImg) modalImg.classList.add('d-none');
            if (modalPdf) {
                modalPdf.src = docPath;
                modalPdf.classList.remove('d-none');
            }
        } else {
            if (modalPdf) {
                modalPdf.src = '';
                modalPdf.classList.add('d-none');
            }
            if (modalImg) {
                modalImg.src = docPath;
                modalImg.classList.remove('d-none');
            }
        }

        const documentModal = document.getElementById('documentModal');
        if (documentModal) {
            if (documentModal.parentElement !== document.body) {
                document.body.appendChild(documentModal);
            }
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(documentModal);
                modalInstance.show();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateIdProofValidation();

        const typeSelect = document.getElementById('id_proof_type');
        if (typeSelect) {
            typeSelect.addEventListener('change', updateIdProofValidation);
        }

        const numberInput = document.getElementById('id_proof_number');
        if (numberInput) {
            numberInput.addEventListener('input', function () {
                formatIdProofNumber(this);
            });
        }

        // Photo upload listener
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');

        if (photoInput) {
            photoInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                const fileName = file.name || '';
                const fileExt = fileName.substring(fileName.lastIndexOf('.')).toLowerCase();
                const validPhotoExts = ['.jpg', '.jpeg', '.png'];
                const validPhotoTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/pjpeg'];

                if (!validPhotoExts.includes(fileExt) || (file.type && !validPhotoTypes.includes(file.type.toLowerCase()))) {
                    alert('Invalid file format! Profile photo must be a JPG, JPEG, or PNG file.');
                    this.value = '';
                    return;
                }

                if (file.size > 500 * 1024) {
                    alert('Profile photo size must be less than 500KB!');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (photoPreview) photoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        // ID Proof document listener
        const docInput = document.getElementById('id_proof_document');
        const idProofPreview = document.getElementById('idProofPreview');
        const pdfChip = document.getElementById('pdfChip');
        const uploadPrompt = document.getElementById('uploadPrompt');
        const docActionsPrompt = document.getElementById('docActionsPrompt');
        const container = document.getElementById('documentPreviewContainer');

        if (docInput) {
            docInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                const fileName = file.name || '';
                const fileExt = fileName.substring(fileName.lastIndexOf('.')).toLowerCase();
                const validExtensions = ['.pdf', '.jpg', '.jpeg', '.png'];
                const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'image/pjpeg'];

                if (!validExtensions.includes(fileExt) || (file.type && !validTypes.includes(file.type.toLowerCase()))) {
                    alert('Invalid file format! ID Proof document must be a PDF, JPG, JPEG, or PNG file.');
                    this.value = '';
                    return;
                }

                if (file.size > 2500 * 1024) {
                    alert('Document size must be less than 2,500KB (2.5MB)!');
                    this.value = '';
                    return;
                }

                const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

                if (container) container.style.display = 'block';
                if (uploadPrompt) uploadPrompt.style.display = 'none';
                if (docActionsPrompt) docActionsPrompt.style.display = 'flex';

                if (isPdf) {
                    if (idProofPreview) idProofPreview.classList.add('d-none');
                    if (pdfChip) pdfChip.classList.remove('d-none');
                    if (container) {
                        container.setAttribute('data-is-pdf', 'true');
                        container.setAttribute('data-doc-path', URL.createObjectURL(file));
                    }
                } else {
                    if (pdfChip) pdfChip.classList.add('d-none');
                    if (idProofPreview) {
                        idProofPreview.classList.remove('d-none');
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            idProofPreview.src = e.target.result;
                            if (container) {
                                container.setAttribute('data-is-pdf', 'false');
                                container.setAttribute('data-doc-path', e.target.result);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        }

        const documentModal = document.getElementById('documentModal');
        if (documentModal && documentModal.parentElement !== document.body) {
            document.body.appendChild(documentModal);
        }
        const photoModal = document.getElementById('photoModal');
        if (photoModal && photoModal.parentElement !== document.body) {
            document.body.appendChild(photoModal);
        }
    });

    document.addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });

    // Delegated click handler for step1 elements
    document.addEventListener('click', function (e) {
        const previewBtn = e.target.closest('[data-action="open-document-modal"]');
        if (previewBtn) {
            openDocumentModal(e);
            return;
        }

        const uploadTriggerBtn = e.target.closest('[data-action="trigger-id-upload"]');
        if (uploadTriggerBtn) {
            triggerIdDocumentUpload(e);
            return;
        }

        const uploadArea = e.target.closest('[data-action="handle-upload-area"]');
        if (uploadArea) {
            handleUploadAreaClick(e);
            return;
        }
    });

    // Expose for compatibility
    window.formatIdProofNumber = formatIdProofNumber;
    window.updateIdProofValidation = updateIdProofValidation;
    window.handlePhotoAreaClick = handlePhotoAreaClick;
    window.handleUploadAreaClick = handleUploadAreaClick;
    window.triggerIdDocumentUpload = triggerIdDocumentUpload;
    window.openPhotoModal = openPhotoModal;
    window.openDocumentModal = openDocumentModal;

})(window, document);
