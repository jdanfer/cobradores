<?php

use App\Http\Controllers\MapaController;
use App\Http\Livewire\CobranzaResumen;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Visualizar;
use Illuminate\Support\Facades\Auth;

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

Auth::routes(['register' => false]);
///Auth::routes();
Route::get('/test-cfe', [App\Http\Controllers\CfeController::class, 'enviarCFE']);
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/registros', Visualizar::class)->name('visualizar.index');

    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::get('admin/settings', ['as' => 'admin.settings', 'uses' => 'App\Http\Controllers\RegistraUsuario@showPerfil']);
    Route::post('admin/settings', ['as' => 'admin.settings', 'uses' => 'App\Http\Controllers\RegistraUsuario@editPerfil']);
    Route::get('profile', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

    //Usuarios
    Route::get('usuarios', ['as' => 'usuarios', 'uses' => 'App\Http\Controllers\RegistraUsuario@show']);
    Route::get('usuarios/editar', ['as' => 'usuarios/editar', 'uses' => 'App\Http\Controllers\RegistraUsuario@showUsuarioEdit']);
    Route::post('usuarios/editar', ['as' => 'usuarios/editar', 'uses' => 'App\Http\Controllers\RegistraUsuario@editUsuario']);
    Route::get('usuarios/eliminar', ['as' => 'usuarios/eliminar', 'uses' => 'App\Http\Controllers\RegistraUsuario@showDelete']);
    Route::post('usuarios/eliminar', ['as' => 'usuarios/eliminar', 'uses' => 'App\Http\Controllers\RegistraUsuario@showDeleteUpdate']);
    Route::get('registrar', ['as' => 'registrar', 'uses' => 'App\Http\Controllers\RegistraUsuario@showUsuarioCreate']);
    Route::post('registrar', ['as' => 'registrar', 'uses' => 'App\Http\Controllers\RegistraUsuario@createUsuario']);
    Route::post('usuarios/eliminar/borrar', ['as' => 'usuarios/eliminar/borrar', 'uses' => 'App\Http\Controllers\RegistraUsuario@showDeleteUpdateBorrar']);

    Route::get('admin/cobranzas', ['as' => 'admin/cobranzas', 'uses' => 'App\Http\Controllers\AdminController@showCobranzaR']);
    Route::get('admin/informescobrador', ['as' => 'admin/informescobrador', 'uses' => 'App\Http\Controllers\AdminController@showInformesCob']);
    Route::get('admin/entregas', ['as' => 'admin/entregas', 'uses' => 'App\Http\Controllers\AdminController@showEntregas']);
    Route::get('admin/validaciones', ['as' => 'admin/validaciones', 'uses' => 'App\Http\Controllers\AdminController@showValidaradm']);
    Route::get('admin/auditoria', ['as' => 'admin/auditoria', 'uses' => 'App\Http\Controllers\AdminController@showAuditoria']);
    Route::get('admin/mutuales', ['as' => 'admin/mutuales', 'uses' => 'App\Http\Controllers\AdminController@showMutuales']);
    Route::get('admin/comisiones', ['as' => 'admin/comisiones', 'uses' => 'App\Http\Controllers\AdminController@showComisiones']);
});
