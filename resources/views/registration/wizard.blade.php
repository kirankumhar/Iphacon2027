@extends('shared.auth-delegate')
@php
    $inner_title = 'Registration progress';
@endphp
@section('delegate-content')
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">
                <div class="card wizard-card shadow-lg border-0" style="border-radius: 16px; overflow: hidden;">
                    
                    <!-- Stepper Custom CSS -->
                    <style>
                        .wizard-header {
                            background: linear-gradient(135deg, #0288D1 0%, #01579B 60%, #00897B 100%);
                            padding: 14px 20px;
                            border-bottom: 3px solid #FF6B00;
                        }
                        .stepper-container {
                            position: relative;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            max-width: 680px;
                            margin: 10px auto 2px auto;
                        }
                        .stepper-line-bg {
                            position: absolute;
                            top: 16px;
                            left: 10%;
                            right: 10%;
                            height: 3px;
                            background: rgba(255, 255, 255, 0.25);
                            z-index: 1;
                        }
                        .stepper-line-fill {
                            position: absolute;
                            top: 16px;
                            left: 10%;
                            height: 3px;
                            background: #FF6B00;
                            z-index: 1;
                            transition: width 0.4s ease;
                        }
                        .step-node {
                            position: relative;
                            z-index: 2;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            text-decoration: none !important;
                        }
                        .step-circle {
                            width: 32px;
                            height: 32px;
                            border-radius: 50%;
                            background: rgba(255, 255, 255, 0.2);
                            color: #ffffff;
                            border: 2px solid rgba(255, 255, 255, 0.5);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: 700;
                            font-size: 0.85rem;
                            transition: all 0.3s ease;
                        }
                        .step-node.completed .step-circle {
                            background: #4BAA7D;
                            border-color: #ffffff;
                            color: #ffffff;
                        }
                        .step-node.active .step-circle {
                            background: #FF6B00;
                            border-color: #ffffff;
                            color: #ffffff;
                            box-shadow: 0 0 0 4px rgba(255, 107, 0, 0.35);
                            transform: scale(1.08);
                        }
                        .step-label {
                            margin-top: 4px;
                            font-size: 0.72rem;
                            font-weight: 600;
                            color: rgba(255, 255, 255, 0.85);
                            text-align: center;
                            white-space: nowrap;
                        }
                        .step-node.active .step-label {
                            color: #ffffff;
                            font-weight: 700;
                        }
                        .step-node.completed .step-label {
                            color: #E0F2FE;
                        }
                        .compact-form-body {
                            padding: 22px 28px;
                        }
                        @media (max-width: 576px) {
                            .compact-form-body {
                                padding: 16px 14px;
                            }
                            .step-label {
                                font-size: 0.68rem;
                            }
                            .step-circle {
                                width: 28px;
                                height: 28px;
                                font-size: 0.78rem;
                            }
                            .stepper-line-bg, .stepper-line-fill {
                                top: 14px;
                            }
                        }
                    </style>

                    <!-- Modern Stepper Header -->
                    <div class="wizard-header text-white">
                        <div class="text-center mb-1">
                            <h5 class="text-white mb-0.5 fw-bold" style="letter-spacing: 0.3px; font-size: 1.15rem;">
                                <i class="fas fa-edit me-1.5 text-warning"></i>IPHACON 2027 Registration Portal
                            </h5>
                        </div>

                        <!-- Progress Stepper Component -->
                        <div class="stepper-container px-2">
                            <div class="stepper-line-bg"></div>
                            @php
                                $fillPercentage = match($step) {
                                    1 => '0%',
                                    2 => '27%',
                                    3 => '54%',
                                    4 => '80%',
                                    default => '0%'
                                };
                            @endphp
                            <div class="stepper-line-fill" style="width: {{ $fillPercentage }};"></div>

                            <!-- Step 1 Node -->
                            <a href="{{ route('registration.create', ['step' => 1]) }}" class="step-node {{ $step > 1 ? 'completed' : ($step == 1 ? 'active' : '') }}" title="Step 1: Personal Info">
                                <div class="step-circle">
                                    @if($step > 1) <i class="fas fa-check fs-6"></i> @else 1 @endif
                                </div>
                                <span class="step-label"><i class="fas fa-user me-1"></i> Personal Info</span>
                            </a>

                            <!-- Step 2 Node -->
                            <a href="{{ route('registration.create', ['step' => 2]) }}" class="step-node {{ $step > 2 ? 'completed' : ($step == 2 ? 'active' : '') }}" title="Step 2: Registration Category">
                                <div class="step-circle">
                                    @if($step > 2) <i class="fas fa-check fs-6"></i> @else 2 @endif
                                </div>
                                <span class="step-label"><i class="fas fa-clipboard-list me-1"></i> Registration</span>
                            </a>

                            <!-- Step 3 Node -->
                            <a href="{{ route('registration.create', ['step' => 3]) }}" class="step-node {{ $step > 3 ? 'completed' : ($step == 3 ? 'active' : '') }}" title="Step 3: Preview">
                                <div class="step-circle">
                                    @if($step > 3) <i class="fas fa-check fs-6"></i> @else 3 @endif
                                </div>
                                <span class="step-label"><i class="fas fa-eye me-1"></i> Preview</span>
                            </a>

                            <!-- Step 4 Node -->
                            <a href="{{ route('registration.create', ['step' => 4]) }}" class="step-node {{ $step == 4 ? 'active' : '' }}" title="Step 4: Payment Upload">
                                <div class="step-circle">4</div>
                                <span class="step-label"><i class="fas fa-credit-card me-1"></i> Payment</span>
                            </a>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <div class="card-body compact-form-body">
                        <!-- Reverted Alert Banner in Wizard -->
                        @if ($registration && !empty($registration->revert_reason) && ($registration->status === 'Draft' || $registration->status === 'Reverted' || !empty($registration->reverted_at)))
                            <div class="alert alert-warning border-0 shadow-sm p-3.5 mb-4 position-relative overflow-hidden" style="border-radius: 12px; background: #FFFBEB; border-left: 5px solid #F59E0B !important;">
                                <div class="d-flex align-items-start gap-2.5">
                                    <i class="fas fa-exclamation-triangle fs-4 text-warning mt-0.5"></i>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Application Reverted for Modification</h6>
                                        <p class="mb-0 text-dark small" style="font-size: 0.85rem; line-height: 1.4;">
                                            <strong>Admin Reason:</strong> "{{ $registration->revert_reason }}"
                                        </p>
                                        <small class="text-muted extra-small d-block mt-1">Please make the required changes below and proceed to step 4 to resubmit your payment details.</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form id="wizardForm" method="POST" action="{{ route('registration.store-step', $step) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Error Summary Alert -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 small mb-4" role="alert" style="border-radius: 10px;">
                                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Please resolve the following inputs:</strong>
                                    <ul class="mb-0 mt-1.5 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show py-2.5 px-3 small mb-4" role="alert" style="border-radius: 10px;">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Step Partial View Include -->
                            @if ($step == 1)
                                @include('registration.steps.step1')
                            @elseif($step == 2)
                                @include('registration.steps.step2')
                            @elseif($step == 3)
                                @include('registration.steps.step3')
                            @elseif($step == 4)
                                @include('registration.steps.step4')
                            @endif

                            <!-- Compact Navigation Action Bar -->
                            <div class="d-flex align-items-center justify-content-between pt-4 mt-4 border-top flex-wrap gap-2">
                                <div>
                                    @php
                                        $stepData = json_encode([
                                            'step' => $step - 1,
                                            'uid' => auth()->id(),
                                        ]);

                                        $encryptedToken = Crypt::encryptString($stepData);
                                    @endphp

                                    @if ($step > 1)
                                        <a href="{{ route('registration.wizard', ['token' => $encryptedToken]) }}"
                                            class="btn btn-outline-secondary px-4 py-2 fw-semibold" style="border-radius: 10px;">
                                            <i class="fas fa-arrow-left me-2"></i>Previous Step
                                        </a>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($step < 4)
                                        <button type="button" class="btn btn-outline-primary px-3.5 py-2 fw-semibold me-2" onclick="saveDraft()" style="border-radius: 10px;">
                                            <i class="fas fa-save me-1.5"></i>Save Draft
                                        </button>

                                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm"
                                            style="background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%); border: none; border-radius: 10px;">
                                            Next Step <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success px-4 py-2.5 fw-bold shadow-sm" style="border-radius: 10px;">
                                            <i class="fas fa-check-circle me-2"></i>Complete Registration
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
                            document.getElementById('total-amount').textContent = '₹45,000.00';
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
                        total += 2000;
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
