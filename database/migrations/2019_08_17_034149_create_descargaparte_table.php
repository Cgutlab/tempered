<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescargaparteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descargaparte', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order',3)->nullable()->default(NULL);
            $table->string('name',70)->nullable()->default(NULL);
            $table->string('file',100)->nullable()->default(NULL);
            $table->string('ext',4)->nullable()->default(NULL);
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
        Schema::dropIfExists('descargaparte');
    }
}
