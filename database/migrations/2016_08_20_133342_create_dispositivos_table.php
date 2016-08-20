<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDispositivosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispositivos', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('token')->unique();
            $table->string('dispositivo')->nullable();
            $table->string('plataforma')->default('android');
            $table->integer('user_id')->nullable();
            $table->boolean('activo')->default(1);
            // $table->boolean('noticias_enabled')->default(1);
            // $table->boolean('eventos_enabled')->default(1);
            // $table->boolean('mensajes_enabled')->default(1);
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
        Schema::drop('dispositivos');
    }

}
