<?php

namespace App\Http\Controllers;

use App\Factories\RolFactory;
use App\Http\Requests\Roles\CreateRolesRequest;
use App\Http\Requests\Roles\UpdateRolesRequest;
use App\Http\Resources\RolResource;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{

    public function index()
    {
        $roles = Rol::all();

        return response()->json([
            'data' => RolResource::collection($roles),
            'current_page' => 1,
            'total_pages' => 1,
            'total_registros' => $roles->count(),
        ], 200);
    }

    public function show($id)
    {
        $rol = Rol::with('permisos')->find($id);

        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado.'], 404);
        }

        return response()->json(new RolResource($rol), 200);
    }

    public function store(CreateRolesRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $rol = RolFactory::fromRequest($validated);
            $rol->save();
            $permisosIds = [];
            foreach ($validated['permisos'] ?? [] as $permisoId) {
                $permisosIds[] = $permisoId;
            }
            $rol->permisos()->sync($permisosIds);
            DB::commit();

            return response()->json(new RolResource($rol->load('permisos')), 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error al crear el rol.', ['exception' => $th]);
            return response()->json(['error' => 'Error al crear el rol.'], 500);
        }
    }

    public function update(UpdateRolesRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $rol = Rol::findOrFail($id);

            $rolFactory = RolFactory::fromRequest($validated, $rol);
            $rolFactory->save();

            if (array_key_exists('permisos', $validated)) {
                $rolFactory->permisos()->sync($validated['permisos'] ?? []);
            }

            DB::commit();

            return response()->json(new RolResource($rolFactory->load('permisos')), 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error al actualizar el rol.', ['exception' => $th]);
            return response()->json(['error' => 'Error al actualizar el rol.'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            $rol->delete();

            return response()->json(['message' => 'Rol eliminado correctamente'], 200);
        } catch (\Throwable $th) {
            Log::error('Error al eliminar el rol.', ['exception' => $th]);
            return response()->json(['error' => 'Error al eliminar el rol.'], 500);
        }
    }
}
