<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{

    public function login(Request $request)
    {
        // Validar los datos
        $credenciales = $request->validate([
            'numero_checador' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Realizamos una consulta para verificar los campos del usuario
        $user = DB::table('users')
            ->join('roles', 'users.id_rol', '=', 'roles.id')
            ->where('users.numero_checador', $credenciales['numero_checador'])
            ->where('users.password', $credenciales['password'])
            ->select('users.*', 'roles.name as role_name')
            ->first();

        // Si el usuario existe y los campos son correctos se iniciar sesión
            if ($user) {
                Auth::loginUsingId($user->id);
            // Redirigir a la página de inicio después de iniciar sesión
            return response()->json(['codigo' => 1, 'mensaje' => 'Login correcto'], 200);
        }else{
            return response()->json(['codigo' => 0, 'mensaje' => 'Login incorrecto'], 200);
        }
    }
   
}


