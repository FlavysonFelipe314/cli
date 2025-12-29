<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Finance\AccountController;
use App\Http\Controllers\Finance\TransactionController;
use App\Http\Controllers\Finance\ContactController;
use App\Http\Controllers\Finance\CategoryController;
use App\Http\Controllers\Finance\PayableController;
use App\Http\Controllers\Finance\ReceivableController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Registro (sem plano - para convites)
Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Webhook do Asaas (sem autenticação)
Route::post('webhook/asaas', [\App\Http\Controllers\Webhook\AsaasWebhookController::class, 'handle'])->name('webhook.asaas');

// Callback do Asaas após pagamento (público)
Route::get('payment/callback', [\App\Http\Controllers\SubscriptionController::class, 'paymentCallback'])->name('payment.callback');

// Página pública de planos e cadastro
Route::get('plans', [\App\Http\Controllers\PublicSubscriptionController::class, 'showPlans'])->name('public.plans');
Route::get('signup/{plan}', [\App\Http\Controllers\PublicSubscriptionController::class, 'showSignup'])->name('public.signup');
Route::post('signup/{plan}', [\App\Http\Controllers\PublicSubscriptionController::class, 'signup'])->name('public.signup.store');
Route::get('payment/success', [\App\Http\Controllers\PublicSubscriptionController::class, 'paymentSuccess'])->name('public.payment.success');

// Rota pública para aceitar convites (sem autenticação)
Route::get('team/invitation/{token}/accept', [\App\Http\Controllers\TeamInvitationController::class, 'accept'])->name('team.invitation.accept');

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard.index');
    }
    return redirect()->route('public.plans');
});

// Rotas protegidas
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
    
    // Perfil
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Assinaturas
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('subscriptions/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    
    // Loja de Módulos
    Route::get('modules/store', [\App\Http\Controllers\ModuleStoreController::class, 'index'])->name('modules.store');
    Route::post('modules/{module}/purchase', [\App\Http\Controllers\ModuleStoreController::class, 'purchase'])->name('modules.purchase');
    Route::get('modules/payment/callback', [\App\Http\Controllers\ModuleStoreController::class, 'paymentCallback'])->name('modules.payment.callback');
    
    // Financeiro - Contas (com verificação de assinatura)
    Route::prefix('dashboard/finance')->name('finance.')->middleware('subscription')->group(function () {
        Route::resource('accounts', AccountController::class);
        Route::resource('transactions', TransactionController::class);
        Route::resource('contacts', ContactController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('payables', PayableController::class);
        Route::resource('receivables', ReceivableController::class);
        Route::get('planning', [\App\Http\Controllers\Finance\FinancialGoalController::class, 'index'])->name('planning.index');
        Route::post('planning/goals', [\App\Http\Controllers\Finance\FinancialGoalController::class, 'store'])->name('planning.goals.store');
        Route::put('planning/goals/{financialGoal}', [\App\Http\Controllers\Finance\FinancialGoalController::class, 'update'])->name('planning.goals.update');
        Route::delete('planning/goals/{financialGoal}', [\App\Http\Controllers\Finance\FinancialGoalController::class, 'destroy'])->name('planning.goals.destroy');
        Route::resource('reconciliations', \App\Http\Controllers\Finance\BankReconciliationController::class);
        Route::resource('indirect-costs', \App\Http\Controllers\Finance\IndirectCostController::class);
        Route::post('indirect-costs/allocation', [\App\Http\Controllers\Finance\IndirectCostAllocationController::class, 'store'])->name('indirect-costs.allocation.store');
    });
    
        // Ferramentas (com verificação de assinatura)
        Route::prefix('tools')->name('tools.')->middleware('subscription')->group(function () {
            Route::get('prolabore', [\App\Http\Controllers\Tools\ProlaboreController::class, 'index'])->name('prolabore.index');
            Route::post('prolabore/calculate', [\App\Http\Controllers\Tools\ProlaboreController::class, 'calculate'])->name('prolabore.calculate');
            Route::get('pricing', [\App\Http\Controllers\Tools\PricingController::class, 'index'])->name('pricing.index');
            Route::post('pricing/calculate', [\App\Http\Controllers\Tools\PricingController::class, 'calculate'])->name('pricing.calculate');
            Route::get('employee-cost', [\App\Http\Controllers\Tools\EmployeeCostController::class, 'index'])->name('employee-cost.index');
            Route::post('employee-cost/calculate', [\App\Http\Controllers\Tools\EmployeeCostController::class, 'calculate'])->name('employee-cost.calculate');
            Route::post('employee-cost', [\App\Http\Controllers\Tools\EmployeeCostController::class, 'store'])->name('employee-cost.store');
            Route::delete('employee-cost/{employeeCostProfile}', [\App\Http\Controllers\Tools\EmployeeCostController::class, 'destroy'])->name('employee-cost.destroy');
            Route::resource('compliance', \App\Http\Controllers\Tools\ComplianceController::class);
        });
        
        // Gestão (com verificação de assinatura)
        Route::prefix('management')->name('management.')->middleware('subscription')->group(function () {
            Route::resource('team', \App\Http\Controllers\Management\TeamController::class);
            Route::resource('tasks', \App\Http\Controllers\Management\TaskController::class);
            Route::post('tasks/{task}/move', [\App\Http\Controllers\Management\TaskController::class, 'move'])->name('tasks.move');
            Route::resource('task-columns', \App\Http\Controllers\Management\TaskColumnController::class)->except(['index', 'show']);
            Route::get('calendar', [\App\Http\Controllers\Management\CalendarController::class, 'index'])->name('calendar.index');
        });
        
        // Equipes (acessível mesmo sem plano)
        Route::prefix('team')->name('team.')->group(function () {
            Route::get('teams', [\App\Http\Controllers\TeamInvitationController::class, 'teams'])->name('teams');
            // Tarefas de equipe (acessível para membros sem plano)
            Route::get('{team}/tasks', [\App\Http\Controllers\Management\TaskController::class, 'index'])->name('tasks');
            Route::post('{team}/tasks', [\App\Http\Controllers\Management\TaskController::class, 'store'])->name('tasks.store');
        });
        
        // Admin - Super Admin apenas
        Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
            Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::resource('plans', PlanController::class);
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
            Route::resource('modules', \App\Http\Controllers\Admin\ModuleController::class);
            Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
            Route::put('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        });
    });
