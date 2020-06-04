<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        "order",
        "name",
        "url",
        "code",
        "charact",
        "file",
        "categoria_id",
        "is_destacado"
    ];
    protected $casts = [
        'file' => 'array',
    ];
    
    public function categoria() {
        return $this->belongsTo('App\Categoria');
    }
    
    public function images() {
        return $this->hasMany('App\Productoimage');
    }
}
