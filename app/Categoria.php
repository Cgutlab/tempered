<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        "order",
        "name",
        "url",
        "image",
        "categoria_id",
        "elim"
    ];
    protected $casts = [
        'image' => 'array',
    ];
    
    public function familia() {
        return $this->belongsTo('App\Familia','categoria_id');
    }
    
    public function productos() {
        return $this->hasMany('App\Producto');
    }
}
