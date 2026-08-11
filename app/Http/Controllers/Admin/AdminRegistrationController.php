<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class AdminRegistrationController extends Controller
{

    public function getRegistrations(Request $request, $type)
    {
        $query = Registration::query();

        $query->leftJoin('payments', 'registrations.id', '=', 'payments.registration_id');
        $query->join('users', 'users.id', '=', 'registrations.user_id');
        $query->join('delegate_categories', 'delegate_categories.id', '=', 'registrations.delegate_category_id')
            ->select(
                'delegate_categories.category_name as type',
                'registration_pdf_path',
                'photo_path',
                'prefix',
                'full_name',
                'date_of_birth',
                'registration_number',
                'transaction_id',
                'payments.payment_receipt_path',
                'revert_reason',
                'rejection_reason',
                'payments.total_amount'
            )->where('registrations.is_deleted', '0');

        switch ($type) {
            case 'ind-paid':
                $query->where('registrations.status', 'Approved')
                    ->where(function ($q) {
                        $q->where('registrations.delegate_type', 'Indian')
                          ->orWhere('users.delegate_type', 'Indian')
                          ->orWhereNull('registrations.delegate_type');
                    });
                $totalRecords = Registration::where('status', 'Approved')
                    ->where(function ($q) {
                        $q->where('delegate_type', 'Indian')
                          ->orWhereHas('user', function ($uq) {
                              $uq->where('delegate_type', 'Indian');
                          })
                          ->orWhereNull('delegate_type');
                    })
                    ->where('is_deleted', '0')
                    ->count();
                break;
            case 'approved':
                $query->where('registrations.status', 'Approved')
                    ->where(function ($q) {
                        $q->where('registrations.delegate_type', 'International')
                          ->orWhere('users.delegate_type', 'International');
                    });
                $totalRecords = Registration::where('status', 'Approved')
                    ->where(function ($q) {
                        $q->where('delegate_type', 'International')
                          ->orWhereHas('user', function ($uq) {
                              $uq->where('delegate_type', 'International');
                          });
                    })
                    ->where('is_deleted', '0')
                    ->count();
                break;
            case 'reject':
                $query->where('registrations.status', 'Rejected');
                $totalRecords = Registration::where('status', 'Rejected')->where('is_deleted', '0')->count();
                break;
            case 'revert':
                $query->where('registrations.status', 'Draft')->where('step_completed', 4)->where('reverted_at', '<>', null);
                $totalRecords = Registration::where('status', 'Rejected')->where('is_deleted', '0')->count();
                break;
            case 'pending':
                $query->where('registrations.status', 'Payment Submitted');
                $totalRecords = Registration::where('status', 'Payment Submitted')->where('is_deleted', '0')->count();
                break;
            case 'incomplete':
                $query->where('registrations.status', 'Draft');
                $totalRecords = Registration::where('status', 'Draft')->where('is_deleted', '0')->count();
                break;
            default:
                return response()->json(['error' => 'Invalid route type'], 400);
        }

        // if ($request->input('course_type')) {
        //     $query->where('apply_class', $request->input('course_type'));
        // }

        // if ($request->input('session')) {
        //     $query->where('session_year', $request->input('session'));
        // }

        if ($request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('full_name', 'like', "%{$searchValue}%")
                    ->orWhere('registration_number', 'like', "%{$searchValue}%")
                    ->orWhere('transaction_id', 'like', "%{$searchValue}%");
            });
        }

        $filteredRecords = $query->count();

        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderColumn = $request->input("columns.{$orderColumnIndex}.name");
            $orderDir = $request->input('order.0.dir');
            $query->orderBy($orderColumn, $orderDir);
        }

        // Apply pagination
        $students = $query->skip($request->input('start', 0))
            ->take($request->input('length', 25))
            ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $students
        ]);
    }

    public function submittedDelegates()
    {
        $registrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where(function ($q) {
                $q->whereIn('status', ['Payment Submitted', 'Submitted', 'Pending Payment'])
                  ->orWhereHas('latestPayment');
            })
            ->whereNotIn('status', ['Approved', 'Rejected', 'Draft'])
            ->where('is_deleted', '0')
            ->latest()
            ->get();

        return view('admin.modules.registration.show-submitted-registration', compact('registrations'));
    }

    public function internationalPaymentSubmittedDelegates()
    {
        $registrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where(function ($q) {
                $q->whereIn('status', ['Payment Submitted', 'Submitted', 'Pending Payment'])
                  ->orWhereHas('latestPayment');
            })
            ->whereNotIn('status', ['Approved', 'Rejected', 'Draft'])
            ->where(function ($q) {
                $q->where('delegate_type', 'International')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('delegate_type', 'International');
                  });
            })
            ->where('is_deleted', '0')
            ->latest()
            ->get();

        return view('admin.modules.registration.show-int-payment-submitted-registration', compact('registrations'));
    }

    public function approvedIndDelegates()
    {
        $registrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where('status', 'Approved')
            ->where(function ($q) {
                $q->where('delegate_type', 'Indian')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('delegate_type', 'Indian');
                  })
                  ->orWhereNull('delegate_type');
            })
            ->where('is_deleted', '0')
            ->latest()
            ->get();

        return view('admin.modules.registration.show-ind-approved-registration', compact('registrations'));
    }

    public function indianIncompleteDelegates()
    {
        $registrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where(function ($q) {
                $q->where('status', 'Draft')
                  ->orWhere('step_completed', '<', 4)
                  ->orWhereNull('status');
            })
            ->where('is_deleted', '0')
            ->latest()
            ->get();

        $registeredUserIds = Registration::where('is_deleted', '0')->pluck('user_id')->toArray();
        $usersWithoutReg = \App\Models\User::whereNotIn('id', $registeredUserIds)->latest()->get();

        return view('admin.modules.registration.show-ind-incomplete-registration', compact('registrations', 'usersWithoutReg'));
    }

    public function internationalApprovedDelegates()
    {
        $registrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where('status', 'Approved')
            ->where(function ($q) {
                $q->where('delegate_type', 'International')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('delegate_type', 'International');
                  });
            })
            ->where('is_deleted', '0')
            ->latest()
            ->get();

        return view('admin.modules.registration.show-int-approved-registration', compact('registrations'));
    }

    public function internationalRejectedDelegates()
    {
        $registrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where('status', 'Rejected')
            ->where('is_deleted', '0')
            ->latest()
            ->get();

        return view('admin.modules.registration.show-int-rejected-registration', compact('registrations'));
    }

    public function internationalRevertedDelegates()
    {
        return view('admin.modules.registration.show-int-reverted-registration');
    }

    public function cmeDelegates()
    {
        $registrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where('participate_in_cme', 1)
            ->where('is_deleted', '0')
            ->latest()
            ->get();

        return view('admin.modules.registration.show-cme-registration', compact('registrations'));
    }

    public function paidPayments()
    {
        $payments = \App\Models\Payment::with(['registration.user'])
            ->whereIn('payment_status', ['Success', 'PAID', 'Approved', 'Completed'])
            ->latest()
            ->get();

        return view('admin.modules.payments.paid-payments', compact('payments'));
    }

    public function pendingPayments()
    {
        $payments = \App\Models\Payment::with(['registration.user', 'registration.delegateCategory'])
            ->whereIn('payment_status', ['Pending', 'Payment Submitted', 'Submitted', 'UNDER_VERIFICATION', 'In Process'])
            ->orWhereNull('payment_status')
            ->latest()
            ->get();

        $pendingRegistrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->whereIn('status', ['Payment Submitted', 'Pending Payment', 'Submitted'])
            ->where('is_deleted', '0')
            ->latest()
            ->paginate(10);

        return view('admin.modules.payments.pending-payments', compact('payments', 'pendingRegistrations'));
    }

    public function failedPayments()
    {
        $payments = \App\Models\Payment::with(['registration.user'])
            ->whereIn('payment_status', ['Failed', 'Failure', 'Rejected', 'CANCELLED'])
            ->latest()
            ->get();

        return view('admin.modules.payments.failed-payments', compact('payments'));
    }

    public function deletedRegistration(Request $request)
    {
        $deletedStuList = Registration::where('is_deleted', '1')->get();

        return view('admin.modules.registration.show-deleted-registration', compact('deletedStuList'));
    }

    public function approvedRegis(Request $request)
    {
        $regId = $request->input('registration_id') ?? $request->input('id');
        $regNo = $request->input('registration_number') ?? $request->input('acknowledgement_id');

        $registration = Registration::when($regId, function($q) use ($regId) {
            $q->where('id', $regId);
        })->when(!$regId && $regNo, function($q) use ($regNo) {
            $q->where('registration_number', $regNo)
              ->orWhere('acknowledgement_id', $regNo);
        })->first();

        if ($registration) {
            $isPaymentSubmitted = in_array($registration->status, ['Payment Submitted', 'Submitted', 'Pending Payment']) || !empty($registration->latestPayment);
            if (!$isPaymentSubmitted && $registration->status !== 'Approved') {
                return redirect()->back()->with('error', "Approval failed. Delegate must submit payment before approval can be granted.");
            }

            if (empty($registration->registration_number)) {
                $registration->registration_number = $registration->generateRegistrationNumber();
            }
            if (empty($registration->acknowledgement_id)) {
                $registration->acknowledgement_id = $registration->generateAcknowledgementId();
            }
            $registration->status = 'Approved';
            $registration->approved_at = now();
            $registration->save();

            // Also update associated payment records status to PAID
            $registration->payments()->update([
                'payment_status' => 'PAID',
                'admin_verified' => true
            ]);

            // Record Activity Log
            \App\Models\ActivityLog::record(
                'ADMIN_APPROVE_REGISTRATION',
                "Approved registration for " . ($registration->user?->full_name ?? 'User') . ". Reg No: {$registration->registration_number}",
                ['registration_id' => $registration->id, 'registration_number' => $registration->registration_number, 'acknowledgement_id' => $registration->acknowledgement_id],
                \Illuminate\Support\Facades\Auth::guard('admin')->user()
            );

            return redirect()->back()->with('success', "Registration successfully marked Approved. Reg No: {$registration->registration_number}");
        }

        return redirect()->back()->with('error', 'Registration record not found.');
    }

    public function rejectRegis(Request $request)
    {
        $regNo = $request->input('registration_id') ?? $request->input('registration_number') ?? $request->input('acknowledgement_id') ?? $request->input('id');
        $reason = $request->input('reason') ?? $request->input('rejection_reason') ?? null;

        $reg = Registration::where(function($q) use ($regNo) {
            $q->where('id', $regNo)
              ->orWhere('registration_number', $regNo)
              ->orWhere('acknowledgement_id', $regNo);
        })->first();

        if ($reg) {
            if ($reg->status === 'Approved') {
                return redirect()->back()->with('error', "Approved registrations cannot be rejected.");
            }

            $reg->update([
                'status' => 'Rejected',
                'registration_number' => null,
                'rejection_reason' => $reason,
                'rejected_at' => now(),
            ]);

            \App\Models\ActivityLog::record(
                'ADMIN_REJECT_REGISTRATION',
                "Rejected registration for " . ($reg->user?->full_name ?? 'User') . ". Reason: " . ($reason ?? 'N/A'),
                ['registration_id' => $reg->id, 'reason' => $reason],
                \Illuminate\Support\Facades\Auth::guard('admin')->user()
            );

            // If coming from details page, redirect to the details page using permanent acknowledgement_id or id
            $previousUrl = url()->previous();
            if (str_contains($previousUrl, 'show-registration-details')) {
                return redirect()->route('show-registration-details', $reg->acknowledgement_id ?? $reg->id)
                    ->with('success', "Registration successfully marked Rejected.");
            }
        }

        return redirect()->back()->with('success', "Registration successfully marked Rejected.");
    }

    public function revertRegis(Request $request)
    {
        $regNo = $request->input('registration_id') ?? $request->input('registration_number') ?? $request->input('acknowledgement_id') ?? $request->input('id');
        $reason = $request->input('reason') ?? $request->input('revert_reason') ?? null;

        $reg = Registration::where(function($q) use ($regNo) {
            $q->where('id', $regNo)
              ->orWhere('registration_number', $regNo)
              ->orWhere('acknowledgement_id', $regNo);
        })->first();

        if ($reg) {
            if ($reg->status === 'Approved') {
                return redirect()->back()->with('error', "Approved registrations cannot be reverted.");
            }

            $reg->update([
                'status' => 'Draft',
                'registration_number' => null,
                'reverted_at' => now(),
                'revert_reason' => $reason,
            ]);

            \App\Models\ActivityLog::record(
                'ADMIN_REVERT_REGISTRATION',
                "Reverted registration to Draft for " . ($reg->user?->full_name ?? 'User'),
                ['registration_id' => $reg->id],
                \Illuminate\Support\Facades\Auth::guard('admin')->user()
            );

            $previousUrl = url()->previous();
            if (str_contains($previousUrl, 'show-registration-details')) {
                return redirect()->route('show-registration-details', $reg->acknowledgement_id ?? $reg->id)
                    ->with('success', "Registration successfully reverted to Draft.");
            }
        }

        return redirect()->back()->with('success', "Registration successfully marked Reverted.");
    }

    public function deleteRegis(Request $request)
    {
        $regNo = $request->input('registration_number') ?? $request->input('acknowledgement_id') ?? $request->input('id');

        Registration::where(function($q) use ($regNo) {
            $q->where('registration_number', $regNo)
              ->orWhere('acknowledgement_id', $regNo)
              ->orWhere('id', $regNo);
        })->update([
            'is_deleted' => '1',
            'deleted_datetime' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', "Registration successfully deleted.");
    }

    public function receiptCumRegistrationSlipDownload($id)
    {

        $delegate = Registration::with([
            'user',
            'latestPayment'
        ])->where(function($q) use ($id) {
            $q->where('registration_number', $id)
              ->orWhere('acknowledgement_id', $id)
              ->orWhere('id', $id);
        })->latest('created_at')->firstOrFail();

        $pdf = PDF::loadView('pdfs.registration', [
            'registration' => $delegate,
            'applicationNumber' => $delegate->registration_number ?? $delegate->acknowledgement_id
        ])->setPaper('a4', 'portrait')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        return $pdf->download("Receipt-{$id}.pdf");
    }

    public function viewRegistrationDetails($id)
    {
        $delegate = Registration::with([
            'user',
            'latestPayment',
            'delegateCategory',
            'country',
            'state'
        ])->where(function($q) use ($id) {
            $q->where('registration_number', $id)
              ->orWhere('acknowledgement_id', $id)
              ->orWhere('id', $id);
        })->latest('created_at')->firstOrFail();

        return view('admin.modules.registration.show-registration-details', compact('delegate'));
    }
}
