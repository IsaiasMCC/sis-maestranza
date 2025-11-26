<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetallePagoController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\PlanPagoController;
use App\Http\Controllers\PresupuestoServicioController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReservaReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ServicioReporteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsuarioController;
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

// Auth

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('/register', [AuthenticatedSessionController::class, 'index_create'])
    ->name('index_create')
    ->middleware('guest');

Route::post('/register', [AuthenticatedSessionController::class, 'register'])
    ->name('register')
    ->middleware('guest');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

Route::get('/reservas/reportes', [ReservaReporteController::class, 'index'])->name('reservas.reportes');
Route::get('/reservas/reportes/export', [ReservaReporteController::class, 'export'])->name('reservas.reportes.export');
Route::get('/servicios/reportes', [ServicioReporteController::class, 'index'])->name('servicios.reportes');
Route::get('/servicios/reportes/export', [ServicioReporteController::class, 'export'])->name('servicios.reportes.export');

Route::get('/search', [DashboardController::class, 'index_search'])->name('search');
Route::resource('roles', RoleController::class);
Route::patch('/roles/permisos/{id}', [RoleController::class, 'updatePermissions'])->name('roles.update.permissions');
Route::get('/contratar-servicios', [ReservaController::class, 'catalogo'])->name('reservas.catalogo');
Route::resource('usuarios', UsuarioController::class);
Route::resource('servicios', ServicioController::class);
Route::resource('avances', AvanceController::class);
Route::resource('detalle-pago', DetallePagoController::class);
Route::resource('metodo-pagos', MetodoPagoController::class);
Route::resource('presupuesto-servicios', PresupuestoServicioController::class);
Route::resource('reservas', ReservaController::class);

Route::get('/plan-pagos', [PlanPagoController::class, 'index'])->name('plan-pagos.index');
Route::get('/plan-pagos/{planPago}', [PlanPagoController::class, 'show'])->name('plan-pagos.show');


Route::get('/mis-reservas', [ReservaController::class, 'misReservas'])->name('reservas.mis-reservas');
Route::get('/mis-reservas/detalle/{id}', [ReservaController::class, 'misReservasDetalles'])->name('reservas.mis-reservas-detalle');
Route::get('/mis-reservas-empleado', [ReservaController::class, 'misReservasEmpleado'])->name('reservas.mis-reservas-empleado');
Route::get('/mis-reservas-empleado/detalle/{id}', [ReservaController::class, 'misReservasDetallesEmpleado'])->name('reservas.mis-reservas-detalle-empleado');

Route::post('/planes-pago', [PlanPagoController::class, 'store'])->name('planes.store');
// Mostrar la vista de pagar
Route::get('/plan-pagos/{planPago}/pagar', [PlanPagoController::class, 'pagar'])->name('plan-pagos.pagar');

// Guardar un pago
Route::post('/plan-pagos/{planPago}/pagar', [PlanPagoController::class, 'guardarPago'])->name('plan-pagos.guardarPago');


Route::resource('tickets', TicketController::class);
Route::resource('mensajes', MensajeController::class);
