<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descargas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order',3)->nullable()->default(NULL);
            $table->string('name',70)->nullable()->default(NULL);
            $table->string('cover',100)->nullable()->default(NULL);
            $table->enum('type', ['público', 'privado']);
            $table->enum('category', ['folletos', 'manual de instalación','órdenes de fabricación']);
            $table->boolean('elim')->default(false);
            
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
        Schema::dropIfExists('descargas');
    }
}
