@extends('shared.auth-delegate')
@php
    $inner_title = '';
@endphp
@section('delegate-content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg border-0" style="border-radius: 15px;">
                    <!-- Progress Bar -->
                    <div class="card-header py-4"
                        style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border-radius: 15px 15px 0 0;">
                        <h3 class="text-white text-center mb-3 fw-bold">
                            <i class="fas fa-edit me-2"></i>Conference Registration
                        </h3>

                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($step / 4) * 100 }}%"
                                aria-valuenow="{{ $step }}" aria-valuemin="0" aria-valuemax="4">
                                Step {{ $step }} of 4
                            </div>
                        </div>

                        <div class="row text-center mt-3">
                            <div class="col-3">
                                <small class="text-white {{ $step >= 1 ? 'fw-bold' : '' }}">
                                    <i class="fas fa-user {{ $step >= 1 ? 'text-warning' : '' }}"></i> Personal Info
                                </small>
                            </div>
                            <div class="col-3">
                                <small class="text-white {{ $step >= 2 ? 'fw-bold' : '' }}">
                                    <i class="fas fa-clipboard-list {{ $step >= 2 ? 'text-warning' : '' }}"></i>
                                    Registration
                                </small>
                            </div>
                            <div class="col-3">
                                <small class="text-white {{ $step >= 3 ? 'fw-bold' : '' }}">
                                    <i class="fas fa-eye {{ $step >= 3 ? 'text-warning' : '' }}"></i> Preview
                                </small>
                            </div>
                            <div class="col-3">
                                <small class="text-white {{ $step >= 4 ? 'fw-bold' : '' }}">
                                    <i class="fas fa-credit-card {{ $step >= 4 ? 'text-warning' : '' }}"></i> Payment
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-5">
                        <form id="wizardForm" method="POST" action="{{ route('registration.store-step', $step) }}"
                            enctype="multipart/form-data">
                            @csrf


                            <!-- Error Summary -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following
                                        errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($step == 1)
                                @include('registration.steps.step1')
                            @elseif($step == 2)
                                @include('registration.steps.step2')
                            @elseif($step == 3)
                                @include('registration.steps.step3')
                            @elseif($step == 4)
                                @include('registration.steps.step4')
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    @php
                                        $stepData = json_encode([
                                            'step' => $step - 1,
                                            'uid' => auth()->id(),
                                        ]);

                                        $encryptedToken = Crypt::encryptString($stepData);

                                    @endphp

                                    @if ($step > 1)
                                        <a href="{{ route('registration.wizard', ['token' => $encryptedToken]) }}"
                                            class="btn btn-outline-secondary btn-lg px-4">
                                            <i class="fas fa-arrow-left me-2"></i>Previous
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-6 text-end">

                                    @if ($step < 4)
                                        <button type="button" class="btn btn-info btn-lg px-4 me-2" onclick="saveDraft()">
                                            <i class="fas fa-save me-2"></i>Save Draft
                                        </button>

                                        <button type="submit" class="btn btn-primary btn-lg px-4"
                                            style="background: linear-gradient(135deg, #2e3192, #4a5bcc); border: none;">
                                            <i class="fas fa-arrow-right me-2"></i>Next
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success btn-lg px-4">
                                            <i class="fas fa-check me-2"></i>Complete Registration
                                        </button>
                                    @endif
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function saveDraft() {
            const formData = new FormData(document.getElementById('wizardForm'));
            formData.append('action', 'save_draft');

            fetch('{{ route('registration.store-step', $step) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Draft saved successfully!');
                    } else {
                        alert('Error saving draft. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error saving draft. Please try again.');
                });
        }

        // Dynamic form updates based on selections
        @if ($step == 2)
            document.addEventListener('DOMContentLoaded', function() {
                const delegateTypeInputs = document.querySelectorAll('input[name="delegate_type"]');
                const feeFields = document.querySelectorAll('.fee-field');

                delegateTypeInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        if (this.value === 'Foreign') {
                            feeFields.forEach(field => {
                                field.style.display = 'none';
                                field.querySelectorAll('input, select').forEach(el => el
                                    .disabled = true);
                            });
                            document.getElementById('total-amount').textContent = '$175.00';
                        } else {
                            feeFields.forEach(field => {
                                field.style.display = 'block';
                                field.querySelectorAll('input, select').forEach(el => el
                                    .disabled = false);
                            });
                            calculateTotal();
                        }
                    });
                });

                function calculateTotal() {
                    let total = 0;

                    // Delegate category fee
                    const categorySelect = document.querySelector('select[name="delegate_category_id"]');
                    if (categorySelect && categorySelect.value) {
                        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                        const fee = parseInt(selectedOption.dataset.fee || 0);
                        total += fee;
                    }

                    // Accompanying persons fee
                    const accompanyingPersons = parseInt(document.querySelector(
                        'input[name="accompanying_persons"]')?.value || 0);
                    total += accompanyingPersons * 4000;

                    // CME fee
                    const cmeCheckbox = document.querySelector('input[name="participate_in_cme"]');
                    if (cmeCheckbox && cmeCheckbox.checked) {
                        total += 1500;
                    }

                    document.getElementById('total-amount').textContent = '₹' + total.toLocaleString('en-IN') +
                        '.00';
                }

                // Add event listeners for fee calculation
                document.querySelector('select[name="delegate_category_id"]')?.addEventListener('change',
                    calculateTotal);
                document.querySelector('input[name="accompanying_persons"]')?.addEventListener('input',
                    calculateTotal);
                document.querySelector('input[name="participate_in_cme"]')?.addEventListener('change',
                    calculateTotal);
            });
        @endif
    </script>
@endsection
