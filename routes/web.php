<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebasController;
use App\Http\Controllers\UserController;

// ruta normal que llama la vista welcome
Route::get('/welcome', function () {
    return view('welcome');
});


//rutas de prueba

Route::get('/pruebas/{nombre?}', function ($nombre = null) {
    $texto = '<h2>texto desde una ruta</h2>';
    $texto .= 'Nombre: ' . $nombre;
    
    return view('pruebas', array(
        'texto' => $texto
    ));
});
//ruta que carga desde un controller 
Route::get('/animales',[PruebasController::class, 'animal']);
route:: get('/test-orm',[PruebasController::class, 'testOrm']);
//ruta para cargar desde un controlardor una vista
route:: get('/',[PruebasController:: class,'index']);

/*
rutas de la api

metodos de HTTP comunes

Get: consegir datos o recursos
Post: Guardar datos o recursos o hacer logica desde un formularios
put :l ACTUALIZAR DATOS O RECURSARO
DELETE: eliminar datos o recursos


*/


//rutas de pruebas
route :: get('/usuario/pruebas',[UserController::class,'pruebas']);
route :: get('/categorias/pruebas',[CategoryController::class,'pruebas']);
route :: get('/entrada/pruebas',[PostController::class,'pruebas']);

//rutas del controlador de usuarios
route:: post ('/api/register',[UserController::class, 'register']);
route:: post ('/api/login',[UserController::class, 'login']);
route:: post ('/api/user/update',[UserController::class, 'update']);

