<?php

namespace App\Http\Controllers\Admin\Configuraciones;

use App\Http\Controllers\Controller;
use App\Model\Configuraciones;
use Illuminate\Http\Request;

class ConfiguracionesController extends Controller
{
    public function updateSueldoBase(Request $data){
        $validatedData = $data->validate([
            'sueldo_base' => ['required', 'numeric','min:0'],
        ]);
        if(!Configuraciones::where('id',1)->first()){
            $newConfiguracion = new Configuraciones();
            $newConfiguracion->save();
        }
        $configuracion = Configuraciones::where('id',1)->first();
        $configuracion->sueldo_base = $data->sueldo_base;
        $configuracion->save();
        return back()->with('success', 'EL sueldo base se ha actualizado exitosamente');
    }
}
