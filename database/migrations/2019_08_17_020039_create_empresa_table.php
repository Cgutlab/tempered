<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id');

            $table->json('email')->nullable()->default(NULL);
            $table->json('telefono')->nullable()->default(NULL);
            $table->json('domicilio')->nullable()->default(NULL);
            $table->json('redes')->nullable()->default(NULL);
            $table->json('images')->nullable()->default(NULL);
            $table->json('metadatos')->nullable()->default(NULL);
            $table->json('form')->nullable()->default(NULL);
            $table->json('secciones')->nullable()->default(NULL);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa');
    }
}
