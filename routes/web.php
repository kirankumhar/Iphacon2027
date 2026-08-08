<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbstractSubmissionController;
// ****** Admin ********
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\AdminAbstractController;
use Illuminate\Support\Facades\Artisan;

// Delegate Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', [RegisterController::class, 'showRegistrationForm']);

// Delegate Registration routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// 🔥 PASSWORD RESET ROUTES
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

// Email verification routes
Route::get('email/verify', [RegisterController::class, 'showVerificationNotice'])
    ->name('verification.notice');

Route::post('email/verify-otp', [RegisterController::class, 'verifyOtp'])
    ->middleware(['throttle:10,1'])
    ->name('verification.verify-otp');

Route::post('email/verification-notification', [RegisterController::class, 'resendVerification'])
    ->middleware(['throttle:6,1'])
    ->name('verification.resend');

// Dashboard route (protected)
// Route::get('dashboard', function () {
//     return view('delegate.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// CAPTCHA route (if using mews/captcha package)
Route::get('captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha')->name('captcha');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route
    Route::get('dashboard', function () {
        $user = Auth::user();
        $registration = \App\Models\Registration::where('user_id', $user->id)->first();
        $abstract = \App\Models\AbstractSubmission::where('user_id', $user->id)->first();
        return view('delegate.dashboard', compact('registration', 'abstract'));
    })->name('dashboard');

    // Profile routes
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    // Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Abstract Submission routes
    Route::get('abstract-submission', [AbstractSubmissionController::class, 'create'])->name('abstract.create');
    Route::post('abstract-submission', [AbstractSubmissionController::class, 'store'])->name('abstract.store');
    Route::get('abstract/view/{id?}', [AbstractSubmissionController::class, 'show'])->name('abstract.show');
    Route::get('abstract/download-pdf/{id}', [AbstractSubmissionController::class, 'downloadPdf'])->name('abstract.download-pdf');

    // 🔥 REGISTRATION ROUTES
    Route::get('registration', [RegistrationController::class, 'index'])->name('registration.index');
    Route::get('registration/create', [RegistrationController::class, 'create'])->name('registration.create');
    Route::post('registration', [RegistrationController::class, 'store'])->name('registration.store');
    Route::get('registration/{id}', [RegistrationController::class, 'show'])->name('registration.show');
    Route::get('registration/{id}/edit', [RegistrationController::class, 'edit'])->name('registration.edit');
    Route::put('registration/{id}', [RegistrationController::class, 'update'])->name('registration.update');

    Route::get('registration/wizard/{token?}', [RegistrationController::class, 'wizard'])->name('registration.wizard');
    Route::post('registration/wizard/{token}', [RegistrationController::class, 'storeStep'])->name('registration.store-step');

    // 🎓 CME WORKSHOP ROUTES
    Route::get('apply-cme-workshop', [RegistrationController::class, 'showCmeWorkshop'])->name('cme.apply');
    Route::post('apply-cme-workshop', [RegistrationController::class, 'processCmeWorkshop'])->name('cme.process');
    Route::get('cme-payment/gateway/{encCmeAppId}', [App\Http\Controllers\PaymentController::class, 'cmeGateway'])->name('cme.payment.gateway');
    Route::post('cme-payment/process/{cmeAppId}', [App\Http\Controllers\PaymentController::class, 'processCmePayment'])->name('cme.payment.process');


    Route::get('/delegate-download-receipt/{registration_number}', [AdminRegistrationController::class, 'receiptCumRegistrationSlipDownload'])
     ->name('delgate.download.receipt');

    // 💰 REGISTRATION PAYMENT ROUTES

    Route::get('payment/gateway/{registration}', [App\Http\Controllers\PaymentController::class, 'gateway'])
        ->name('payment.gateway');

    Route::post('payment/process/{registration}', [App\Http\Controllers\PaymentController::class, 'processPayment'])
        ->name('payment.process');

    Route::get('payment/success/{registration}', [App\Http\Controllers\PaymentController::class, 'success'])
        ->name('payment.success');

    //  Route::post('response', [App\Http\Controllers\PaymentController::class, 'response'])->name('response');


    Route::get('payment/failed/{registration}', [App\Http\Controllers\PaymentController::class, 'failed'])
        ->name('payment.failed');

    // API route for states
    Route::get('api/states/{country}', function ($countryId) {
        $states = \App\Models\State::where('country_id', $countryId)->get();
        return response()->json($states);
    });
});

// Admin Authentication Routes
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminLoginController::class, 'login']);
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::prefix('admin')->middleware(['admin'])->group(function () {

    // Dashboard - All roles
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware('admin:dashboard.view')
        ->name('admin.dashboard');

    Route::get('/profile/change-password', [DashboardController::class, 'getChangePassword'])->name('admin.profile.change-password');
    Route::post('/update-password', [DashboardController::class, 'updatePassword'])->name('admin.user.update.password');

    // User Management - Admin & Super Admin
    Route::middleware('admin:users.view')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('users/{id}', [UserController::class, 'show'])->name('admin.users.show');
    });

    Route::middleware('admin:users.create')->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
    });

    Route::middleware('admin:users.edit')->group(function () {
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    });

    Route::middleware('admin:users.delete')->group(function () {
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });
    Route::post('/view_registrations/{type}', [AdminRegistrationController::class, 'getRegistrations']);
    Route::get('/download-receipt/{registration_number}', [AdminRegistrationController::class, 'receiptCumRegistrationSlipDownload'])->name('download.receipt');
    Route::get('/show-registration-details/{registration_number}', [AdminRegistrationController::class, 'viewRegistrationDetails'])->name('show-registration-details');

    Route::get('/cme-delegates', [AdminRegistrationController::class, 'cmeDelegates'])->name('admin.cme-delegates');
    Route::get('/paid-payments', [AdminRegistrationController::class, 'paidPayments'])->name('paid-payments');
    Route::get('/failed-payments', [AdminRegistrationController::class, 'failedPayments'])->name('failed-payments');
    Route::get('/submitted-delegates', [AdminRegistrationController::class, 'submittedDelegates'])->name('submitted-delegates');
    Route::get('/international-payment-submitted-delegates', [AdminRegistrationController::class, 'internationalPaymentSubmittedDelegates'])->name('international-payment-submitted-delegates');
    Route::get('/indian-approved-delegates', [AdminRegistrationController::class, 'approvedIndDelegates'])->name('indian-approved-delegates');
    Route::get('/indian-incomplete-delegates', [AdminRegistrationController::class, 'indianIncompleteDelegates'])->name('indian-incomplete-delegates');
    Route::get('/international-approved-delegates', [AdminRegistrationController::class, 'internationalApprovedDelegates'])->name('international-approved-delegates');
    Route::get('/deleted-delegates', [AdminRegistrationController::class, 'deletedRegistration'])->name('deleted-delegates');
    Route::get('/international-rejected-delegates', [AdminRegistrationController::class, 'internationalRejectedDelegates'])->name('international-rejected-delegates');
    Route::get('/international-reverted-delegates', [AdminRegistrationController::class, 'internationalRevertedDelegates'])->name('international-reverted-delegates');

    Route::post('/reject-regis', [AdminRegistrationController::class, 'rejectRegis'])->name('student-reject-regis');
    Route::post('/revert-regis', [AdminRegistrationController::class, 'revertRegis'])->name('student-revert-regis');
    Route::post('/approved-regis', [AdminRegistrationController::class, 'approvedRegis'])->name('student-approved-regis');
    Route::post('/delete-regis', [AdminRegistrationController::class, 'deleteRegis'])->name('student-regis-delete');

    // Abstract Submissions Management
    Route::get('/abstracts', [AdminAbstractController::class, 'index'])->name('admin.abstracts.index');
    Route::post('/abstracts/data', [AdminAbstractController::class, 'getAbstracts'])->name('admin.abstracts.data');
    Route::get('/abstracts/{id}', [AdminAbstractController::class, 'show'])->name('admin.abstracts.show');
    Route::post('/abstracts/{id}/status', [AdminAbstractController::class, 'updateStatus'])->name('admin.abstracts.update-status');


    // Admin Management - Super Admin Only
    Route::middleware('admin.role:Super Admin')->prefix('admins')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.admins.index');
        Route::get('create', [AdminController::class, 'create'])->name('admin.admins.create');
        Route::post('/', [AdminController::class, 'store'])->name('admin.admins.store');
        Route::get('{id}/edit', [AdminController::class, 'edit'])->name('admin.admins.edit');
        Route::put('{id}', [AdminController::class, 'update'])->name('admin.admins.update');
        Route::delete('{id}', [AdminController::class, 'destroy'])->name('admin.admins.destroy');
    });
});



