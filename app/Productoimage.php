<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productoimage extends Model
{
    protected $table = "productoimage";

    protected $fillable = [
        "order",
        "image",
        "producto_id"
    ];
    protected $casts = [
        'image' => 'array',
    ];
}
