@extends('shared.auth-delegate')
@section('title', 'Registration Details')
@section('delegate-content')
    <div class="container py-4">
        <h2 class="mb-4">Registration Details</h2>

        <div class="card">
            <div class="card-body">
                <p><strong>Registration Number:</strong> {{ $registration->registration_number }}</p>
                <p><strong>Delegate Name:</strong> {{ $registration->user->full_name }}</p>
                <p><strong>Delegate Type:</strong> {{ $registration->delegate_type }}</p>

                @if ($registration->delegate_type == 'Indian')
                    <p><strong>Category:</strong> {{ $registration->delegateCategory->category_name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> {{ $registration->status }}</p>
                @endif

                <p><strong>Registered On:</strong> {{ $registration->created_at->format('d M, Y') }}</p>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Back to Dashboard</a>
            </div>
        </div>
    </div>
@endsection
