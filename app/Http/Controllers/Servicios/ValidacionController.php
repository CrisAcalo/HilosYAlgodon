<?php

namespace App\Http\Controllers\Servicios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ValidacionController extends Controller
{
    public function valcedula ($cedula){
       
            if (is_numeric($cedula)) {
                $resul[] = null;
                $matriz = str_split($cedula);
                if(strlen($cedula) == 10){
                    $moduloV = 10;
                    $digito = 9;
                    $valve =[2,1,2,1,2,1,2,1,2];
                }elseif(strlen($cedula) == 13){
                    if ($matriz[12] != 0) {
                        $moduloV = 11;
                        if ($matriz[2] == 9) {
                            $digito = 9;
                            $valve =[4,3,2,7,6,5,4,3,2];
                        }elseif ($matriz[2] == 6) {
                            $digito = 8;
                            $valve =[3,2,7,6,5,4,3,2];
                        }elseif ($matriz[2] < 6) {
                            $moduloV = 10;
                            $digito = 9;
                            $valve =[2,1,2,1,2,1,2,1,2];
                        }else{
                            return false;
                        }
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
                    for ($i=0; $i < $digito ; $i++) { 
                        $resul[$i] = $matriz[$i]*$valve[$i];
                        if ($resul[$i]>9&&$matriz[2] < 6) {
                            $resul[$i] = $resul[$i]-9;
                        }
                    }
                    $suma = array_sum($resul);
                    $modulo = fmod($suma , $moduloV);
                    if ($modulo!=0) {
                        $resultado = $moduloV - intval($modulo);
                        if ($resultado == $matriz[$digito]) {
                            return true;
                        }else {
                            return false;
                        }
                    }else{
                        if ($modulo == $matriz[9]) {
                            return true;
                        }else {
                            return false;
                        }
                    }
            }else{
                return false;
            }   
    }

    public function valcorreo($correo){
        if(filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        else {
            return false;
        }
    }

    
    public function valcelular($celular){
        
        if($celular[0] == 0){
            if($celular[1] == 9){
                return true;
            }
            
        }
        return false;
    }
}
