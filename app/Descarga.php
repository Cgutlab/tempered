<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descarga extends Model
{
    protected $fillable = [
        "order",
        "name",
        "url",
        "cover",
        "type",
        "category",
        "elim",
        "section"
    ];
    protected $casts = [
        'cover' => 'array',
    ];
    
    public function partes() {
        return $this->hasMany('App\Descargas');
    }
}
