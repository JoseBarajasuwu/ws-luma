<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RifaController;

//******************************************************//
Route::POST('/login', [LoginController::class, 'login']);
//******************************************************//

//**************************************************************************************//
Route::POST('/estudio/mostrar-boletos', [RifaController::class,'MostrarBoletos']);
Route::POST('/estudio/guardar-boleto', [RifaController::class,'GuardarBoletos']);
Route::POST('/estudio/editar-boleto', [RifaController::class,'EditarBoleto']);
Route::POST('/estudio/no-ganador-boleto', [RifaController::class,'NoGanadorBoleto']);
//**************************************************************************************//