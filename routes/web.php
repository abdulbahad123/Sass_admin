<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\AgencyController;
use App\Http\Controllers\SuperAdmin\AuditLogController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\ProductController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Public SaaS Checkout & Store Launch Routes
Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
Route::get('/membership/checkout', [CheckoutController::class, 'showCheckout'])->name('membership.checkout.show');
Route::post('/membership/checkout', [CheckoutController::class, 'processCheckout'])->name('membership.checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/whitelabel-panel/login', [LoginController::class, 'showLoginForm'])->name('whitelabel.login');
Route::get('/whitelabel-panel', function () {
    return redirect()->route('whitelabel.dashboard');
});
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Super Admin Restricted Routes
Route::prefix('admin')->name('admin.')->middleware([SuperAdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products Management (Launchshop, CRM, Builder, etc.)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Agencies & Master Plan Agencies Management
    Route::get('/agencies', [AgencyController::class, 'index'])->name('agencies.index');
    Route::post('/agencies', [AgencyController::class, 'store'])->name('agencies.store');
    Route::put('/agencies/{agency}', [AgencyController::class, 'update'])->name('agencies.update');
    Route::patch('/agencies/{agency}/products/{product}', [AgencyController::class, 'toggleProductAccess'])->name('agencies.toggle-product');
    Route::post('/agencies/{agency}/reprovision-db', [AgencyController::class, 'reprovisionDatabase'])->name('agencies.reprovision-db');
    Route::delete('/agencies/{agency}', [AgencyController::class, 'destroy'])->name('agencies.destroy');

    // Plans & Pricing Management
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans', [PlanController::class, 'store'])->name('plans.store');
    Route::put('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');

    // Subscriptions & Revenue Billing Oversight
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::patch('/subscriptions/{subscription}/status', [SubscriptionController::class, 'updateStatus'])->name('subscriptions.update-status');

    // Direct One-Click Admin Access (Credential-Free Login)
    Route::get('/agencies/{agency}/admin-login', [AgencyController::class, 'loginAsAgency'])->name('agencies.admin-login');
    
    // Profile & Platform Settings (Currency Switcher & Account Edit)
    Route::get('/profile', [\App\Http\Controllers\SuperAdmin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\SuperAdmin\ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/settings', [\App\Http\Controllers\SuperAdmin\ProfileController::class, 'updateSettings'])->name('settings.update');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    // Support Tickets Management (Staff & Agency Tickets)
    Route::get('/tickets', [\App\Http\Controllers\SuperAdmin\SuperAdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\SuperAdmin\SuperAdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\SuperAdmin\SuperAdminTicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('/tickets/{ticket}/assign-staff', [\App\Http\Controllers\SuperAdmin\SuperAdminTicketController::class, 'assignStaff'])->name('tickets.assign-staff');
    Route::patch('/tickets/{ticket}/status', [\App\Http\Controllers\SuperAdmin\SuperAdminTicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::delete('/tickets/{ticket}', [\App\Http\Controllers\SuperAdmin\SuperAdminTicketController::class, 'destroy'])->name('tickets.destroy');
});

// Shared Authenticated Product Single-Click SSO Launch Routes (Accessible to Super Admin, Master & White Label Agencies)
Route::middleware('auth')->group(function () {
    Route::get('/admin/products/{product}/admin-launch', [ProductController::class, 'launchAdmin'])->name('admin.products.admin-launch');
    Route::get('/products/{product}/admin-launch', [ProductController::class, 'launchAdmin'])->name('products.admin-launch');
});

// Master Agency Dedicated Portal Routes (Full Functionality Suite)
Route::prefix('master')->name('master.')->middleware([\App\Http\Middleware\MasterAgencyMiddleware::class])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\MasterAgency\MasterDashboardController::class, 'index'])->name('dashboard');

    // 1. Sub-Agencies
    Route::get('/sub-agencies', [\App\Http\Controllers\MasterAgency\MasterSubAgencyController::class, 'index'])->name('sub-agencies.index');
    Route::post('/sub-agencies', [\App\Http\Controllers\MasterAgency\MasterSubAgencyController::class, 'store'])->name('sub-agencies.store');
    Route::put('/sub-agencies/{agency}', [\App\Http\Controllers\MasterAgency\MasterSubAgencyController::class, 'update'])->name('sub-agencies.update');
    Route::delete('/sub-agencies/{agency}', [\App\Http\Controllers\MasterAgency\MasterSubAgencyController::class, 'destroy'])->name('sub-agencies.destroy');
    Route::patch('/sub-agencies/{agency}/products/{product}', [\App\Http\Controllers\MasterAgency\MasterSubAgencyController::class, 'toggleProduct'])->name('sub-agencies.toggle-product');

    // 2. Clients
    Route::get('/clients', [\App\Http\Controllers\MasterAgency\MasterClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [\App\Http\Controllers\MasterAgency\MasterClientController::class, 'store'])->name('clients.store');

    // 3. Products & Access
    Route::get('/products', [\App\Http\Controllers\MasterAgency\MasterProductController::class, 'index'])->name('products.index');

    // 4. Plans & Pricing
    Route::get('/plans', [\App\Http\Controllers\MasterAgency\MasterPlanController::class, 'index'])->name('plans.index');
    Route::post('/plans', [\App\Http\Controllers\MasterAgency\MasterPlanController::class, 'store'])->name('plans.store');
    Route::put('/plans/{plan}', [\App\Http\Controllers\MasterAgency\MasterPlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{plan}', [\App\Http\Controllers\MasterAgency\MasterPlanController::class, 'destroy'])->name('plans.destroy');

    // 5. Subscriptions
    Route::get('/subscriptions', [\App\Http\Controllers\MasterAgency\MasterSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::patch('/subscriptions/{subscription}/status', [\App\Http\Controllers\MasterAgency\MasterSubscriptionController::class, 'updateStatus'])->name('subscriptions.update-status');

    // 6. Billing & Invoices
    Route::get('/billing', [\App\Http\Controllers\MasterAgency\MasterBillingController::class, 'index'])->name('billing.index');

    // 7. Reports & Analytics
    Route::get('/reports', [\App\Http\Controllers\MasterAgency\MasterReportController::class, 'index'])->name('reports.index');

    // 8. Custom Branding
    Route::get('/branding', [\App\Http\Controllers\MasterAgency\MasterBrandingController::class, 'index'])->name('branding.index');
    Route::post('/branding', [\App\Http\Controllers\MasterAgency\MasterBrandingController::class, 'update'])->name('branding.update');

    // 9. Team Members
    Route::get('/team', [\App\Http\Controllers\MasterAgency\MasterTeamController::class, 'index'])->name('team.index');
    Route::post('/team', [\App\Http\Controllers\MasterAgency\MasterTeamController::class, 'store'])->name('team.store');
    Route::delete('/team/{user}', [\App\Http\Controllers\MasterAgency\MasterTeamController::class, 'destroy'])->name('team.destroy');
    
    // Roles Management
    Route::post('/team/roles', [\App\Http\Controllers\MasterAgency\MasterTeamController::class, 'storeRole'])->name('team.roles.store');
    Route::put('/team/roles/{role}', [\App\Http\Controllers\MasterAgency\MasterTeamController::class, 'updateRole'])->name('team.roles.update');
    Route::delete('/team/roles/{role}', [\App\Http\Controllers\MasterAgency\MasterTeamController::class, 'destroyRole'])->name('team.roles.destroy');

    // 10. Settings
    Route::get('/settings', [\App\Http\Controllers\MasterAgency\MasterSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\MasterAgency\MasterSettingsController::class, 'update'])->name('settings.update');

    // 11. Integrations
    Route::get('/integrations', [\App\Http\Controllers\MasterAgency\MasterIntegrationController::class, 'index'])->name('integrations.index');
});

// White Label Agency Dedicated Portal Routes
Route::prefix('whitelabel')->name('whitelabel.')->middleware([\App\Http\Middleware\WhiteLabelMiddleware::class])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\WhiteLabel\WhiteLabelDashboardController::class, 'index'])->name('dashboard');

    // Manage Section
    Route::get('/clients', [\App\Http\Controllers\WhiteLabel\WhiteLabelClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [\App\Http\Controllers\WhiteLabel\WhiteLabelClientController::class, 'store'])->name('clients.store');
    Route::get('/products', [\App\Http\Controllers\WhiteLabel\WhiteLabelProductController::class, 'index'])->name('products.index');
    Route::get('/subscriptions', [\App\Http\Controllers\WhiteLabel\WhiteLabelSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/team', [\App\Http\Controllers\WhiteLabel\WhiteLabelGeneralController::class, 'team'])->name('team.index');
    Route::get('/activity-logs', [\App\Http\Controllers\WhiteLabel\WhiteLabelGeneralController::class, 'activityLogs'])->name('activity-logs.index');

    // Billing & Support Section
    Route::get('/billing', [\App\Http\Controllers\WhiteLabel\WhiteLabelGeneralController::class, 'billing'])->name('billing.index');
    Route::get('/tickets', [\App\Http\Controllers\WhiteLabel\WhiteLabelTicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [\App\Http\Controllers\WhiteLabel\WhiteLabelTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\WhiteLabel\WhiteLabelTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\WhiteLabel\WhiteLabelTicketController::class, 'reply'])->name('tickets.reply');

    // Configuration Section
    Route::get('/branding', [\App\Http\Controllers\WhiteLabel\WhiteLabelGeneralController::class, 'branding'])->name('branding.index');
    Route::post('/branding', [\App\Http\Controllers\WhiteLabel\WhiteLabelGeneralController::class, 'updateBranding'])->name('branding.update');
});
