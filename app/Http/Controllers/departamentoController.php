<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use \App\Models\Departamento;

class departamentoController extends Controller
{
    //

    public function index()
    {

        return Inertia::render('Departamento/DepartamentoIndex', [
            'departamentos' => Departamento::with('area')->get(),
        ]);
    }

    function byArea($area_id)
    {
        return response()->json(['departamentos' => Departamento::where('area_id', $area_id)->get()]);
    }
    function findAll(Request $request)
    {
        $query = Departamento::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);

        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');
        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('departamentos.id', 'like', '%' . $filter . '%')
                    ->orWhere('departamentos.nombre', 'like', '%' . $filter . '%')
                    ->orWhere('departamentos.descripcion', 'like', '%' . $filter . '%')
                    ->orWhereHas('area', function ($q) use ($filter) {
                        $q->where('areas.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('areas.descripcion', 'like', '%' . $filter . '%');
                    });
            });
        }

        if (in_array($sortField, ['id', 'nombre', 'descripcion', 'area.nombre'])) {
            if (strpos($sortField, 'area.') === 0) {
                $query->join('areas', 'departamentos.area_id', '=', 'areas.id')
                    ->orderBy('areas.' . substr($sortField, 5), $sortOrder);
            } else {
                $query->orderBy($sortField, $sortOrder);
            }
        }

        $departamentos = $query->with('area')->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($departamentos);
    }

    function create()
    {
        return Inertia::render('Departamento/DepartamentoCreate');
    }

    function store(Request $request)
    {
        Departamento::create($request->only('nombre', 'area_id', 'descripcion'));
        return redirect()->route('departamento.index');
    }

    function edit($id)
    {
        return Inertia::render('Departamento/DepartamentoEdit', [
            'departamento' => Departamento::find($id),
        ]);
    }

    function update(Request $request, $id)
    {
        $departamento = Departamento::find($id);
        $departamento->update($request->only('nombre', 'area_id', 'descripcion'));
        return redirect()->route('departamento.index');
    }

    function destroy($id)
    {
        $departamento = Departamento::find($id);
        $departamento->delete();
        return response()->json(['departamentos' => Departamento::with('area')->get(),]);
    }
}
