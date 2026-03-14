<?php

use App\Helpers\Seguridad;
use App\Http\Controllers\Admin\HabitacionesController;
use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Admin\ServiciosController;
use App\Http\Controllers\Admin\TiposHabitacionController;
use App\Http\Controllers\UbicacionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\YoutubeController;
use App\Http\Controllers\PagoPruebaController;
use App\Http\Controllers\ReservaTestController;

Route::get('/Home', function () {
    return view('Home');
});

Route::get('/Habitaciones', function () {
    return view('Habitaciones');
});



Route::get('/Login', [AuthController::class, 'vista'])->name('login');
Route::post('/Login', [AuthController::class, 'login']);
Route::post('/Registro', [AuthController::class, 'registro']);
Route::post('/Logout', [AuthController::class, 'cerrarSesion']);


Route::get('/ubicacion', [UbicacionController::class, 'show']);


Route::get('/Admin', [PanelController::class, 'index']);

Route::prefix('Admin')->group(function () {

    Route::get('/Tipos-Habitacion', [TiposHabitacionController::class, 'index']);
    Route::get('/Tipos-Habitacion/Crear', [TiposHabitacionController::class, 'crear']);
    Route::post('/Tipos-Habitacion', [TiposHabitacionController::class, 'guardar']);

    Route::get('/Tipos-Habitacion/{id}/Editar', [TiposHabitacionController::class, 'editar']);
    Route::put('/Tipos-Habitacion/{id}', [TiposHabitacionController::class, 'actualizar']);

    Route::delete('/Tipos-Habitacion/{id}', [TiposHabitacionController::class, 'eliminar']);

    Route::get('/Tipos-Habitacion/{id}/Servicios', [TiposHabitacionController::class, 'servicios']);
    Route::post('/Tipos-Habitacion/{id}/Servicios', [TiposHabitacionController::class, 'asignarServicio']);
    Route::delete('/Tipos-Habitacion/{id}/Servicios/{servicio}', [TiposHabitacionController::class, 'quitarServicio']);



    Route::get('/Servicios', [ServiciosController::class, 'index']);
    Route::post('/Servicios', [ServiciosController::class, 'store']);
    Route::put('/Servicios/{id}', [ServiciosController::class, 'update']);
    Route::delete('/Servicios/{id}', [ServiciosController::class, 'destroy']);




    Route::get('/Habitaciones', [HabitacionesController::class, 'index']);
    Route::post('/Habitaciones', [HabitacionesController::class, 'store']);
    Route::put('/Habitaciones/{id}', [HabitacionesController::class, 'update']);
    Route::delete('/Habitaciones/{id}', [HabitacionesController::class, 'destroy']);
});

Route::get('/Redes', function () {
    return view('Redes');
});
Route::apiResource('users', UserController::class);
Route::apiResource('posts', PostController::class);


Route::get('/youtube', [YoutubeController::class, 'index']);
Route::get('/youtube-search', [YoutubeController::class, 'search']);

Route::get('/Prueba', function () {
    return view('Login');
});




// Rutas de Pago Prueba
Route::get('/pago-prueba', [PagoPruebaController::class, 'index'])->name('pago.prueba');
Route::post('/procesar-pago-prueba', [PagoPruebaController::class, 'procesar'])->name('pago.procesar');

// Rutas de Reservación Test
Route::get('/reservacion-test', [ReservaTestController::class,'vista']);
Route::post('/crear-pago-test', [ReservaTestController::class,'crearPago']);

// Rutas de Reservación
Route::get('/reservaciones', [ReservacionController::class, 'index'])->name('reservaciones.index');
Route::post('/reservaciones/store', [ReservacionController::class, 'store'])->name('reservaciones.store');
Route::post('/reservaciones/disponibilidad', [ReservacionController::class, 'disponibilidad'])->name('reservaciones.disponibilidad');
Route::get('/reservaciones/{id}', [ReservacionController::class, 'show']);
