<?php

namespace App\Http\Controllers;

use App\Factories\RolFactory;
use App\Http\Requests\Roles\CreateRolesRequest;
use App\Http\Requests\Roles\UpdateRolesRequest;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{

    public function index()
    {
        $roles = Rol::all();

        return response()->json([
            'data' => $roles,
            'current_page' => 1,
            'total_pages' => 1,
            'total_registros' => count($roles)
        ], 200);
    }

    public function show($id)
    {
        $rol = Rol::with('permisos')->find($id);

        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado.'], 404);
        }

        return response()->json($rol, 200);
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

            return response()->json($rol->load('permisos'), 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el rol.', 'details' => $th->getMessage()], 500);
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

        return response()->json($rolFactory->load('permisos'), 200);
    } catch (\Throwable $th) {
        DB::rollBack();
        return response()->json([
            'error'   => 'Error al actualizar el rol.',
            'details' => $th->getMessage(),
        ], 500);
    }
}

    public function delete($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            $rol->delete();

            return response()->json('Rol eliminado correctamente', 204);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al eliminar el rol.', 'details' => $th->getMessage()], 500);
        }
    }
}
