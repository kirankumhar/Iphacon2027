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
use Illuminate\Support\Facades\Mail;
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

    public function create(Request $request)
    {
        $user = Auth::user();

        // Check if user already has a registration
        $existingRegistration = Registration::where('user_id', $user->id)->first();

        if ($existingRegistration) {

            if ($existingRegistration?->status === 'Payment Submitted' || $existingRegistration?->status === 'Approved' || $existingRegistration?->status === 'Rejected') {
                return redirect()->route('registration.index');
            } else if ($existingRegistration?->status === 'Pending Payment' && $user->delegate_type == 'Indian') {
                return redirect()->route('payment.gateway');
            } else {
                $maxAllowedStep = max(1, min(4, (int)$existingRegistration->step_completed + 1));
                if ($request->has('step')) {
                    $targetStep = min($maxAllowedStep, max(1, (int)$request->input('step')));
                } else if (!empty($existingRegistration->revert_reason) || !empty($existingRegistration->reverted_at)) {
                    $targetStep = 1;
                } else {
                    $targetStep = $maxAllowedStep;
                }

                return redirect()->route('registration.wizard', ['step' => $targetStep]);
            }
        }

        // Create new draft registration
        $registration = Registration::create([
            'user_id' => $user->id,
            'status' => 'Draft',
            'step_completed' => 0,
            'city' => '',
        ]);

        return redirect()->route('registration.wizard', ['step' => 1]);
    }

    public function wizard(Request $request, $step = null)
    {
        $user = auth()->user();

        $registration = Registration::where('user_id', $user->id)->first();

        if (!$registration || $registration->status !== 'Draft') {
            return redirect()->route('registration.create');
        }

        $maxAllowedStep = max(1, min(4, (int)$registration->step_completed + 1));

        if ($step !== null) {
            $requestedStep = max(1, min(4, (int)$step));
        } else if ($request->has('step')) {
            $requestedStep = max(1, min(4, (int)$request->input('step')));
        } else if (!empty($registration->revert_reason) || !empty($registration->reverted_at)) {
            $requestedStep = 1;
        } else {
            $requestedStep = $maxAllowedStep;
        }

        // If user tries to access a step ahead of their completed progress, redirect back
        if ($requestedStep > $maxAllowedStep) {
            return redirect()->route('registration.wizard', ['step' => $maxAllowedStep])
                ->with('error', 'Please complete step ' . $maxAllowedStep . ' first before proceeding.');
        }

        $step = $requestedStep;

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

        $step = (int) $step;
        $maxAllowedStep = max(1, min(4, (int)$registration->step_completed + 1));

        if ($step > $maxAllowedStep) {
            if ($request->input('action') === 'save_draft') {
                return response()->json([
                    'success' => false,
                    'message' => 'Please complete previous steps first.'
                ], 422);
            }
            return redirect()->route('registration.wizard', ['step' => $maxAllowedStep])
                ->with('error', 'Please complete previous steps first.');
        }

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

            if ($nextStep > 4) {
                return redirect()->route('registration.show', $registration->id)
                    ->with('success', 'Registration completed successfully!');
            }

            return redirect()->route('registration.wizard', ['step' => $nextStep])
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
        // Server-side allowlist validation
        $rules = [
            'prefix' => 'nullable|string|in:Dr.,Mr.,Mrs.,Prof.,Ms.',
            'full_name' => [$isDraft ? 'nullable' : 'required', 'string', 'max:100', 'regex:/^[A-Za-z.\s]+$/'],
            'designation' => $isDraft ? 'nullable|string|max:100' : 'required|string|max:100|in:Professor,Additional Professor,Associate Professor,Assistant Professor,Senior Resident,Junior Resident,Other',
            'other_designation' => ($request->designation === 'Other' && !$isDraft)
                ? ['required', 'string', 'max:100', 'regex:/^[A-Za-z0-9\s.,\-\/()]+$/']
                : ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9\s.,\-\/()]+$/'],
            'gender' => 'nullable|in:Male,Female',
            'dob' => $isDraft ? 'nullable|date|before_or_equal:-18 years' : 'required|date|before_or_equal:-18 years',
            'mobile_number' => ['nullable', 'string', 'max:18', 'regex:/^[0-9+\s\-]+$/'],
            'photo' => $registration->photo_path !== null ? 'nullable|image|mimes:jpg,jpeg,png|max:500' : ($isDraft ? 'nullable|image|mimes:jpg,jpeg,png|max:500' : 'required|image|mimes:jpg,jpeg,png|max:500'),
            'address' => [$isDraft ? 'nullable' : 'required', 'string', 'max:500', 'regex:/^[A-Za-z0-9\s,.\-\/#&():]+$/'],
            'state_id' => auth()->user()->delegate_type == 'Indian' 
                ? ($isDraft ? 'nullable|integer' : 'required|integer') 
                : ($isDraft ? 'nullable|string|max:50|regex:/^[A-Za-z0-9\s.\-]+$/' : 'required|string|max:50|regex:/^[A-Za-z0-9\s.\-]+$/'),
            'city' => [$isDraft ? 'nullable' : 'required', 'string', 'max:100', 'regex:/^[A-Za-z0-9\s.,\-\/]+$/'],
            'pin_code' => auth()->user()->delegate_type == 'Indian' 
                ? ($isDraft ? 'nullable|string|regex:/^[0-9]{6}$/' : 'required|string|regex:/^[0-9]{6}$/') 
                : ($isDraft ? 'nullable|string|max:10|regex:/^[A-Za-z0-9\s\-]+$/' : 'required|string|max:10|regex:/^[A-Za-z0-9\s\-]+$/'),
            'whatsapp_country_code' => ['nullable', 'string', 'regex:/^\+?[0-9]{1,5}$/'],
            'whatsapp_number' => [$isDraft ? 'nullable' : 'required', 'string', 'max:20', 'regex:/^[0-9+\s\-]+$/'],
            'dietary_preference' => 'nullable|in:Vegetarian,Non-Vegetarian',
            'id_proof_type' => $isDraft ? 'nullable|string|in:Aadhaar,PAN,Passport,Driving License,Voter-ID' : 'required|string|in:Aadhaar,PAN,Passport,Driving License,Voter-ID',
            'id_proof_number' => $isDraft ? 'nullable|string|max:50|regex:/^[A-Za-z0-9\/\-]+$/' : 'required|string|max:50|regex:/^[A-Za-z0-9\/\-]+$/',
            'id_proof_document' => $registration->id_proof_document_path !== null ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:200' : ($isDraft ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:200' : 'required|file|mimes:pdf,jpg,jpeg,png|max:200')
        ];

        $messages = [
            'full_name.regex' => 'Full name contains invalid characters. Only letters, dots, and spaces are allowed.',
            'address.regex' => 'Address contains invalid characters. Script tags and HTML elements are strictly prohibited.',
            'city.regex' => 'City contains invalid characters.',
            'other_designation.regex' => 'Designation contains invalid characters.',
            'designation.required' => 'Please select your designation.',
            'other_designation.required' => 'Please specify your designation.',
            'photo.required' => 'Please upload your profile photo to proceed.',
            'photo.image' => 'Profile photo must be a valid image file.',
            'photo.mimes' => 'Profile photo must be a JPG, JPEG, or PNG file.',
            'photo.max' => 'Profile photo must not exceed 500KB.',
            'dob.required' => 'Please select your Date of Birth.',
            'dob.before_or_equal' => 'You must be at least 18 years old.',
            'pin_code.required' => 'Please enter your PIN / Zip Code.',
            'pin_code.regex' => 'Indian PIN Code must be exactly 6 digits.',
            'pin_code.max' => 'International Zip Code must not exceed 10 characters.',
            'id_proof_number.required' => 'Please enter your ID Proof / Aadhaar / PAN number.',
            'id_proof_number.regex' => 'ID proof number contains invalid characters.',
            'id_proof_document.required' => 'Please upload your ID proof document to proceed.',
            'id_proof_document.file' => 'ID proof document must be a valid file.',
            'id_proof_document.mimes' => 'ID proof document must be a PDF, JPG, JPEG, or PNG file.',
            'id_proof_document.max' => 'ID proof document size must not exceed 200KB.',
            'whatsapp_number.required' => 'Please enter your WhatsApp number.',
            'whatsapp_number.regex' => 'WhatsApp number contains invalid characters.',
        ];

        // Specific validation for ID Proof Number based on ID Proof Type
        if ($request->filled('id_proof_type') && ($request->filled('id_proof_number') || !$isDraft)) {
            $idType = $request->id_proof_type;
            if ($idType === 'Aadhaar') {
                $rules['id_proof_number'] = [$isDraft ? 'nullable' : 'required', 'string', 'regex:/^[0-9]{12}$/'];
                $messages['id_proof_number.regex'] = 'Aadhaar number must be exactly 12 digits.';
            } elseif ($idType === 'PAN') {
                $rules['id_proof_number'] = [$isDraft ? 'nullable' : 'required', 'string', 'regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/'];
                $messages['id_proof_number.regex'] = 'PAN number must be 10 characters in standard format (e.g. ABCDE1234F).';
            } elseif ($idType === 'Voter-ID') {
                $rules['id_proof_number'] = [$isDraft ? 'nullable' : 'required', 'string', 'regex:/^[A-Za-z0-9]{8,12}$/'];
                $messages['id_proof_number.regex'] = 'Voter ID number must be 8-12 alphanumeric characters.';
            } elseif ($idType === 'Passport') {
                $rules['id_proof_number'] = [$isDraft ? 'nullable' : 'required', 'string', 'regex:/^[A-Za-z0-9]{6,12}$/'];
                $messages['id_proof_number.regex'] = 'Passport number must be 6-12 alphanumeric characters.';
            } elseif ($idType === 'Driving License') {
                $rules['id_proof_number'] = [$isDraft ? 'nullable' : 'required', 'string', 'regex:/^[A-Za-z0-9\/-]{8,20}$/'];
                $messages['id_proof_number.regex'] = 'Driving License number must be 8-20 alphanumeric characters.';
            }
        }

        $request->validate($rules, $messages);

        // Update user information
        $user = $registration->user;
        if ($request->filled('prefix'))
            $user->prefix = $request->prefix;
        if ($request->filled('full_name'))
            $user->full_name = $request->full_name;
        if ($request->filled('designation')) {
            $user->designation = $request->designation;
            $user->other_designation = $request->designation === 'Other' ? $request->other_designation : null;
        }
        if ($request->filled('gender'))
            $user->gender = $request->gender;
        if ($request->filled('dob'))
            $user->date_of_birth = $request->dob;
        if ($request->filled('mobile_number'))
            $user->mobile_number = $request->mobile_number;

        $user->save();

        // Handle file uploads and registration updates
        $updateData = [];

        if ($request->filled('designation')) {
            $updateData['designation'] = $request->designation;
            $updateData['other_designation'] = $request->designation === 'Other' ? $request->other_designation : null;
        }
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
        if ($request->filled('id_proof_number'))
            $updateData['id_proof_number'] = $request->id_proof_number;

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

            // ONLY Category 1 (IPHA Member) requires membership number
            if ($categoryId == 1) {
                $rules = array_merge($rules, [
                    'ismm_membership_no' => [$isDraft ? 'nullable' : 'required', 'string', 'max:50', 'regex:/^[A-Za-z0-9_-]+$/'],
                    'accompanying_persons' => 'nullable|integer|min:0|max:10',
                    'participate_in_cme' => 'nullable|boolean',
                ]);
            } else {
                $rules = array_merge($rules, [
                    'accompanying_persons' => 'nullable|integer|min:0|max:10',
                    'participate_in_cme' => 'nullable|boolean',
                    'ismm_membership_no' => 'nullable|string|max:50',
                ]);
            }
        } else {
            // International delegates
            $rules = [
                'accompanying_persons' => 'nullable|integer|min:0|max:10',
                'participate_in_cme' => 'nullable|boolean',
            ];
        }

        $messages = [
            'ismm_membership_no.required' => 'IPHA Membership Number is required for IPHA Member category.',
            'ismm_membership_no.regex' => 'IPHA Membership Number can only contain letters, numbers, hyphens (-), and underscores (_).',
            'delegate_category_id.required' => 'Please select your delegate category.',
            'ismm_membership_no.max' => 'IPHA Membership Number cannot exceed 50 characters.',
        ];

        $request->validate($rules, $messages);

        // Update data based on delegate type
        if ($delegateType == 'Indian') {
            $updateData = [
                'delegate_type' => 'Indian',
                'delegate_category_id' => $request->delegate_category_id,
                'accompanying_persons' => $request->accompanying_persons ?? 0,
                'participate_in_cme' => $request->participate_in_cme ?? false,
                'membership_no' => $request->delegate_category_id == 1 ? $request->ismm_membership_no : null,
                'cme_fee' => $request->participate_in_cme ? 2000 : 0,
                'accompanying_fee' => $request->accompanying_persons ? 5000 : 0,
            ];
        } else {
            // International delegate
            $updateData = [
                'delegate_type' => 'International',
                'delegate_category_id' => 6, // Foreign Delegates category
                'accompanying_persons' => $request->accompanying_persons ?? 0,
                'participate_in_cme' => $request->participate_in_cme ?? false,
                'membership_no' => null,
                'cme_fee' => $request->participate_in_cme ? 2000 : 0,
                'accompanying_fee' => $request->accompanying_persons ? 5000 : 0,
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
        do {
            $part1 = strtoupper(Str::random(4));
            $part2 = strtoupper(Str::random(4));
            $part3 = strtoupper(Str::random(4));
            $number = "{$part1}-{$part2}-{$part3}";
        } while (Registration::where('registration_number', $number)->exists());

        return $number;
    }

    public function generateUniqueRegistrationNumber()
    {
        return $this->generateRegistrationNumber();
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
                $registration = Registration::where('id', $registration->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // If already approved, return without creating duplicate
                if ($registration->status === 'Approved') {
                    DB::commit();
                    return redirect()->route('registration.index')
                        ->with('success', 'Registration is already approved.');
                }

                // Only send confirmation email on first submission
                $isFirstSubmission = empty($registration->submitted_at) || in_array($registration->status, ['Draft', 'Pending Payment']);

                $receiptPath = $request->file('payment_receipt')->store(
                    'International/payment_receipts/' . ($registration->registration_number ?? $registration->id),
                    'public'
                );

                // Check if payment already exists for this registration to prevent duplicate records
                $payment = Payment::where('registration_id', $registration->id)
                    ->where(function($q) use ($request) {
                        $q->where('transaction_id', $request->transaction_id)
                          ->orWhereIn('payment_status', ['Pending', 'Payment Submitted', 'Submitted', 'UNDER_VERIFICATION']);
                    })
                    ->latest()
                    ->first();

                $paymentData = [
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
                ];

                if ($payment) {
                    $payment->update($paymentData);
                } else {
                    $payment = Payment::create($paymentData);
                }

                // Update registration status
                if (empty($registration->acknowledgement_id)) {
                    $registration->acknowledgement_id = $registration->generateAcknowledgementId();
                }
                $registration->status = 'Payment Submitted';
                $registration->step_completed = 4;
                $registration->submitted_at = $registration->submitted_at ?? now();
                $registrationSaved = $registration->save();

                if (!$registrationSaved) {
                    throw new \Exception("Failed to update registration status.");
                }

                // Record Activity Log
                \App\Models\ActivityLog::record(
                    'DELEGATE_REGISTRATION_SUBMITTED',
                    "Delegate registration submitted for " . ($registration->user?->full_name ?? 'User') . ". Ack ID: {$registration->acknowledgement_id}",
                    ['acknowledgement_id' => $registration->acknowledgement_id, 'registration_id' => $registration->id],
                    $registration->user
                );

                DB::commit();

                // Send email notification to delegate ONLY upon first registration submission
                if ($isFirstSubmission) {
                    try {
                        $recipientEmail = $registration->user?->email;
                        if ($recipientEmail) {
                            $registration->loadMissing(['user', 'delegateCategory', 'country', 'state', 'latestPayment']);
                            
                            $ackPdf = null;
                            try {
                                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.registration', [
                                    'registration' => $registration,
                                    'applicationNumber' => $registration->registration_number ?? $registration->acknowledgement_id
                                ])->setPaper('a4', 'portrait')
                                    ->setOption('margin-top', 10)
                                    ->setOption('margin-bottom', 10)
                                    ->setOption('margin-left', 10)
                                    ->setOption('margin-right', 10);

                                $ackPdf = $pdf->output();
                            } catch (\Exception $pdfEx) {
                                Log::warning('PDF generation during delegate registration submission failed: ' . $pdfEx->getMessage());
                            }

                            Mail::send('emails.delegate_submission_confirmation', [
                                'registration' => $registration,
                                'user'         => $registration->user,
                                'payment'      => $payment,
                            ], function ($message) use ($recipientEmail, $registration, $ackPdf) {
                                $message->to($recipientEmail)
                                    ->subject('IPHACON 2027 : Delegate Registration Submitted (' . $registration->acknowledgement_id . ')')
                                    ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));

                                if ($ackPdf) {
                                    $docId = $registration->registration_number ?? $registration->acknowledgement_id;
                                    $message->attachData($ackPdf, "IPHACON_2027_Acknowledgement_Receipt_{$docId}.pdf", [
                                        'mime' => 'application/pdf'
                                    ]);
                                }
                            });
                        }
                    } catch (\Exception $mailEx) {
                        Log::error('Failed to send delegate registration submission confirmation email: ' . $mailEx->getMessage());
                    }
                }

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

        if (empty($registration->acknowledgement_id)) {
            $registration->acknowledgement_id = $registration->generateAcknowledgementId();
        }
        $registration->status = 'Pending Payment';
        $registration->step_completed = 4;
        $registration->save();

        // Indian delegate - redirect to payment gateway
        return redirect()->route('payment.gateway');
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

    public function showCmeWorkshop()
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->latest()->first();

        if (!$registration) {
            return redirect()->route('registration.create')
                ->with('error', 'Please complete your main conference registration first before applying for CME Workshop.');
        }

        if ($registration->status === 'Rejected') {
            return redirect()->route('registration.index')
                ->with('error', 'Your registration status is Rejected. Rejected delegates cannot apply for CME Workshop.');
        }

        $cmeApp = \App\Models\CmeApplication::where('registration_id', $registration->id)->latest()->first();

        return view('delegate.cme-workshop', compact('registration', 'user', 'cmeApp'));
    }

    public function processCmeWorkshop(Request $request)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->latest()->firstOrFail();

        if ($registration->status === 'Rejected') {
            return redirect()->route('registration.index')
                ->with('error', 'Your registration status is Rejected. Rejected delegates cannot apply for CME Workshop.');
        }

        if (!$request->has('participate_in_cme')) {
            return redirect()->back()->with('error', 'Please check the Pre-Conference CME Workshop box to proceed.');
        }

        // Create or get CME Application in Pending Payment state
        $cmeApp = \App\Models\CmeApplication::updateOrCreate(
            [
                'user_id' => $user->id,
                'registration_id' => $registration->id,
                'status' => 'Pending Payment'
            ],
            [
                'cme_fee' => 2000.00,
                'gst_amount' => 360.00,
                'total_amount' => 2360.00,
            ]
        );

        return redirect()->route('cme.payment.gateway');
    }
}
