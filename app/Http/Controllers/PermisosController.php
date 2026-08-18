<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permisos\AgregarPermisoRequest;
use App\Models\Permiso;
use App\Models\PermisoUsuario;
use App\Models\RolPermiso;
use Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\TipoUsuario;
use App\Models\User;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string  $tipoUsuario
     * @return \Illuminate\Http\Response
     */
    public function index($tipoUsuario = null)
    {
        $permisos = Permiso::all();

        return response()->json($permisos);
    }

    public function indexAgrupados()
    {
        $permisos = Permiso::all()->groupBy('grupo');

        return response()->json($permisos);
    }

    public function PermisosUsuario(User $usuario)
    {
        try {
            $permisosUsuario = PermisoUsuario::where('id_usuario', operator: $usuario->id)->with('permiso')->get();
            return response()->json($permisosUsuario);
        } catch (\Throwable $th) {
            // Manejar la excepción
            return response()->json(['error' => 'Error al obtener los permisos.', 'details' => $th->getMessage()], 500);
        }
    }

    public function misPermisos()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tipoUsuario = TipoUsuario::find($user->tipo_usuarios);

            $permisosUsuario = PermisoUsuario::where('id_usuario', $user->id)->with('permiso')->get();
            $permisosRol = RolPermiso::where('id_rol', $user->id_rol)->with('permiso')->get();
            $permisos = $permisosUsuario->concat($permisosRol);
            $permisos = $permisos->unique('id_permiso')->values();

            return response()->json($permisos);
        } catch (\Throwable $th) {
            // Manejar la excepción
            return response()->json(['error' => 'Error al obtener los permisos.', 'details' => $th->getMessage()], 500);
        }
    }

    public function AgregarPermiso(AgregarPermisoRequest $request, $id_usuario)
    {
        $usuario = User::find($id_usuario);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        }

        // Extraer permisos del request (array de IDs)
        $id_permisos = $request->input('id_permiso', []);

        // Validar que todos los permisos existan
        $permisosValidos = Permiso::whereIn('id', $id_permisos)->get(['id'])->pluck('id')->all();

        if (count($permisosValidos) !== count($id_permisos)) {
            return response()->json(['error' => 'Uno o más permisos no existen.'], 404);
        }

        $usuario->permisos()->sync($permisosValidos);

        return response()->json(['message' => 'Permisos agregados correctamente al usuario.'], 200);
    }
}
