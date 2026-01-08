<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $Password = $request->input('password');
        $NombreUsuario = $request->input('usuario');
        try {
            $usuarios = DB::select('SELECT
            "UsuarioID"
        FROM
            "Usuario"
        WHERE
            "Contrasenia" = ? 
        AND "NombreUsuario" = ?', [$Password, $NombreUsuario]);
            if (!empty($usuarios)) {
                $data = [
                    "UsuarioID" => 1
                ];
                return json_encode($data);
            } else {
                $data = [
                    "UsuarioID" => 0
                ];
                return json_encode($data);
            }
        } catch (\Throwable $th) {
            return response()->json([], 500);
        }
    }
}
