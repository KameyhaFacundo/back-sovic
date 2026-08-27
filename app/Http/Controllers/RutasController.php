<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rutas\CreateRutaRequest;
use App\Http\Requests\Rutas\FilterRequest;
use App\Http\Requests\Rutas\UpdateRutaRequest;
use App\Http\Resources\RutaResource;
use App\Models\Ruta;

class RutasController extends Controller
{
    public function filter(FilterRequest $request)
    {
        $query = Ruta::query();

        if ($request->filled('filtro')) {
            $filtro = $request->input('filtro');
            $query->where('descripcion', 'like', '%' . $filtro . '%');
        }

        $query->orderBy('descripcion');

        $resultados = $query->paginate($request->cantidad, ['*'], 'page', $request->pagina);

        return response()->json([
            'data' => RutaResource::collection($resultados->items()),
            'current_page' => $resultados->currentPage(),
            'total_pages' => $resultados->lastPage(),
            'total_registros' => $resultados->total(),
        ], 200);
    }

    public function show($id)
    {
        $ruta = Ruta::findOrFail($id);

        return response()->json(new RutaResource($ruta));
    }

    public function store(CreateRutaRequest $request)
    {
        $ruta = Ruta::create($request->validated());

        return response()->json([
            'message' => 'Ruta creada con éxito',
            'data' => new RutaResource($ruta),
        ], 201);
    }

    public function update(UpdateRutaRequest $request, $id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->update($request->validated());

        return response()->json([
            'message' => 'Ruta actualizada con éxito',
            'data' => new RutaResource($ruta),
        ]);
    }

    public function destroy($id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->delete();

        return response()->json([
            'message' => 'Ruta eliminada correctamente',
        ]);
    }
}
