<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MaterialesPorProducto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'materiales_por_producto';

    public function getMaterial($materialId){
        return Materiales::where('id',decrypt($materialId))->first();
    }
}
