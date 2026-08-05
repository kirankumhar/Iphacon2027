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

    public function internationalPaymentSubmittedDelegates()
    {
        $registrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
            ->where('status', 'Payment Submitted')
            ->where('is_deleted', '0')
            ->latest()
            ->get();

        return view('admin.modules.registration.show-int-payment-submitted-registration', compact('registrations'));
    }

    public function approvedIndDelegates()
    {
        return view('admin.modules.registration.show-ind-approved-registration');
    }

    public function internationalApprovedDelegates()
    {
        return view('admin.modules.registration.show-int-approved-registration');
    }

    public function internationalRejectedDelegates()
    {
        return view('admin.modules.registration.show-int-rejected-registration');
    }

    public function internationalRevertedDelegates()
    {
        return view('admin.modules.registration.show-int-reverted-registration');
    }

    public function deletedRegistration(Request $request)
    {
        $deletedStuList = Registration::where('is_deleted', '1')->get();

        return view('admin.modules.registration.show-deleted-registration', compact('deletedStuList'));
    }

    public function approvedRegis(Request $request)
    {
        $registration_number = $request->input('registration_number');

        Registration::where('registration_number', $registration_number)->update([
            'status' => 'Approved'
        ]);

        return redirect()->back()->with('success', "$registration_number, registration successfully marked Approved.");
    }

    public function rejectRegis(Request $request)
    {
        $registration_number = $request->input('registration_number');

        Registration::where('registration_number', $registration_number)->update([
            'status' => 'Rejected',
            'rejection_reason' => $request->input('reason') ?? null,
            'rejected_at' => now(),
        ]);

        return redirect()->back()->with('success', "$registration_number, registration successfully marked Reject.");
    }

    public function revertRegis(Request $request)
    {
        $registration_number = $request->input('registration_number');

        Registration::where('registration_number', $registration_number)->update([
            'status' => 'Draft',
            'reverted_at' => now(),
            'revert_reason' => $request->input('reason') ?? null,
        ]);

        return redirect()->back()->with('success', "$registration_number, registration successfully marked Revert.");
    }

    public function deleteRegis(Request $request)
    {
        $registration_number = $request->input('registration_number');

        Registration::where('registration_number', $registration_number)->update([
            'is_deleted' => '1',
            'deleted_datetime' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', "$registration_number, registration successfully deleted.");
    }

    public function receiptCumRegistrationSlipDownload($id)
    {

        $delegate = Registration::with([
            'user',
            'latestPayment'
        ])->where('registration_number', $id)
            ->latest('created_at')->first();

        $pdf = PDF::loadView('pdfs.registration', [
            'registration' => $delegate,
            'applicationNumber' => $delegate->registration_number
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
        ])->where('registration_number', $id)
            ->latest('created_at')->first();

            // dd($delegate->user);
        return view('admin.modules.registration.show-registration-details', compact('delegate'));
    }
}
