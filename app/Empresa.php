<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = "empresa";
    protected $fillable = [
        'email',
        'telefono',
        'domicilio',
        'redes',
        'images',
        'metadatos',
        'form',
        'secciones',
    ];
    
    protected $casts = [
        'email' => 'array',
        'telefono' => 'array',
        'domicilio' => 'array',
        'redes' => 'array',
        'images' => 'array',
        'metadatos' => 'array',
        'form' => 'array',
        'secciones' => 'array',
    ];
}
