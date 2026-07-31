<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;
use App\Models\Country;
use App\Models\State;
use App\Models\DelegateCategory;
use App\Models\Payment;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Str;

class RegistrationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $registrations = Registration::where('user_id', $user->id)
            ->with(['delegateCategory', 'country', 'state'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('registration.index', compact('registrations'));
    }

    public function create()
    {
        $user = Auth::user();

        // Check if user already has a registration
        $existingRegistration = Registration::where('user_id', $user->id)->first();

        if ($existingRegistration) {

            if ($existingRegistration?->status === 'Payment Submitted' || $existingRegistration?->status === 'Approved' || $existingRegistration?->status === 'Rejected') {
                return redirect()->route('registration.index');
            } else if ($existingRegistration?->status === 'Pending Payment' && $user->delegate_type == 'Indian') {

                $gatewayData = json_encode([
                    'reg_id' => $existingRegistration->id,
                    'uid' => auth()->id(),
                ]);

                $encrypted = Crypt::encryptString($gatewayData);

                return redirect()->route('payment.gateway', $encrypted);
            } else {
                $stepData = json_encode([
                    'step' => max(1, $existingRegistration->step_completed),
                    'uid' => auth()->id(),
                ]);
            }
        } else {
            $stepData = json_encode([
                'step' => 1,
                'uid' => auth()->id(),
            ]);
        }

        $encryptedToken = Crypt::encryptString($stepData);

        if ($existingRegistration) {
            return redirect()->route('registration.wizard', ['token' => $encryptedToken])
                ->with('info', 'You already have a registration in progress. Continue from where you left off.');
        }

        // Create new draft registration
        $registration = Registration::create([
            'user_id' => $user->id,
            'status' => 'Draft',
            'step_completed' => 0,
            'city' => '',
        ]);

        return redirect()->route('registration.wizard', ['token' => $encryptedToken]);
    }

    public function wizard(Request $request, $token)
    {
        try {
            $decrypted = Crypt::decryptString($token);
            $stepData = json_decode($decrypted, true);

            if (!isset($stepData['step'], $stepData['uid'])) {
                abort(404);
            }

            $step = (int) $stepData['step'];
            $uid = (int) $stepData['uid'];
        } catch (\Exception $e) {
            abort(404);
        }

        $user = auth()->user();

        // Ensure token belongs to current user
        if (!$user || $user->id !== $uid) {
            abort(403, 'Unauthorized access.');
        }

        $registration = Registration::where('user_id', $user->id)->first();

        if (!$registration || $registration->status !== 'Draft') {
            return redirect()->route('registration.create');
        }

        $countries = Country::active()->orderBy('country_name')->get();
        $states = State::active()->where('country_id', 1)->orderBy('state_name')->get();
        $delegateCategories = DelegateCategory::where('is_active', true)->get();

        return view('registration.wizard', compact('registration', 'step', 'user', 'countries', 'delegateCategories', 'states'));
    }

    public function storeStep(Request $request, $step)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)
            ->where('status', 'Draft')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $isDraft = $request->input('action') === 'save_draft';

            switch ($step) {
                case 1:
                    $this->validateAndStoreStep1($request, $registration, $isDraft);
                    break;
                case 2:
                    $this->validateAndStoreStep2($request, $registration, $isDraft);
                    break;
                case 3:
                    $this->validateAndStoreStep3($request, $registration, $isDraft);
                    break;
                case 4:
                    return $this->processPayment($request, $registration, $isDraft);
            }

            // Refresh the registration model to get updated data
            $registration->refresh();

            if ($isDraft) {
                $registration->updateAmounts();
                $registration->save();
            }

            DB::commit();

            if ($isDraft) {
                return response()->json([
                    'success' => true,
                    'message' => 'Progress saved as draft successfully!'
                ]);
            }

            $nextStep = $step + 1;

            $stepData = json_encode([
                'step' => $nextStep,
                'uid' => auth()->id(),
            ]);

            $encryptedToken = Crypt::encryptString($stepData);

            if ($nextStep > 4) {
                return redirect()->route('registration.show', $registration->id)
                    ->with('success', 'Registration completed successfully!');
            }

            return redirect()->route('registration.wizard', ['token' => $encryptedToken])
                ->with('success', 'Step ' . $step . ' completed successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            if ($isDraft) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Please fix the validation errors before saving.'
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration Step Error: ' . $e->getMessage(), [
                'step' => $step,
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($isDraft) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while saving: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput();
        }
    }

    private function validateAndStoreStep1(Request $request, Registration $registration, $isDraft = false)
    {
        // Relaxed validation for draft saving
        $rules = [
            'prefix' => 'nullable|string|in:Dr.,Mr.,Mrs.,Prof.',
            'full_name' => 'nullable|string|max:100',
            'gender' => 'nullable|in:Male,Female',
            'dob' => 'nullable|date|before:-18 years',
            'mobile_number' => 'nullable|string|max:18',
            'photo' => 'nullable|image|mimes:jpg,jpeg|max:500',
            'address' => $isDraft ? 'nullable|string|max:500' : 'required|string|max:500',
            'state_id' => auth()->user()->delegate_type == 'Indian' ? ($isDraft ? 'nullable|integer' : 'required|integer') : ($isDraft ? 'nullable|string|max:50' : 'required|string|max:50'),
            'city' => $isDraft ? 'nullable|string|max:100' : 'required|string|max:100',
            'pin_code' => $isDraft ? 'nullable|string|max:20' : 'required|string|max:20',
            'whatsapp_country_code' => 'nullable|string',
            'whatsapp_number' => 'nullable|string|max:20',
            'dietary_preference' => 'nullable|in:Vegetarian,Non-Vegetarian',
            'id_proof_type' => $isDraft ? 'nullable|string' : 'required|string',
            'id_proof_document' => $registration->id_proof_document_path !== null ? 'nullable|file|mimes:jpg,jpeg,pdf|max:2500' : ($isDraft ? 'nullable|file|mimes:jpg,jpeg,pdf|max:2500' : 'required|file|mimes:jpg,jpeg,pdf|max:2500')
        ];

        $request->validate($rules);

        // Update user information
        $user = $registration->user;
        if ($request->filled('prefix'))
            $user->prefix = $request->prefix;
        if ($request->filled('full_name'))
            $user->full_name = $request->full_name;
        if ($request->filled('gender'))
            $user->gender = $request->gender;
        if ($request->filled('dob'))
            $user->date_of_birth = $request->dob;
        if ($request->filled('mobile_number'))
            $user->mobile_number = $request->mobile_number;

        $user->save();

        // Handle file uploads and registration updates
        $updateData = [];

        if ($request->filled('address'))
            $updateData['address'] = $request->address;
        if ($request->filled('country_id'))
            $updateData['country_id'] = auth()->user()->country_id;
        if ($request->filled('state_id'))

            if (auth()->user()->delegate_type == 'Indian') {
                $updateData['state_id'] = $request->state_id ?: null;
            } else {
                $updateData['other_state'] = $request->state_id ?: null;
            }

        if ($request->filled('city'))
            $updateData['city'] = $request->city;
        if ($request->filled('pin_code'))
            $updateData['pin_code'] = $request->pin_code;
        if ($request->filled('whatsapp_country_code'))
            $updateData['whatsapp_country_code'] = auth()->user()->country_code;
        if ($request->filled('whatsapp_number'))
            $updateData['whatsapp_number'] = $request->whatsapp_number;
        if ($request->filled('dietary_preference'))
            $updateData['dietary_preference'] = $request->dietary_preference;
        if ($request->filled('id_proof_type'))
            $updateData['id_proof_type'] = $request->id_proof_type;

        if ($request->hasFile('photo')) {
            $updateData['photo_path'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->hasFile('id_proof_document')) {
            $updateData['id_proof_document_path'] = $request->file('id_proof_document')->store('id_proofs', 'public');
        }

        if (!$isDraft) {
            $updateData['step_completed'] = 1;
        }

        if (!empty($updateData)) {
            $registration->update($updateData);
        }
    }

    private function validateAndStoreStep2(Request $request, Registration $registration, $isDraft = false)
    {
        // Get the delegate type from the user
        $delegateType = $request->user()->delegate_type;

        if ($delegateType == 'Indian') {
            // For Indian delegates - validate based on selected category
            $rules = [
                'delegate_category_id' => $isDraft ? 'nullable|exists:delegate_categories,id' : 'required|exists:delegate_categories,id',
            ];

            $categoryId = $request->delegate_category_id;

            // Fixed logic error: use AND (&&) instead of OR (||)
            if ($categoryId != 1 && $categoryId != 4) {
                $rules = array_merge($rules, [
                    'ismm_membership_no' => 'required_if:delegate_category_id,2|string|max:50|nullable',
                    'young_isam_membership_no' => 'required_if:delegate_category_id,3|string|max:50|nullable',
                    'accompanying_persons' => 'nullable|integer|min:0|max:10',
                    'participate_in_cme' => 'nullable|boolean',
                ]);
            } else {
                $rules = array_merge($rules, [
                    'accompanying_persons' => 'nullable|integer|min:0|max:10',
                    'participate_in_cme' => 'nullable|boolean',
                    'ismm_membership_no' => 'nullable|string|max:50',
                    'young_isam_membership_no' => 'nullable|string|max:50',
                ]);
            }
        } else {
            // International delegates - minimal validation
            $rules = [
                'participate_in_cme' => 'nullable|boolean|in:0',
            ];
        }

        $messages = [
            'ismm_membership_no.required_if' => 'Iphacon Membership Number is required for IPHACON Member category.',
            'young_isam_membership_no.required_if' => 'Young ISAM Membership Number is required for Young ISAM Members category.',
            'delegate_category_id.required' => 'Please select your delegate category.',
            'ismm_membership_no.max' => 'IPHACON Membership Number cannot exceed 50 characters.',
            'young_isam_membership_no.max' => 'Young ISAM Membership Number cannot exceed 50 characters.',
        ];

        $request->validate($rules, $messages);

        // Update data based on delegate type
        if ($delegateType == 'Indian') {
            $updateData = [
                'delegate_type' => 'Indian',
                'delegate_category_id' => $request->delegate_category_id,
                'accompanying_persons' => $request->accompanying_persons ?? 0,
                'participate_in_cme' => $request->participate_in_cme ?? false,
                'membership_no' => $request->delegate_category_id == 2 ? $request->ismm_membership_no : ($request->delegate_category_id == 3 ? $request->young_isam_membership_no : null),
                'cme_fee' => $request->participate_in_cme ? 1000 : 0,
                'accompanying_fee' => $request->accompanying_persons ? 4000 : 0,
                ];
        } else {
            // International delegate - fixed values
            $updateData = [
                'delegate_type' => 'International',
                'delegate_category_id' => 1, // Default category for international
                'accompanying_persons' => 0,
                'participate_in_cme' => false,
                'membership_no' => null,
            ];
        }

        if (!$isDraft) {
            $updateData['step_completed'] = 2;
        }

        $registration->update($updateData);

        // Calculate and save total amount, delegate fee, and gst amount
        if (!$isDraft) {
            $registration->updateAmounts();
            $registration->save();
        }
    }

    public function generateRegistrationNumber()
    {
        $randomBlock = function () {
            return strtoupper(Str::random(4));
        };

        $registrationNumber = $randomBlock() . '-' . $randomBlock() . '-' . $randomBlock();

        return $registrationNumber;
    }

    public function generateUniqueRegistrationNumber()
    {
        do {
            $number = $this->generateRegistrationNumber();
        } while (Registration::where('registration_number', $number)->exists());

        return $number;
    }

    private function validateAndStoreStep3(Request $request, Registration $registration, $isDraft = false)
    {
        // $registration = Registration::where('user_id', auth()->user()->id)
        //     ->where('id', $registration->id)
        //     ->with(['delegateCategory', 'country', 'state'])
        //     ->firstOrFail();

        // // Check if registration is ready for payment
        // if ($registration->status !== 'Draft' && $registration->step_completed < 3) {
        //     return redirect()->route('registration.wizard', ['step' => $registration->step_completed])
        //         ->with('error', 'Please complete all registration steps before payment.');
        // }

        /* if (!$registration->registration_number) {
             if ($registration->delegate_type === 'International') {
                 $registration->registration_number = 'NRI27ISM' . str_pad(
                     Registration::count() + 1,
                     6,
                     '0',
                     STR_PAD_LEFT
                 );
             } else {
                 $registration->registration_number = 'IND27ISM' . str_pad(
                     Registration::count() + 1,
                     6,
                     '0',
                     STR_PAD_LEFT
                 );
             }
         } */

        if (!$isDraft) {
            $registration->step_completed = 3;
            $registration->updateAmounts();
        }

        $registration->save();

        if (auth()->user()->delegate_type == 'Indian') {
            $this->processPayment($request, $registration);
        }
    }

    private function processPayment(Request $request, Registration $registration, $isDraft = false)
    {
        if ($registration->delegate_type === 'International') {
            $request->validate([
                'transaction_id' => 'required|string|max:100',
                'payment_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1020'
            ]);

            try {
                $receiptPath = $request->file('payment_receipt')->store(
                    'International/payment_receipts/' . $registration->registration_number,
                    'public'
                );

                // Create the payment record explicitly
                $payment = new Payment([
                    'registration_id' => $registration->id,
                    'delegate_category_fee' => 175.00,
                    'accompanying_persons_fee' => 0.00,
                    'cme_fee' => 0.00,
                    'gst_amount' => 0.00,
                    'total_amount' => 175.00,
                    'currency' => 'USD',
                    'transaction_id' => $request->transaction_id,
                    'payment_method' => 'QR_Code',
                    'payment_status' => 'Pending',
                    'payment_receipt_path' => $receiptPath,
                    'admin_verified' => false
                ]);

                $paymentSaved = $payment->save();

                if (!$paymentSaved) {
                    throw new \Exception("Failed to save payment record.");
                }

                // Update registration status
                $registration->registration_number = $this->generateUniqueRegistrationNumber();
                $registration->status = 'Payment Submitted';
                $registration->step_completed = 4;
                $registration->submitted_at = now();
                $registrationSaved = $registration->save();

                if (!$registrationSaved) {
                    throw new \Exception("Failed to update registration status.");
                }

                DB::commit();

                return redirect()->route('registration.index', $registration->id)
                    ->with('success', 'Registration submitted successfully! Payment verification is under process.');

            } catch (\Exception $e) {
                DB::rollBack();

                // Delete stored file if DB transaction fails
                if (isset($receiptPath) && Storage::disk('public')->exists($receiptPath)) {
                    Storage::disk('public')->delete($receiptPath);
                }

                Log::error('Payment Processing Error: ' . $e->getMessage(), [
                    'registration_id' => $registration->id,
                    'user_id' => $registration->user_id,
                    'trace' => $e->getTraceAsString()
                ]);

                return back()
                    ->withErrors(['payment' => 'Error saving payment: ' . $e->getMessage()])
                    ->withInput();
            }
        }

        $totalAmount = $registration->calculateTotalAmount();

        // Generate unique transaction ID
        $transactionId = 'CONF' . $registration->id . '_' . time();

        $registration->registration_number = $this->generateUniqueRegistrationNumber();
        $registration->status = 'Pending Payment';
        $registration->step_completed = 4;
        $registration->save();

        $gatewayData = json_encode([
            'reg_id' => $registration->id,
            'uid' => auth()->id(),
        ]);

        $encrypted = Crypt::encryptString($gatewayData);

        // Indian delegate - redirect to payment gateway
        return redirect()->route('payment.gateway', $encrypted);
    }

    public function show($id)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['delegateCategory', 'country', 'state', 'payments'])
            ->firstOrFail();

        return view('registration.show', compact('registration'));
    }

    public function getDelegateCount()
    {
        $registrations = Registration::where('status', 'Approved')->count();

        return json_encode([
            'delegate_count' => $registrations,
        ]);
    }

    public function getWorkshopCount()
    {
        $registrations = Registration::where('participate_in_cme', true)->where('status', 'Approved')->count();

        return json_encode([
            'workshop_count' => $registrations,
        ]);
    }
}
