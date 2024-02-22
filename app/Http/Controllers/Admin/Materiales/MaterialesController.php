<?php

namespace App\Http\Controllers\Admin\Materiales;

use App\Http\Controllers\Controller;
use App\Model\Materiales;
use App\Model\MaterialesPorProducto;
use App\Model\Productos;
use Illuminate\Http\Request;
use App\Model\Configuraciones;
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
            'cantidad' => 'required|numeric|min:0.01',
            'costo_total' => 'required|numeric|min:0.01'
        ]);

        $newMaterial = new Materiales();
        try {
            $newMaterial->nombre = $newMaterialData->nombre;
            $newMaterial->ud_medida = $newMaterialData->ud_medida;
            $newMaterial->cantidad = $newMaterialData->cantidad;
            $newMaterial->costo_total = $newMaterialData->costo_total;
            $newMaterial->costo_ud_medida = $newMaterialData->costo_total / $newMaterialData->cantidad;
            $newMaterial->save();

            return back()->with(['success' => 'El material se registró con éxito']);
        } catch (Exception $e) {
            return back()->with(['danger' => $e->getMessage()]);
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
            'cantidad' => 'required|numeric|min:0.01',
            'costo_total' => 'required|numeric|min:0.01'
        ]);

        try {
            $material->nombre = $newData->nombre;
            $material->ud_medida = $newData->ud_medida;
            $material->cantidad = $newData->cantidad;
            $material->costo_total = $newData->costo_total;
            $material->costo_ud_medida = $newData->costo_total / $newData->cantidad;
            $material->save();

            $idsProductosAsignados = MaterialesPorProducto::where('material_id', decrypt($materialID))->pluck('producto_id')->unique()->toArray();
            foreach ($idsProductosAsignados as $productId) {
                $this->calculateCost($productId);
            }

            return back()->with(['success' => 'El material se actualizó con éxito']);
        } catch (Exception $e) {
            return back()->with(['danger' => $e->getMessage()]);
            return $e->getMessage();
        }
    }

    public function destroy($materialID)
    {
        try {
            $material = Materiales::where('id', decrypt($materialID))->first();
            $material->delete();

            $idsProductosAsignados = MaterialesPorProducto::where('material_id', decrypt($materialID))->pluck('producto_id')->unique()->toArray();
            foreach ($idsProductosAsignados as $productId) {
                $this->calculateCost($productId);
            }

            return back()->with(['success' => 'El material se eliminó con éxito']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function calculateCost($productID)
    {
        if(!Configuraciones::where('id',1)->first()){
            $newConfiguracion = new Configuraciones();
            $newConfiguracion->save();
        }
        $configuracion = Configuraciones::where('id',1)->first();
        $valor_por_hora = $configuracion->sueldo_base / 30 / 8;
        $total = 0;
        $product = Productos::where('id', $productID)->first();

        $asignaciones = MaterialesPorProducto::where('producto_id', $productID)->get();
        foreach ($asignaciones as $asignacion) {
            $material = Materiales::where('id', $asignacion->material_id)->first();
            $costoPorUnidadDeMedida = $material->costo_ud_medida;
            $cantidadMaterial = $asignacion->cantidad;
            $total += $costoPorUnidadDeMedida * $cantidadMaterial;
        }
        $horasDeTrabajo = $product->horas_trabajo;
        $total += $horasDeTrabajo * $valor_por_hora;
        $product->costo_unitario = $total;
        $product->save();
    }
}
