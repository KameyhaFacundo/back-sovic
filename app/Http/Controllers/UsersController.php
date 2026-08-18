<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\FilterRequest;
use App\Http\Requests\Users\ImpersonarUsuarioRequest;
use App\Http\Requests\Users\LoginRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Models\RegistroCajaExchange;
use App\Models\TipoUsuario;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function create(CreateUserRequest $request)
    {
        $tipoUsuario = TipoUsuario::findOrFail($request->tipo_usuarios);
        $identificador = str_replace(' ', '', $request->nombre_completo) . "_" . uniqid() . '_' . $tipoUsuario->codigo;

        $user = User::create([
            'id_rol' => !is_null($request->id_rol) ? $request->id_rol : null,
            'identificador' => $identificador,
            'nombre_completo' => $request->nombre_completo,
            'email' => $request->email,
            'password' => $request->password,
            'tipo_usuarios' => $request->tipo_usuarios,
        ]);

        DB::table('usuarios_sucursal')->insert([
            'id_usuario' => $user->id,
            'id_sucursal' => $request->id_sucursal_comercio
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json([
            'user' => $user,
            'token' => $token // Devuelve el token si es necesario
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        // Obtener las credenciales del request
        $credentials = $request->only('email', 'password');

        try {
            // Intentar autenticar al usuario
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciales no válidas'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'No se pudo crear el token'
            ], 500);
        }

        // Obtener el usuario asociado al email proporcionado
        $user = User::where('email', $request->email)->with(['tipoUsuario', 'sucursales', 'permisosUsuarios'])->firstOrFail();

        // Preparar la respuesta
        $response = [
            'token' => $this->respondWithToken($token),
            'user' => $user

        ];

        return response()->json($response);
    }

    public function filter(FilterRequest $request)
    {
        // Obtener el valor de búsqueda
        $query = User::with(['tipoUsuario', 'sucursales']);

        if ($request->has('filtro')) {
            $filtro = $request->input('filtro');
            $query->where(function ($query) use ($filtro) {
                $query->where('identificador', 'like', '%' . $filtro . '%')
                    ->orWhere('nombre_completo', 'like', '%' . $filtro . '%')
                    ->orWhere('email', 'like', '%' . $filtro . '%');
            });
        }

        if ($request->has('is_deleted')) {
            $isDeleted = $request->input('is_deleted', false);
            if ($isDeleted) {
                $query->withTrashed()->whereNotNull('deleted_at');
            }
        }

        if ($request->boolean('is_gestor_prendas')) {
            $query->whereHas('permisos', function ($q) {
                $q->where('codigo', 'gestor-prendas');
            });
        }

        $resultados = $query->paginate($request->cantidad, ['*'], 'page', $request->pagina);

        $data = collect($resultados->items())->map(function ($user) {
            return [
                ...$user->toArray(),
                'registro_caja_actual_exchange' => $user->registro_caja_actual_exchange
            ];
        });

        return response()->json([
            'data' => $data,
            'current_page' => $resultados->currentPage(),
            'total_pages' => $resultados->lastPage(),
            'total_registros' => $resultados->total()
        ], 200);
    }

    public function usuariosConCajaActualExchange(Request $request)
    {

        $cajaTesoreriaExiste = RegistroCajaExchange::where('es_tesoreria', 'T')
            ->whereNull('cierre_caja_at')
            ->exists();

        $query = User::where(function ($q) use ($cajaTesoreriaExiste) {

            // Usuarios normales con caja abierta
            $q->whereHas('registroCajaExchange', function ($sub) {
                $sub->whereNull('cierre_caja_at');
            });

            // Usuarios tesorería (solo si existe caja compartida)
            if ($cajaTesoreriaExiste) {
                $q->orWhere('tipo_usuario_exchange', 'T');
            }
        })
            ->with(['tipoUsuario', 'sucursales']);



        if ($request->has('pagina') && $request->has('cantidad')) {
            $pagina = $request->input('pagina');
            $cantidad = $request->input('cantidad');
            $resultados = $query->paginate($cantidad, ['*'], 'page', $pagina);
            $data = collect($resultados->items())->map(function ($user) {
                return [
                    ...$user->toArray(),
                    'registro_caja_actual_exchange' => $user->registro_caja_actual_exchange
                ];
            });
            return response()->json([
                'data' => $data,
                'current_page' => $resultados->currentPage(),
                'total_pages' => $resultados->lastPage(),
                'total_registros' => $resultados->total()
            ], 200);
        } else {
            $usuarios = $query->get();
            $data = collect($usuarios)->map(function ($user) {
                return [
                    ...$user->toArray(),
                    'registro_caja_actual_exchange' => $user->registro_caja_actual_exchange
                ];
            });
            return response()->json([
                'data' => $data,
                'current_page' => 1,
                'total_pages' => 1,
                'total_registros' => $data->count()
            ], 200);
        }
    }

    protected function respondWithToken($token)
    {
        $ttl = config('jwt.ttl');
        $expirationTime = now()->addMinutes($ttl);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl,
            'expires_at' => $expirationTime->toISOString(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (isset($data['nombre_completo'])) {
            $user->nombre_completo = $data['nombre_completo'];
        }
        if (isset($data['id_rol'])) {
            $user->id_rol = $data['id_rol'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['tipo_usuarios'])) {
            $user->tipo_usuarios = $data['tipo_usuarios'];
        }
        if (isset($data['tipo_usuario_exchange'])) {
            $user->tipo_usuario_exchange = $data['tipo_usuario_exchange'];
        }

        $user->save();

        if (isset($data['id_sucursal_comercio'])) {
            DB::table('usuarios_sucursal')->updateOrInsert(
                ['id_usuario' => $user->id],
                ['id_sucursal' => $data['id_sucursal_comercio']]
            );
        } else {
            DB::table('usuarios_sucursal')->where('id_usuario', $user->id)->delete();
        }

        $user->load(['tipoUsuario', 'sucursales']);

        return response()->json([
            'message' => 'Usuario actualizado con éxito',
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente'
        ]);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if (!$user->trashed()) {
            return response()->json([
                'message' => 'El usuario no está eliminado.'
            ], 400);
        }

        $user->restore();

        return response()->json([
            'message' => 'Usuario restaurado correctamente.',
            'user' => $user
        ]);
    }

    public function cambiarContraseña(ChangePasswordRequest $request)
    {
        $data = $request->validated();

        $user = User::findOrFail($data['id_usuario']);

        $user->password = Hash::make($data['password']);
        $user->save();

        return response()->json([
            'message' => 'Contraseña actualizada con éxito',
            'user' => $user->only(['id', 'nombre_completo', 'email', 'identificador'])
        ]);
    }

    public function impersonar(ImpersonarUsuarioRequest $request)
    {
        $validated = $request->validated();
        $user = User::find($validated['id_usuario']);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Impersonación exitosa',
            'token' => $token,
            'user' => $user->load(['tipoUsuario', 'sucursales', 'permisosUsuarios']),
        ]);
    }
}
