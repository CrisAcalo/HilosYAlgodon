<?php

namespace App\Http\Controllers\Admin\Materiales;

use App\Http\Controllers\Controller;
use App\Model\Materiales;
use Illuminate\Http\Request;
use Exception;

class MaterialesController extends Controller
{
    public function index()
    {
        $materiales = Materiales::all();
        return view('admin.materiales.materialesManager', compact('materiales'));
    }

    public function create(Request $newMaterialData)
    {
        $validatedData = $newMaterialData->validate([
            'nombre' => 'required|unique:materiales|max:255',
            'ud_medida' => 'required',
            'costo_por_ud_medida' => 'required|numeric'
        ]);

        $newMaterial = new Materiales();
        try {

            $newMaterial->nombre = $newMaterialData->nombre;
            $newMaterial->ud_medida = $newMaterialData->ud_medida;
            $newMaterial->costo_ud_medida = $newMaterialData->costo_por_ud_medida;
            $newMaterial->save();

            return back()->with(['success' => 'El material se registró con éxito']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit(Request $newData, $materialID)
    {

        $material = Materiales::where('id', decrypt($materialID))->first();
        if ($material->nombre != $newData->nombre) {
            $validatedData = $newData->validate([
                'nombre' => 'required|unique:materiales|max:255',
            ]);
        }
        $validatedData = $newData->validate([
            'ud_medida' => 'required',
            'costo_por_ud_medida' => 'required|numeric'
        ]);

        try {

            $material->nombre = $newData->nombre;
            $material->ud_medida = $newData->ud_medida;
            $material->costo_ud_medida = $newData->costo_por_ud_medida;
            $material->save();

            return back()->with(['success' => 'El material se actualizó con éxito']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($materialID)
    {

        try {
            $material = Materiales::where('id', decrypt($materialID))->first();
            $material->delete();

            return back()->with(['success' => 'El material se eliminó con éxito']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
