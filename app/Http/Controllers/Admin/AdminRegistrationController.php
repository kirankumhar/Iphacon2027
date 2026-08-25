<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

    public function indianIncompleteDelegates(Request $request)
    {
        $draftSearch = trim($request->input('draft_search', ''));
        $userSearch = trim($request->input('user_search', ''));

        $registrationsQuery = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where(function ($q) {
                $q->where('status', 'Draft')
                  ->orWhere('step_completed', '<', 4)
                  ->orWhereNull('status');
            })
            ->where('is_deleted', '0');

        if (!empty($draftSearch)) {
            $registrationsQuery->where(function ($q) use ($draftSearch) {
                $q->whereHas('user', function ($uq) use ($draftSearch) {
                    $uq->where('full_name', 'like', "%{$draftSearch}%")
                       ->orWhere('email', 'like', "%{$draftSearch}%")
                       ->orWhere('mobile_number', 'like', "%{$draftSearch}%");
                })
                ->orWhereHas('delegateCategory', function ($cq) use ($draftSearch) {
                    $cq->where('category_name', 'like', "%{$draftSearch}%");
                })
                ->orWhere('delegate_type', 'like', "%{$draftSearch}%");
            });
        }

        $registrations = $registrationsQuery->latest()
            ->paginate(10, ['*'], 'draft_page')
            ->withQueryString();

        $registeredUserIds = Registration::where('is_deleted', '0')->pluck('user_id')->toArray();
        $usersWithoutRegQuery = \App\Models\User::whereNotIn('id', $registeredUserIds);

        if (!empty($userSearch)) {
            $usersWithoutRegQuery->where(function ($q) use ($userSearch) {
                $q->where('full_name', 'like', "%{$userSearch}%")
                   ->orWhere('email', 'like', "%{$userSearch}%")
                   ->orWhere('mobile_number', 'like', "%{$userSearch}%")
                   ->orWhere('delegate_type', 'like', "%{$userSearch}%");
            });
        }

        $usersWithoutReg = $usersWithoutRegQuery->latest()
            ->paginate(10, ['*'], 'user_page')
            ->withQueryString();

        // Fetch all reminders sent today to show status indicators
        $todayReminders = \App\Models\ActivityLog::whereIn('action', ['ADMIN_SEND_PAYMENT_REMINDER', 'ADMIN_SEND_INCOMPLETE_REMINDER'])
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->get()
            ->reduce(function ($carry, $log) {
                $props = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                if (is_array($props)) {
                    $time = $log->created_at->format('h:i A');
                    if (!empty($props['registration_id'])) {
                        $carry['reg_' . $props['registration_id']] = $time;
                    }
                    if (!empty($props['user_id'])) {
                        $carry['user_' . $props['user_id']] = $time;
                    }
                    if (!empty($props['recipient_email'])) {
                        $carry['email_' . strtolower(trim($props['recipient_email']))] = $time;
                    }
                }
                return $carry;
            }, []);

        return view('admin.modules.registration.show-ind-incomplete-registration', compact('registrations', 'usersWithoutReg', 'todayReminders'));
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
        $registrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where(function($q) {
                $q->where('status', 'Reverted')
                  ->orWhereNotNull('reverted_at')
                  ->orWhereNotNull('revert_reason');
            })
            ->where('status', '!=', 'Approved')
            ->where('is_deleted', '0')
            ->latest('reverted_at')
            ->latest()
            ->get();

        return view('admin.modules.registration.show-int-reverted-registration', compact('registrations'));
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
            ->get()
            ->unique(function ($item) {
                $txn = trim($item->transaction_id ?: ($item->gateway_transaction_id ?: ''));
                return $txn !== '' ? $txn : 'payment_' . $item->id;
            })
            ->values();

        return view('admin.modules.payments.paid-payments', compact('payments'));
    }

    public function pendingPayments()
    {
        $payments = \App\Models\Payment::with(['registration.user', 'registration.delegateCategory'])
            ->where(function($q) {
                $q->whereIn('payment_status', ['Pending', 'Payment Submitted', 'Submitted', 'UNDER_VERIFICATION', 'In Process'])
                  ->orWhereNull('payment_status');
            })
            ->latest()
            ->get()
            ->unique(function ($item) {
                $txn = trim($item->transaction_id ?: ($item->gateway_transaction_id ?: ''));
                return $txn !== '' ? $txn : 'payment_' . $item->id;
            })
            ->values();

        $pendingRegistrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->whereIn('status', ['Payment Submitted', 'Pending Payment', 'Submitted'])
            ->where('is_deleted', '0')
            ->latest()
            ->paginate(10);

        // Fetch all reminders sent today to show status indicators
        $todayReminders = \App\Models\ActivityLog::where('action', 'ADMIN_SEND_PAYMENT_REMINDER')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->get()
            ->reduce(function ($carry, $log) {
                $props = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                if (is_array($props)) {
                    $time = $log->created_at->format('h:i A');
                    if (!empty($props['registration_id'])) {
                        $carry['reg_' . $props['registration_id']] = $time;
                    }
                    if (!empty($props['payment_id'])) {
                        $carry['pay_' . $props['payment_id']] = $time;
                    }
                    if (!empty($props['recipient_email'])) {
                        $carry['email_' . strtolower(trim($props['recipient_email']))] = $time;
                    }
                }
                return $carry;
            }, []);

        return view('admin.modules.payments.pending-payments', compact('payments', 'pendingRegistrations', 'todayReminders'));
    }

    public function failedPayments()
    {
        $payments = \App\Models\Payment::with(['registration.user'])
            ->whereIn('payment_status', ['Failed', 'Failure', 'Rejected', 'CANCELLED'])
            ->latest()
            ->get()
            ->unique(function ($item) {
                $txn = trim($item->transaction_id ?: ($item->gateway_transaction_id ?: ''));
                return $txn !== '' ? $txn : 'payment_' . $item->id;
            })
            ->values();

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
            $hasPaymentProof = !empty($registration->latestPayment?->payment_receipt_path) || !empty($registration->latestPayment?->transaction_id);
            $isPaymentSubmitted = ($registration->status === 'Payment Submitted') || ($hasPaymentProof && !in_array($registration->status, ['Pending Payment', 'Draft', 'Incomplete', 'Rejected']));
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

            // Generate Registration Receipt PDF & Save
            $pdfPath = null;
            try {
                $registration->loadMissing(['user', 'delegateCategory', 'country', 'state', 'latestPayment']);
                $pdf = Pdf::loadView('pdfs.registration', [
                    'registration' => $registration,
                    'applicationNumber' => $registration->registration_number
                ])->setPaper('a4', 'portrait')
                    ->setOption('margin-top', 10)
                    ->setOption('margin-bottom', 10)
                    ->setOption('margin-left', 10)
                    ->setOption('margin-right', 10);

                $year = now()->format('Y');
                $month = now()->format('m');
                $filename = "Delegate_Registration_{$registration->registration_number}.pdf";
                $pdfPath = "registrations_receipt/{$year}/{$month}/{$filename}";

                Storage::disk('public')->put($pdfPath, $pdf->output());

                $registration->update([
                    'registration_pdf_path' => $pdfPath
                ]);
            } catch (\Exception $pdfEx) {
                Log::warning('PDF generation during approval failed: ' . $pdfEx->getMessage());
            }

            // Send Confirmation Email with Registration Number & PDF Attachment
            try {
                $recipientEmail = $registration->user?->email;
                if ($recipientEmail) {
                    $registration->loadMissing(['user', 'delegateCategory', 'country', 'state', 'latestPayment']);
                    Mail::send('emails.registration_confirmation', [
                        'registration'   => $registration,
                        'registrationID' => $registration->registration_number,
                    ], function ($message) use ($recipientEmail, $registration, $pdfPath) {
                        $message->to($recipientEmail)
                            ->subject('IPHACON 2027 : Delegate Registration Approved (Reg No: ' . $registration->registration_number . ')')
                            ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));

                        // Attach the PDF file if available
                        if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                            $localPath = storage_path("app/public/{$pdfPath}");
                            $message->attach($localPath, [
                                'as' => "Delegate Registration - {$registration->registration_number}.pdf",
                                'mime' => 'application/pdf'
                            ]);
                        }
                    });
                }
            } catch (\Exception $mailEx) {
                Log::error('Failed to send delegate approval confirmation email: ' . $mailEx->getMessage());
            }

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
        $reason = $request->input('reason') ?? $request->input('rejection_reason') ?? $request->input('revert_reason') ?? $request->input('remarks') ?? null;

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
        $reason = $request->input('reason') ?? $request->input('revert_reason') ?? $request->input('rejection_reason') ?? $request->input('remarks') ?? null;

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

        if ($delegate->status !== 'Approved') {
            return redirect()->back()->with('error', 'Receipt PDF can only be downloaded after registration has been Approved.');
        }

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

        // Check if reminder was already sent today for this delegate
        $todayReminder = \App\Models\ActivityLog::where('action', 'ADMIN_SEND_PAYMENT_REMINDER')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->get()
            ->first(function ($log) use ($delegate) {
                $props = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                if (empty($props)) return false;
                $email = strtolower(trim($delegate->user?->email ?? ''));
                $propEmail = strtolower(trim($props['recipient_email'] ?? ''));
                return (!empty($email) && $email === $propEmail)
                    || (!empty($props['registration_id']) && (string)$props['registration_id'] === (string)$delegate->id)
                    || (!empty($delegate->user_id) && !empty($props['user_id']) && (string)$props['user_id'] === (string)$delegate->user_id);
            });

        $reminderSentTime = $todayReminder ? $todayReminder->created_at->format('h:i A') : null;

        return view('admin.modules.registration.show-registration-details', compact('delegate', 'reminderSentTime'));
    }

    public function resendSubmissionEmail(Request $request)
    {
        $regId = $request->input('registration_id') ?? $request->input('id');
        $regNo = $request->input('registration_number') ?? $request->input('acknowledgement_id');
        $customEmail = trim($request->input('email') ?? '');
        $emailType = $request->input('email_type', 'submission'); // 'submission' or 'approval'

        $registration = Registration::when($regId, function($q) use ($regId) {
            $q->where('id', $regId);
        })->when(!$regId && $regNo, function($q) use ($regNo) {
            $q->where('registration_number', $regNo)
              ->orWhere('acknowledgement_id', $regNo);
        })->first();

        if (!$registration) {
            return redirect()->back()->with('error', 'Registration record not found.');
        }

        $recipientEmail = !empty($customEmail) ? $customEmail : ($registration->user?->email);

        if (empty($recipientEmail)) {
            return redirect()->back()->with('error', 'No recipient email found. Please provide a valid email address.');
        }

        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Invalid email address provided.');
        }

        try {
            $registration->loadMissing(['user', 'delegateCategory', 'country', 'state', 'latestPayment', 'payments']);

            // If user's account has empty email, update it with provided email
            if (!empty($customEmail) && $registration->user && empty($registration->user->email)) {
                $registration->user->update(['email' => $customEmail]);
            }

            $shouldSendApprovalEmail = ($emailType === 'approval') || ($registration->status === 'Approved' && $emailType !== 'submission_only');

            if ($shouldSendApprovalEmail && $registration->status === 'Approved') {
                // Ensure PDF is generated if missing
                $pdfPath = $registration->registration_pdf_path;
                if (!$pdfPath || !Storage::disk('public')->exists($pdfPath)) {
                    try {
                        $pdf = Pdf::loadView('pdfs.registration', [
                            'registration' => $registration,
                            'applicationNumber' => $registration->registration_number ?? $registration->acknowledgement_id
                        ])->setPaper('a4', 'portrait')
                            ->setOption('margin-top', 10)
                            ->setOption('margin-bottom', 10)
                            ->setOption('margin-left', 10)
                            ->setOption('margin-right', 10);

                        $year = now()->format('Y');
                        $month = now()->format('m');
                        $filename = "Delegate_Registration_{$registration->registration_number}.pdf";
                        $pdfPath = "registrations_receipt/{$year}/{$month}/{$filename}";

                        Storage::disk('public')->put($pdfPath, $pdf->output());

                        $registration->update([
                            'registration_pdf_path' => $pdfPath
                        ]);
                    } catch (\Exception $pdfEx) {
                        Log::warning('PDF generation during resend email failed: ' . $pdfEx->getMessage());
                    }
                }

                // Send Confirmation Email with Registration Number & PDF Attachment
                Mail::send('emails.registration_confirmation', [
                    'registration'   => $registration,
                    'registrationID' => $registration->registration_number,
                ], function ($message) use ($recipientEmail, $registration, $pdfPath) {
                    $message->to($recipientEmail)
                        ->subject('IPHACON 2027 : Delegate Registration Approved (Reg No: ' . $registration->registration_number . ')')
                        ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));

                    if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                        $localPath = storage_path("app/public/{$pdfPath}");
                        $message->attach($localPath, [
                            'as' => "Delegate Registration - {$registration->registration_number}.pdf",
                            'mime' => 'application/pdf'
                        ]);
                    }
                });

                $logMsg = "Resent Registration Approval Confirmation Email to {$recipientEmail}. Reg No: {$registration->registration_number}";
                $successMsg = "Approval confirmation email with PDF receipt successfully sent to {$recipientEmail}!";
            } else {
                // Generate Acknowledgement / Registration Receipt PDF
                $ackPdf = null;
                try {
                    $pdf = Pdf::loadView('pdfs.registration', [
                        'registration' => $registration,
                        'applicationNumber' => $registration->registration_number ?? $registration->acknowledgement_id
                    ])->setPaper('a4', 'portrait')
                        ->setOption('margin-top', 10)
                        ->setOption('margin-bottom', 10)
                        ->setOption('margin-left', 10)
                        ->setOption('margin-right', 10);

                    $ackPdf = $pdf->output();
                } catch (\Exception $pdfEx) {
                    Log::warning('PDF generation for submission confirmation email failed: ' . $pdfEx->getMessage());
                }

                // Send Registration Submission Confirmation Email with PDF Attachment
                Mail::send('emails.delegate_submission_confirmation', [
                    'registration' => $registration,
                    'user'         => $registration->user,
                    'payment'      => $registration->latestPayment,
                ], function ($message) use ($recipientEmail, $registration, $ackPdf) {
                    $message->to($recipientEmail)
                        ->subject('IPHACON 2027 : Delegate Registration Submitted (' . ($registration->acknowledgement_id ?? 'IPHACON') . ')')
                        ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));

                    if ($ackPdf) {
                        $docId = $registration->registration_number ?? $registration->acknowledgement_id;
                        $message->attachData($ackPdf, "IPHACON_2027_Acknowledgement_Receipt_{$docId}.pdf", [
                            'mime' => 'application/pdf'
                        ]);
                    }
                });

                $logMsg = "Resent Registration Submission Confirmation Email with PDF Attachment to {$recipientEmail}. Ack ID: {$registration->acknowledgement_id}";
                $successMsg = "Registration submission confirmation email with Acknowledgement PDF successfully sent to {$recipientEmail}!";
            }

            // Record Activity Log
            \App\Models\ActivityLog::record(
                'ADMIN_RESEND_REGISTRATION_EMAIL',
                $logMsg,
                [
                    'registration_id' => $registration->id,
                    'acknowledgement_id' => $registration->acknowledgement_id,
                    'registration_number' => $registration->registration_number,
                    'recipient_email' => $recipientEmail,
                    'email_type' => $emailType
                ],
                \Illuminate\Support\Facades\Auth::guard('admin')->user()
            );

            return redirect()->back()->with('success', $successMsg);

        } catch (\Exception $e) {
            Log::error('Failed to send registration email: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
                'email' => $recipientEmail,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Send Payment Reminder / Complete Registration Email to Delegate
     * Enforces strict limit: Max 1 email per user/email per day.
     */
    public function sendPaymentReminder(Request $request)
    {
        $regId = $request->input('registration_id');
        $payId = $request->input('payment_id');
        $ackNo = $request->input('acknowledgement_id');
        $customEmail = trim($request->input('email') ?? '');
        $customMessage = trim($request->input('custom_message') ?? '');

        $registration = null;
        $payment = null;

        if ($regId) {
            $registration = Registration::with(['user', 'delegateCategory', 'country', 'state', 'latestPayment', 'payments'])
                ->find($regId);
        } elseif ($payId) {
            $payment = \App\Models\Payment::with(['registration.user', 'registration.delegateCategory', 'registration.country', 'registration.state'])
                ->find($payId);
            if ($payment) {
                $registration = $payment->registration;
            }
        } elseif ($ackNo) {
            $registration = Registration::with(['user', 'delegateCategory', 'country', 'state', 'latestPayment', 'payments'])
                ->where('acknowledgement_id', $ackNo)
                ->orWhere('registration_number', $ackNo)
                ->first();
        }

        if (!$registration && !$payment) {
            return redirect()->back()->with('error', 'Registration or Payment record not found.');
        }

        $recipientEmail = !empty($customEmail) ? $customEmail : ($registration?->user?->email ?? $payment?->registration?->user?->email);

        if (empty($recipientEmail)) {
            return redirect()->back()->with('error', 'No recipient email found. Please provide a valid email address.');
        }

        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Invalid email address provided.');
        }

        // =========================================================================
        // STRICT CHECK: Only 1 reminder email per user/recipient per day
        // =========================================================================
        $cleanEmail = strtolower(trim($recipientEmail));
        $alreadySentToday = \App\Models\ActivityLog::where('action', 'ADMIN_SEND_PAYMENT_REMINDER')
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->get()
            ->first(function ($log) use ($registration, $payment, $cleanEmail) {
                $props = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                if (empty($props)) return false;

                if (!empty($props['recipient_email']) && strtolower(trim($props['recipient_email'])) === $cleanEmail) {
                    return true;
                }

                if ($registration && !empty($props['registration_id']) && (string)$props['registration_id'] === (string)$registration->id) {
                    return true;
                }

                if ($registration && !empty($registration->user_id) && !empty($props['user_id']) && (string)$props['user_id'] === (string)$registration->user_id) {
                    return true;
                }

                if ($payment && !empty($props['payment_id']) && (string)$props['payment_id'] === (string)$payment->id) {
                    return true;
                }

                return false;
            });

        if ($alreadySentToday) {
            $sentTime = $alreadySentToday->created_at->format('h:i A');
            return redirect()->back()->with('error', "Today's reminder has already been sent to {$recipientEmail} at {$sentTime}. Only 1 reminder email is allowed per user per day (ek user ko ek din me sirf ek hi mail bheja ja sakta hai).");
        }

        try {
            $userPrefix = $registration?->user?->prefix ?? '';
            $userName = $registration?->user?->full_name ?? ($registration?->user?->name ?? 'Delegate');
            $acknowledgementId = $registration?->acknowledgement_id ?? ($payment?->registration?->acknowledgement_id ?? 'N/A');
            $categoryName = $registration?->delegateCategory?->category_name ?? ($payment?->registration?->delegateCategory?->category_name ?? 'Delegate');
            $delegateType = $registration?->delegate_type ?? ($payment?->registration?->delegate_type ?? 'Indian');
            $paymentStatus = $payment?->payment_status ?: ($registration?->status ?? 'Pending Payment');

            // Calculate pending amount
            $pendingAmount = $registration?->total_amount ?: ($payment?->total_amount ?: 0);
            if (!$pendingAmount && $registration && method_exists($registration, 'calculateTotalAmount')) {
                $pendingAmount = $registration->calculateTotalAmount();
            }

            $currencySymbol = ($delegateType === 'International' || $delegateType === 'Foreigner') ? '$' : '₹';
            $paymentUrl = route('login');

            Mail::send('emails.payment_reminder', [
                'registration'      => $registration,
                'payment'           => $payment,
                'userPrefix'        => $userPrefix,
                'userName'          => $userName,
                'acknowledgementId' => $acknowledgementId,
                'categoryName'      => $categoryName,
                'delegateType'      => $delegateType,
                'paymentStatus'     => $paymentStatus,
                'pendingAmount'     => $pendingAmount,
                'currencySymbol'    => $currencySymbol,
                'paymentUrl'        => $paymentUrl,
                'customMessage'     => $customMessage,
            ], function ($message) use ($recipientEmail, $acknowledgementId) {
                $message->to($recipientEmail)
                    ->subject('IPHACON 2027 : Pending Payment Reminder / Complete Your Registration (' . ($acknowledgementId ?: 'IPHACON') . ')')
                    ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));
            });

            // Record Activity Log
            \App\Models\ActivityLog::record(
                'ADMIN_SEND_PAYMENT_REMINDER',
                "Sent Payment Reminder Email to {$recipientEmail}. Ack ID: {$acknowledgementId}",
                [
                    'user_id'           => $registration?->user_id,
                    'registration_id'   => $registration?->id,
                    'payment_id'        => $payment?->id,
                    'acknowledgement_id'=> $acknowledgementId,
                    'recipient_email'   => $recipientEmail,
                    'pending_amount'    => $pendingAmount,
                    'custom_message'    => $customMessage,
                ],
                \Illuminate\Support\Facades\Auth::guard('admin')->user()
            );

            return redirect()->back()->with('success', "Payment reminder email successfully sent to {$recipientEmail}!");

        } catch (\Exception $e) {
            Log::error('Failed to send payment reminder email: ' . $e->getMessage(), [
                'registration_id' => $registration?->id,
                'payment_id' => $payment?->id,
                'email' => $recipientEmail,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to send payment reminder email: ' . $e->getMessage());
        }
    }

    /**
     * Send Incomplete Registration Reminder Email
     * STRICT ENFORCEMENT: Max 1 email per user/recipient per day (ek din me ek hi baar mail jayega).
     */
    public function sendIncompleteRegistrationReminder(Request $request)
    {
        $target = $request->input('target', 'all'); // 'all', 'drafts', 'users', 'single_reg', 'single_user'
        $customMessage = trim($request->input('custom_message', ''));

        // Fetch all emails, user_ids, and registration_ids that received a reminder today
        $todayLogs = \App\Models\ActivityLog::whereIn('action', ['ADMIN_SEND_PAYMENT_REMINDER', 'ADMIN_SEND_INCOMPLETE_REMINDER'])
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->get();

        $todaySentEmails = [];
        $todaySentUserIds = [];
        $todaySentRegIds = [];

        foreach ($todayLogs as $log) {
            $props = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
            if (is_array($props)) {
                if (!empty($props['recipient_email'])) {
                    $todaySentEmails[] = strtolower(trim($props['recipient_email']));
                }
                if (!empty($props['user_id'])) {
                    $todaySentUserIds[] = (string)$props['user_id'];
                }
                if (!empty($props['registration_id'])) {
                    $todaySentRegIds[] = (string)$props['registration_id'];
                }
            }
        }
        $todaySentEmails = array_unique($todaySentEmails);
        $todaySentUserIds = array_unique($todaySentUserIds);
        $todaySentRegIds = array_unique($todaySentRegIds);

        // Helper closure to check if user already received reminder today
        $isSentToday = function ($email, $userId = null, $regId = null) use ($todaySentEmails, $todaySentUserIds, $todaySentRegIds) {
            $clean = strtolower(trim($email ?? ''));
            if ($clean !== '' && in_array($clean, $todaySentEmails)) {
                return true;
            }
            if ($userId && in_array((string)$userId, $todaySentUserIds)) {
                return true;
            }
            if ($regId && in_array((string)$regId, $todaySentRegIds)) {
                return true;
            }
            return false;
        };

        // 1. Handle Single Delegate Registration
        if ($target === 'single_reg') {
            $regId = $request->input('registration_id');
            $reg = Registration::with(['user', 'delegateCategory'])->find($regId);
            if (!$reg || !$reg->user || empty($reg->user->email)) {
                return redirect()->back()->with('error', 'Registration or delegate email not found.');
            }

            $email = strtolower(trim($reg->user->email));
            if ($isSentToday($email, $reg->user_id, $reg->id)) {
                return redirect()->back()->with('error', "Today's reminder has already been sent to {$email}. Only 1 reminder email is permitted per user per day.");
            }

            try {
                Mail::send('emails.incomplete_registration_reminder', [
                    'isDraft'        => true,
                    'userPrefix'     => $reg->user->prefix,
                    'userName'       => $reg->user->full_name,
                    'userEmail'      => $reg->user->email,
                    'delegateType'   => $reg->delegate_type ?? 'Indian',
                    'categoryName'   => $reg->delegateCategory?->category_name ?? 'Delegate',
                    'stepCompleted'  => $reg->step_completed ?? 1,
                    'actionUrl'      => route('login'),
                    'customMessage'  => $customMessage,
                ], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('IPHACON 2027 : Friendly Reminder - Please Complete Your Registration')
                        ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));
                });

                \App\Models\ActivityLog::record(
                    'ADMIN_SEND_INCOMPLETE_REMINDER',
                    "Sent Incomplete Registration Reminder Email to {$email}",
                    [
                        'registration_id' => $reg->id,
                        'user_id'         => $reg->user_id,
                        'recipient_email' => $email,
                        'custom_message'  => $customMessage,
                        'type'            => 'draft'
                    ],
                    \Illuminate\Support\Facades\Auth::guard('admin')->user()
                );

                return redirect()->back()->with('success', "Registration reminder email sent successfully to {$email}!");
            } catch (\Exception $e) {
                Log::error('Single draft reminder email failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
            }
        }

        // 2. Handle Single Signed-Up User
        if ($target === 'single_user') {
            $userId = $request->input('user_id');
            $user = \App\Models\User::find($userId);
            if (!$user || empty($user->email)) {
                return redirect()->back()->with('error', 'User or user email not found.');
            }

            $email = strtolower(trim($user->email));
            if ($isSentToday($email, $user->id, null)) {
                return redirect()->back()->with('error', "Today's reminder has already been sent to {$email}. Only 1 reminder email is permitted per user per day.");
            }

            try {
                Mail::send('emails.incomplete_registration_reminder', [
                    'isDraft'        => false,
                    'userPrefix'     => $user->prefix,
                    'userName'       => $user->full_name,
                    'userEmail'      => $user->email,
                    'delegateType'   => $user->delegate_type ?? 'Indian',
                    'categoryName'   => null,
                    'stepCompleted'  => null,
                    'actionUrl'      => route('login'),
                    'customMessage'  => $customMessage,
                ], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('IPHACON 2027 : Welcome - Complete Your Delegate Registration')
                        ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));
                });

                \App\Models\ActivityLog::record(
                    'ADMIN_SEND_INCOMPLETE_REMINDER',
                    "Sent Registration Reminder Email to Signed-Up User {$email}",
                    [
                        'user_id'         => $user->id,
                        'recipient_email' => $email,
                        'custom_message'  => $customMessage,
                        'type'            => 'signed_up_user'
                    ],
                    \Illuminate\Support\Facades\Auth::guard('admin')->user()
                );

                return redirect()->back()->with('success', "Registration reminder email sent successfully to {$email}!");
            } catch (\Exception $e) {
                Log::error('Single user reminder email failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
            }
        }

        // 3. Handle Bulk Sending ('all', 'drafts', 'users')
        set_time_limit(0);

        $recipients = collect();

        if (in_array($target, ['all', 'drafts'])) {
            $draftRegs = Registration::with(['user', 'delegateCategory'])
                ->where(function ($q) {
                    $q->where('status', 'Draft')
                      ->orWhere('step_completed', '<', 4)
                      ->orWhereNull('status');
                })
                ->where('is_deleted', '0')
                ->get();

            foreach ($draftRegs as $reg) {
                if ($reg->user && !empty($reg->user->email) && filter_var($reg->user->email, FILTER_VALIDATE_EMAIL)) {
                    $email = strtolower(trim($reg->user->email));
                    $recipients->put($email, [
                        'type'           => 'draft',
                        'registration'   => $reg,
                        'user'           => $reg->user,
                        'userPrefix'     => $reg->user->prefix,
                        'userName'       => $reg->user->full_name,
                        'userEmail'      => $email,
                        'userId'         => $reg->user_id,
                        'regId'          => $reg->id,
                        'delegateType'   => $reg->delegate_type ?? 'Indian',
                        'categoryName'   => $reg->delegateCategory?->category_name ?? 'Delegate',
                        'stepCompleted'  => $reg->step_completed ?? 1,
                    ]);
                }
            }
        }

        if (in_array($target, ['all', 'users'])) {
            $registeredUserIds = Registration::where('is_deleted', '0')->pluck('user_id')->toArray();
            $users = \App\Models\User::whereNotIn('id', $registeredUserIds)->get();

            foreach ($users as $user) {
                if (!empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    $email = strtolower(trim($user->email));
                    if (!$recipients->has($email)) {
                        $recipients->put($email, [
                            'type'           => 'signed_up_user',
                            'registration'   => null,
                            'user'           => $user,
                            'userPrefix'     => $user->prefix,
                            'userName'       => $user->full_name,
                            'userEmail'      => $email,
                            'userId'         => $user->id,
                            'regId'          => null,
                            'delegateType'   => $user->delegate_type ?? 'Indian',
                            'categoryName'   => null,
                            'stepCompleted'  => null,
                        ]);
                    }
                }
            }
        }

        if ($recipients->isEmpty()) {
            return redirect()->back()->with('error', 'No eligible recipients found to send reminders.');
        }

        $sentCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        $adminUser = \Illuminate\Support\Facades\Auth::guard('admin')->user();

        foreach ($recipients as $email => $item) {
            // STRICT CHECK: Skip if already sent today
            if ($isSentToday($email, $item['userId'] ?? null, $item['regId'] ?? null)) {
                $skippedCount++;
                continue;
            }

            try {
                $isDraft = ($item['type'] === 'draft');
                $subject = $isDraft 
                    ? 'IPHACON 2027 : Friendly Reminder - Please Complete Your Registration'
                    : 'IPHACON 2027 : Welcome - Complete Your Delegate Registration';

                Mail::send('emails.incomplete_registration_reminder', [
                    'isDraft'        => $isDraft,
                    'userPrefix'     => $item['userPrefix'],
                    'userName'       => $item['userName'],
                    'userEmail'      => $email,
                    'delegateType'   => $item['delegateType'],
                    'categoryName'   => $item['categoryName'],
                    'stepCompleted'  => $item['stepCompleted'],
                    'actionUrl'      => route('login'),
                    'customMessage'  => $customMessage,
                ], function ($message) use ($email, $subject) {
                    $message->to($email)
                        ->subject($subject)
                        ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));
                });

                \App\Models\ActivityLog::record(
                    'ADMIN_SEND_INCOMPLETE_REMINDER',
                    "Sent Bulk Incomplete Registration Reminder Email to {$email}",
                    [
                        'registration_id' => $item['registration']?->id,
                        'user_id'         => $item['user']?->id,
                        'recipient_email' => $email,
                        'custom_message'  => $customMessage,
                        'type'            => $item['type']
                    ],
                    $adminUser
                );

                // Add to sent tracking for this batch
                $todaySentEmails[] = $email;
                if (!empty($item['userId'])) {
                    $todaySentUserIds[] = (string)$item['userId'];
                }
                if (!empty($item['regId'])) {
                    $todaySentRegIds[] = (string)$item['regId'];
                }
                $sentCount++;
            } catch (\Exception $e) {
                Log::error("Failed sending reminder email to {$email}: " . $e->getMessage());
                $failedCount++;
            }
        }

        $msgParts = [];
        if ($sentCount > 0) {
            $msgParts[] = "Successfully sent reminder emails to {$sentCount} recipient(s).";
        }
        if ($skippedCount > 0) {
            $msgParts[] = "{$skippedCount} recipient(s) skipped (already sent today - max 1 email/day rule).";
        }
        if ($failedCount > 0) {
            $msgParts[] = "{$failedCount} recipient(s) failed.";
        }

        $finalMsg = implode(' ', $msgParts);
        if ($sentCount === 0 && $skippedCount > 0) {
            return redirect()->back()->with('error', "All {$skippedCount} selected recipient(s) have already received a reminder email today. (Max 1 email per user per day).");
        }

        return redirect()->back()->with('success', $finalMsg ?: 'Reminder emails processed.');
    }
}
