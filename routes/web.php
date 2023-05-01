<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (auth()->guard('admin')->check()) {
        return redirect()->route('admin.admin.index');
    } elseif (auth()->guard('customer')->check()) {
        return redirect()->route('customer.customer.index');
    } else {
        return redirect()->route('landing.index');
    }
});

Route::get('/landing', 'LandingController@index')->name('landing.index');
Route::get('/product/{id}', 'LandingController@product')->name('landing.product');
Route::get('/product/detail/{property}', 'LandingController@detail')->name('landing.detail');

// AUTH
Route::namespace('Auth')->prefix('secure/auth/')->name('auth.')->group(function () {
    // REGISTER
    Route::prefix('register/')->name('register.')->group(function () {
        Route::get('/admin', 'RegisterController@form_admin')->name('form_admin');
        Route::post('/admin', 'RegisterController@register_admin')->name('register_admin');
        Route::get('/customer', 'RegisterController@form_customer')->name('form_customer');
        Route::post('/customer', 'RegisterController@register_customer')->name('register_customer');

        // Route::get('/customer', 'RegisterController@form_customer')->name('form_customer');
        // Route::post('/customer', 'RegisterController@register_customer')->name('register_customer');
    });
    // LOGIN
    // http://127.0.0.1:8000/secure/auth/login/admin
    Route::prefix('login/')->name('login.')->group(function () {
        Route::get('/admin', 'LoginController@form_admin')->name('form_admin');
        Route::post('/admin', 'LoginController@login_admin')->name('login_admin');

        Route::get('customer', 'LoginController@form_customer')->name('form_customer');
        Route::post('/customer', 'LoginController@login_customer')->name('login_customer');
    });
    // LOGOUT
    Route::get('/admin/logout', 'LogoutController@logout_admin')->name('logout.admin');
    Route::get('/customer/logout', 'LogoutController@logout_customer')->name('logout.customer');
});

// admin - logged
Route::namespace('Admin')->prefix('admin/')->name('admin.')->group(function () {
    Route::middleware('IsAdmin')->group(function () {
        // DASHBOARD
        Route::get('/', 'AdminController@index');
        Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');
        Route::resource('admin', 'AdminController'); //index, show, edit, update, create, store, destroy
        Route::resource('customer', 'CustomerController');
        Route::resource('category_property', 'CategoryPropertyController');
        Route::resource('sub_category_property', 'SubCategoryPropertyController');
        Route::resource('property', 'PropertyController');
        Route::get('add_stock/{id}', 'PropertyController@add_stock')->name('property.add_stock');
        Route::put('add_stock/{id}', 'PropertyController@store_stock')->name('property.store_stock');
        Route::resource('category_payment', 'CategoryPaymentController');
        Route::resource('transaction', 'TransactionController');
        // Route::resource('transaction_detail', 'TransactionDetailController');
        Route::get('delivery', 'DeliveryController@index')->name('delivery.index');
        Route::get('delivery/{transaction}', 'DeliveryController@show')->name('delivery.show');
        Route::get('evidence_payment', 'TransactionController@evidence_payment_index')->name('evidence_payment.index');
        Route::get('evidence_payment/{transaction}', 'TransactionController@evidence_payment_show')->name('evidence_payment.show');
        Route::put('evidence_payment/{transaction}', 'TransactionController@evidence_payment_approve')->name('evidence_payment.approve');
        Route::delete('evidence_payment/{transaction}', 'TransactionController@evidence_payment_reject')->name('evidence_payment.reject');
        Route::post('evidence_payment/{transaction}', 'TransactionController@evidence_payment_notify_user')->name('evidence_payment.notify_user');

        Route::get('submission_premium', 'SubmissionPremiumController@submission_premium_index')->name('submission_premium.index');
        Route::get('submission_premium/{submission_premium_customer}', 'SubmissionPremiumController@submission_premium_show')->name('submission_premium.show');
        Route::put('submission_premium/{submission_premium_customer}', 'SubmissionPremiumController@submission_premium_approve')->name('submission_premium.approve');
        Route::delete('submission_premium/{submission_premium_customer}', 'SubmissionPremiumController@submission_premium_reject')->name('submission_premium.reject');

        Route::get("submission/credit/payment", 'SubmissionCreditPaymentController@index')->name('submission.credit.payment.index');
        Route::get("submission/credit/payment/{submission_credit_payment}", "SubmissionCreditPaymentController@show")->name("submission.credit.payment.show");
        Route::put("submission/credit/payment/approve/{submission_credit_payment}", "SubmissionCreditPaymentController@approve")->name("submission.credit.payment.approve");
        Route::put("submission/credit/payment/reject/{submission_credit_payment}", "SubmissionCreditPaymentController@reject")->name("submission.credit.payment.reject");
        Route::delete("submission/credit/payment/{submission_credit_payment}", "SubmissionCreditPaymentController@destroy")->name("submission.credit.payment.delete");

        Route::get("submission/down/payment", 'SubmissionDownPaymentController@index')->name('submission.dp.payment.index');
        Route::get("submission/down/payment/{submission_down_payment}", "SubmissionDownPaymentController@show")->name("submission.dp.payment.show");
        Route::put("submission/down/payment/approve/{submission_down_payment}", "SubmissionDownPaymentController@approve")->name("submission.dp.payment.approve");
        Route::put("submission/down/payment/reject/{submission_down_payment}", "SubmissionDownPaymentController@reject")->name("submission.dp.payment.reject");
        Route::delete("submission/down/payment/{submission_down_payment}", "SubmissionDownPaymentController@destroy")->name("submission.dp.payment.delete");

        Route::get("transfer/payment", 'TransferPaymentController@index')->name('transfer.payment.index');
        Route::delete("transfer/payment/{transaction}", "TransferPaymentController@destroy")->name("transfer.payment.delete");

        Route::get("submission/transfer/payment", 'SubmissionTransferPaymentController@index')->name('submission.transfer.payment.index');
        Route::get("submission/transfer/payment/{submission_transfer_payment}", "SubmissionTransferPaymentController@show")->name("submission.transfer.payment.show");
        Route::put("submission/transfer/payment/approve/{submission_transfer_payment}", "SubmissionTransferPaymentController@approve")->name("submission.transfer.payment.approve");
        Route::put("submission/transfer/payment/reject/{submission_transfer_payment}", "SubmissionTransferPaymentController@reject")->name("submission.transfer.payment.reject");
        Route::delete("submission/transfer/payment/{submission_transfer_payment}", "SubmissionTransferPaymentController@destroy")->name("submission.transfer.payment.delete");

        Route::get('sales/report', 'SalesReportController@index')->name('sales.report.index');
        Route::get('sales/report/print', array('as' => 'sales.report.print', 'uses' => 'SalesReportController@print'));

        // IMPORT DATA
        Route::prefix('import')->name('import.')->group(function () {
            // admin
            Route::get('admin', 'AdminController@form_import')->name('form_admin');
            Route::post('admin', 'AdminController@import')->name('admin');
        });

        // EXPORT DATA
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('admin', 'AdminController@export')->name('admin');
        });

        Route::prefix('stock')->name('stock.')->group(function () {
            Route::get('/', 'StockController@index')->name('index');
            Route::get('/{id}', 'StockController@edit')->name('edit');
        });
    });
});


// customer - logged
Route::namespace('Customer')->prefix('customer/')->name('customer.')->group(function () {
    Route::middleware('IsCustomer')->group(function () {
        Route::resource('customer', 'CustomerController');
        Route::resource('transaction', 'TransactionController');
        Route::resource('cart', 'CartController');
        Route::get('checkout/single', 'CheckoutController@single_index')->name('checkout.single');
        Route::post('checkout/single/store', 'CheckoutController@single_store')->name('checkout.single.store');
        Route::get('checkout/edit/quantity/{cart}', 'CheckoutController@edit_quantity')->name('checkout.edit.quantity');
        Route::put('checkout/update/quantity/{cart}', 'CheckoutController@update_quantity')->name('checkout.update.quantity');
        Route::get('checkout/single/edit/quantity', 'CheckoutController@single_edit_quantity')->name('checkout.single.edit.quantity');
        Route::resource('checkout', 'CheckoutController');
        Route::get('profile', 'ProfileController@index')->name('profile.index');
        Route::get('profile/submission_premium', 'ProfileController@submission_premium_index')->name('profile.submission_premium.index');
        Route::post('profile/submission_premium', 'ProfileController@submission_premium_store')->name('profile.submission_premium.store');
        // Route::resource('transaction_detail', 'TransactionDetailController');
        Route::get('/profile/change/password/{customer}', 'ProfileController@change_password_index')->name('profile.change.password.index');
        Route::put('/profile/change/password/{customer}', 'ProfileController@change_password_update')->name('profile.change.password.update');
        Route::get('/profile/change/{customer}', 'ProfileController@edit')->name('profile.edit');
        Route::put('/profile/change/{customer}', 'ProfileController@update')->name('profile.update');

        Route::resource('notification', 'NotificationController');
        Route::get('/notifications/transaction', 'NotificationController@transaction_index')->name('notification.transaction.index');
        Route::get('/notifications/transaction/{transaction}', 'NotificationController@transaction_show')->name('notification.transaction.show');
        Route::get('/notifications/transaction/edit/{transaction}', 'NotificationController@transaction_edit')->name('notification.transaction.edit');
        Route::post('/notifications/transaction/edit/{transactionId}', 'NotificationController@transaction_edit_store')->name('notification.transaction.edit_store');
        Route::get('/credit/payment/{transaction}', 'NotificationController@credit_payment_index')->name('credit.payment.index');
        Route::get('/down/payment/{transaction}', 'NotificationController@dp_payment_index')->name('dp.payment.index');
        Route::post('/credit/payment/{transaction}', 'NotificationController@credit_payment_store')->name('credit.payment.store');
        Route::post('/down/payment/{transaction}', 'NotificationController@dp_payment_store')->name('dp.payment.store');
        Route::get('/transfer/payment/{transaction}', 'NotificationController@transfer_payment_index')->name('transfer.payment.index');
        Route::post('/transfer/payment/{transaction}', 'NotificationController@transfer_payment_store')->name('transfer.payment.store');

        Route::get('delivery', 'DeliveryController@index')->name('delivery.index');
        Route::get('delivery/{delivery}', 'DeliveryController@show')->name('delivery.show');
        Route::get('delivery/accept/{deliveryId}', 'DeliveryController@accept_delivery')->name('delivery.accept_delivery');
    });
});


// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
