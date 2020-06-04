<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductorelacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productorelacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('producto')->nullable()->default(NULL);
            $table->unsignedBigInteger('producto_id')->nullable()->default(NULL);

            $table->foreign('producto')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
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
        Schema::dropIfExists('productorelacion');
    }
}
