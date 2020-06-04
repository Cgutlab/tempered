<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $table = "categorias";

    protected $fillable = [
        "order",
        "name",
        "url",
        "image",
        "elim"
    ];
    protected $casts = [
        'image' => 'array',
    ];
    
    public function categorias() {
        return $this->hasMany('App\Categoria','categoria_id');
    }
}
