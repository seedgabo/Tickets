<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alertas', function(Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('mensaje');
            $table->text('correo');
            $table->datetime("programado");
            $table->boolean("con_correo")->default("1");
            $table->boolean("emitido")->default('0');
            $table->string("usuarios");
            $table->integer("user_id");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('alertas');
    }
}
