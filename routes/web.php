<?php
// routes/web.php — VERSION FINALE avec portail client

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PortailClientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\GestionnaireController;
use App\Http\Controllers\Admin\StatistiquesController;
use App\Http\Controllers\Gestionnaire\DashboardController as GestDashboard;
use App\Http\Controllers\Gestionnaire\MaterielController;
use App\Http\Controllers\Gestionnaire\ClientController;
use App\Http\Controllers\Gestionnaire\AffectationController;
use App\Http\Controllers\Gestionnaire\MaintenanceController;
use App\Http\Controllers\Gestionnaire\RapportController;
use App\Http\Controllers\Gestionnaire\SignalementController;

// ── Accueil → login
Route::get('/', fn() => redirect('/login'));

// ── Auth
Route::get('/login',   [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login',  [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ============================================================
// PORTAIL CLIENT (accès public via token unique)
// ============================================================
Route::prefix('portail')->name('portail.')->group(function () {
    Route::get('/{token}',                        [PortailClientController::class, 'index'])->name('index');
    Route::get('/{token}/signaler/{affectation}', [PortailClientController::class, 'signalerForm'])->name('signaler.form');
    Route::post('/{token}/signaler/{affectation}',[PortailClientController::class, 'signalerStore'])->name('signaler.store');
});

// ============================================================
// ADMIN
// ============================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard',    [AdminDashboard::class,      'index'])->name('dashboard');
    Route::get('/statistiques', [StatistiquesController::class, 'index'])->name('statistiques');

    Route::get('/gestionnaires',                   [GestionnaireController::class, 'index'])->name('gestionnaires.index');
    Route::post('/gestionnaires',                  [GestionnaireController::class, 'store'])->name('gestionnaires.store');
    Route::delete('/gestionnaires/{gestionnaire}', [GestionnaireController::class, 'destroy'])->name('gestionnaires.destroy');

    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
});

// ============================================================
// GESTIONNAIRE
// ============================================================
Route::prefix('gestionnaire')->name('gestionnaire.')->middleware(['auth', 'role:gestionnaire'])->group(function () {
    Route::get('/dashboard', [GestDashboard::class, 'index'])->name('dashboard');

    // Matériels
    Route::resource('materiels', MaterielController::class);
    Route::post('materiels/{materiel}/archiver', [MaterielController::class, 'archiver'])->name('materiels.archiver');

    // Clients
    Route::get('/clients',           [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create',    [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients',          [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}',  [ClientController::class, 'show'])->name('clients.show');

    // Affectations
    Route::get('/affectations',                 [AffectationController::class, 'index'])->name('affectations.index');
    Route::get('/affectations/create',          [AffectationController::class, 'create'])->name('affectations.create');
    Route::post('/affectations',                [AffectationController::class, 'store'])->name('affectations.store');
    Route::post('/affectations/{id}/restituer', [AffectationController::class, 'restituer'])->name('affectations.restituer');

    // Maintenances
    Route::get('/maintenances',                     [MaintenanceController::class, 'index'])->name('maintenances.index');
    Route::get('/maintenances/create',              [MaintenanceController::class, 'create'])->name('maintenances.create');
    Route::post('/maintenances',                    [MaintenanceController::class, 'store'])->name('maintenances.store');
    Route::get('/maintenances/{maintenance}/edit',  [MaintenanceController::class, 'edit'])->name('maintenances.edit');
    Route::put('/maintenances/{maintenance}',       [MaintenanceController::class, 'update'])->name('maintenances.update');

    // Signalements clients
    Route::get('/signalements',                     [SignalementController::class, 'index'])->name('signalements.index');
    Route::post('/signalements/{signalement}/lu',   [SignalementController::class, 'marquerLu'])->name('signalements.lu');
    Route::post('/signalements/{signalement}/traite',[SignalementController::class, 'marquerTraite'])->name('signalements.traite');

    // Rapports
    Route::get('/rapports',              [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/inventaire',   [RapportController::class, 'inventaire'])->name('rapports.inventaire');
    Route::get('/rapports/affectations', [RapportController::class, 'affectations'])->name('rapports.affectations');
    Route::get('/rapports/pdf/{type}',   [RapportController::class, 'exportPdf'])->name('rapports.pdf');
    Route::get('/rapports/excel/{type}', [RapportController::class, 'exportExcel'])->name('rapports.excel');

    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
});