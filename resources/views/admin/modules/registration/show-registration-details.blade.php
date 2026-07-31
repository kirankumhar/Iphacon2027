@extends('admin.layouts.main')

@section('admin-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="py-3 mb-4"><span class="invert-text-white">Indian Paid Registration</span></h5>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card bg-primary text-white shadow-sm" style="background: linear-gradient(135deg, #2D69FF 0%, #1A52E0 100%) !important;">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h4 class="text-white mb-1 fw-bold">Registration: {{ $delegate->registration_number }}</h4>
                        <small class="text-white opacity-75"><i class="bx bx-calendar me-1"></i>Submitted on {{ \Carbon\Carbon::parse($delegate->created_at)->format('d M, Y h:i A') }}</small>
                    </div>
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 text-uppercase shadow-sm" style="font-size: 0.85rem; border-radius: 20px;">
                        {{ $delegate->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-user me-2"></i>Personal Details</h5>
                </div>
                <div class="card-body pt-1 pb-3">
                    <table class="table table-sm table-borderless align-middle mb-0" style="font-size: 0.9rem;">
                        <tbody>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-normal py-2" style="width: 38%;">Delegate Name</th>
                                <td class="fw-bold text-dark py-2">{{ $delegate->user->prefix }} {{ $delegate->user->full_name }}</td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-normal py-2">Gender & DOB</th>
                                <td class="fw-semibold text-dark py-2">{{ $delegate->user->gender }} | {{ date('d-m-Y', strtotime($delegate->user->date_of_birth)) }}</td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-normal py-2">Email ID</th>
                                <td class="fw-semibold text-dark py-2">{{ $delegate->user->email }}</td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-normal py-2">Mobile / WhatsApp</th>
                                <td class="fw-semibold text-dark py-2">
                                    <i class="bx bxl-whatsapp text-success me-1"></i>{{ $delegate->user->mobile_country_code }} {{ $delegate->user->mobile_number }}
                                    @if($delegate->whatsapp_number && $delegate->whatsapp_number != $delegate->user->mobile_number)
                                        / {{ $delegate->whatsapp_number }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-normal py-2">Delegate & Category</th>
                                <td class="fw-semibold text-dark py-2">
                                    <span class="badge bg-label-primary px-2 py-1 me-1">{{ $delegate->delegate_type }}</span>
                                    {{ $delegate->delegateCategory->category_name ?? 'N/A' }}
                                </td>
                            </tr>
                            @if($delegate->membership_no)
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-normal py-2">Membership ID</th>
                                <td class="fw-semibold text-dark py-2">{{ $delegate->membership_no }}</td>
                            </tr>
                            @endif
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-normal py-2">Address & Location</th>
                                <td class="fw-semibold text-dark py-2">{{ $delegate->address }} - {{ $delegate->city }}, {{ $delegate?->state?->state_name }} ({{ $delegate->pin_code }}), {{ $delegate->country->country_name }}</td>
                            </tr>
                            @if($delegate->dietary_preference)
                            <tr class="border-bottom border-light">
                                <th class="text-muted fw-normal py-2">Dietary Preference</th>
                                <td class="fw-semibold text-dark py-2">{{ $delegate->dietary_preference }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th class="text-muted fw-normal py-2">ID Proof ({{ $delegate->id_proof_type }})</th>
                                <td class="py-2">
                                    @if($delegate->id_proof_document_path)
                                    <a href="/storage/{{ $delegate->id_proof_document_path }}" target="_blank" class="btn btn-outline-primary btn-xs py-1 px-2">
                                        <i class="bx bx-show me-1"></i> View ID Document
                                    </a>
                                    @else
                                    <span class="text-danger small">No document uploaded</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card mb-4 border-2 border-primary">
                <div class="card-header bg-label-primary">
                    <h5 class="mb-0"><i class="bx bx-wallet me-2"></i>Financial Summary</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Transaction ID</span>
                        <span class="fw-bold">{{ $delegate->latestPayment->transaction_id }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delegate Fee (Excl. GST)</span>
                        <span class="fw-bold">{{ $delegate->delegate_type == 'International' ? '$' : '₹' }}{{ number_format($delegate->delegate_fee, 2) }}</span>
                    </div>
                    @if($delegate->participate_in_cme)
                    <div class="d-flex justify-content-between mb-2">
                        <span>CME / Workshop Fee</span>
                        <span class="fw-bold">₹{{ number_format($delegate->cme_fee ?: 1000, 2) }}</span>
                    </div>
                    @endif
                    @if($delegate->accompanying_persons > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Accompanying Persons ({{ $delegate->accompanying_persons }})</span>
                        <span class="fw-bold">₹{{ number_format($delegate->accompanying_fee ?: ($delegate->accompanying_persons * 4000), 2) }}</span>
                    </div>
                    @endif
                    @if($delegate->delegate_type != 'International')
                    <div class="d-flex justify-content-between mb-2">
                        <span>GST Amount (18%)</span>
                        <span class="fw-bold">₹{{ number_format($delegate->gst_amount, 2) }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Total Amount (Incl. GST)</h5>
                        <h4 class="text-primary mb-0">{{ $delegate->delegate_type == 'International' ? '$' : '₹' }} {{ number_format($delegate->total_amount, 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Downloads</h6>
                    <a href="{{ route('download.receipt', $delegate->registration_number) }}"
                        target="_blank"
                        class="btn btn-danger w-100 mb-2">
                        <i class="bx bxs-file-pdf me-2"></i>Download Acknowledgement
                    </a>
                    <!-- <button class="btn btn-label-secondary w-100">
                        <i class="bx bx-printer me-2"></i>Print Details
                    </button> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection