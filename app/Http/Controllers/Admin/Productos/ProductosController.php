<?php

namespace App\Http\Controllers\Admin\Productos;

use App\Http\Controllers\Controller;
use App\Model\Materiales;
use Illuminate\Http\Request;
use Exception;
use App\Model\Productos;
use App\Model\MaterialesPorProducto;
use App\Model\Configuraciones;

class ProductosController extends Controller
{
    public function index()
    {
        $productos = Productos::all();
        $configuraciones = Configuraciones::where('id', 1)->first();
        return view('admin.productos.ProductosManager', compact('productos','configuraciones'));
    }

    public function create(Request $newProductoData)
    {
        $validatedData = $newProductoData->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:productos'],
            'descripcion' => ['required', 'string'],
            'costo_unitario' => ['numeric'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'horas_trabajo' => ['required', 'numeric', 'min:1'],
        ]);

        $newProducto = new Productos();

        try {
            $newProducto->nombre = $newProductoData->nombre;
            $newProducto->descripcion = $newProductoData->descripcion;
            $newProducto->costo_unitario = $newProductoData->costo_unitario;
            $newProducto->cantidad = $newProductoData->cantidad;
            $newProducto->horas_trabajo = $newProductoData->horas_trabajo;
            $newProducto->save();
            $this->calculateCost($newProducto->id);

            return back()->with(['success' => 'El Producto se registró con éxito']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function details($productID)
    {
        $producto = Productos::where('id', decrypt($productID))->first();
        $materiales = Materiales::all();
        $idsMaterialesAsignados = MaterialesPorProducto::where('producto_id', decrypt($productID))->pluck('material_id')->toArray();
        $materialesAsignados = MaterialesPorProducto::where('producto_id', decrypt($productID))->get();
        $configuraciones = Configuraciones::where('id', 1)->first();
        return view('admin.productos.productoDetail', compact('producto', 'materiales', 'idsMaterialesAsignados', 'materialesAsignados','configuraciones'));
    }

    public function editAsignacionMateriales(Request $newMateriales, $productID)
    {
        if ($newMateriales && $productID) {
            try {
                $productID = decrypt($productID);
                $materialesToAssign = $newMateriales->except(['_token', '_method', 'asignarMateriales_length']);
                $nuevosMaterialesIds = [];
                $idsMaterialesAsignados = MaterialesPorProducto::where('producto_id', $productID)->pluck('material_id')->toArray();

                foreach ($materialesToAssign as $checkbox => $value) {
                    $parts = explode('_', $value);
                    $materialId = $parts[1];
                    $nuevosMaterialesIds[] = $materialId;
                    if (!in_array($materialId, $idsMaterialesAsignados)) {
                        $newAssignation = new MaterialesPorProducto();
                        $newAssignation->producto_id = $productID;
                        $newAssignation->material_id = $materialId;
                        $newAssignation->save();
                    }
                }

                foreach ($idsMaterialesAsignados as $id) {
                    if (!in_array($id, $nuevosMaterialesIds)) {
                        MaterialesPorProducto::where('producto_id', $productID)->where('material_id', $id)->delete();
                    }
                }
                $this->calculateCost($productID);
                return back()->with(['success' => 'Los materiales se asignaron con éxito']);
            } catch (Exception $e) {
                return back()->with(['danger' => 'Ocurrió un error: ' . $e]);
            }
        } else {
            return back()->with(['danger' => 'Ocurrió un error']);
        }
    }

    public function editCantidadMateriales(Request $request, $productID)
    {
        if ($request && $productID) {
            try {
                $productID = decrypt($productID);
                $materialesToAssign = $request->except(['_token', '_method']);

                foreach ($materialesToAssign as $idMaterial => $cantidad) {
                    $assignationToModify = MaterialesPorProducto::where('id', $idMaterial)->first();
                    $assignationToModify->cantidad = $cantidad;
                    $assignationToModify->save();
                }
                $this->calculateCost($productID);

                return back()->with(['success' => 'Los materiales se asignaron con éxito']);
            } catch (Exception $e) {
                return back()->with(['danger' => 'Ocurrió un error: ' . $e->getMessage()]);
            }
        } else {
            return back()->with(['danger' => 'Ocurrió un error']);
        }
    }

    public function edit(Request $newData, $productID)
    {
        $productID = decrypt($productID);
        $producto = Productos::where('id', $productID)->first();
        if ($producto->nombre != $newData->nombre) {
            $validatedData = $newData->validate([
                'nombre' => ['required', 'string', 'max:255', 'unique:productos'],
            ]);
        }
        $validatedData = $newData->validate([
            'descripcion' => ['required', 'string'],
            'costo_unitario' => ['numeric'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'horas_trabajo' => ['required', 'numeric', 'min:0'],
        ]);

        try {

            $producto->nombre = $newData->nombre;
            $producto->descripcion = $newData->descripcion;
            $producto->costo_unitario = $newData->costo_unitario;
            $producto->cantidad = $newData->cantidad;
            $producto->horas_trabajo = $newData->horas_trabajo;
            $producto->save();
            $this->calculateCost($productID);

            return back()->with(['success' => 'El Producto se actualizó con éxito']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($ProductoID)
    {

        try {
            $Producto = Productos::where('id', decrypt($ProductoID))->first();
            $Producto->delete();

            return back()->with(['success' => 'El Producto se eliminó con éxito']);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function calculateCost($productID)
    {
        if (!Configuraciones::where('id', 1)->first()) {
            $newConfiguracion = new Configuraciones();
            $newConfiguracion->save();
        }
        $configuracion = Configuraciones::where('id', 1)->first();
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
