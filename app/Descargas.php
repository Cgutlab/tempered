<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descargas extends Model
{
    protected $table = "descargaparte";

    protected $fillable = [
        "order",
        "name",
        "file",
        "ext",
        "elim",
        "descarga_id"
    ];
    protected $casts = [
        'file' => 'array',
    ];
}
