<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('username',40)->nullable()->default(NULL);
            $table->string('name',100)->nullable()->default(NULL);
            $table->string('email',200)->nullable()->default(NULL);
            $table->string('password')->nullable();
            $table->enum('type', [ 'final' , 'comercio', 'mayorista' ])->nullable()->default(NULL);
            
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
        Schema::dropIfExists('clientes');
    }
}
