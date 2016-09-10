<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('cedula');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('email');
            $table->date("nacimiento");
            $table->char("sexo")->default('M');
            $table->string("estadoCivil")->default('soltero');
            $table->string('telefono');
            $table->string('foto')->nullable();
            $table->string('nota'); 
            $table->string('direccion');
            $table->integer("user_id")->nullable();
            $table->integer("puesto_id")->nullable();
            $table->string('cargo')->nullable(); 
            $table->string('departamento')->nullable(); 
            $table->string('punto_id')->nullable(); 
            $table->string('ingreso')->nullable(); 
            $table->string('egreso')->nullable(); 
            $table->integer('eps_id')->nullable(); 
            $table->integer('arl_id')->nullable(); 
            $table->integer('fondosDePensiones_id')->nullable();
            $table->decimal('ibc',20,3);            
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
        Schema::drop('pacientes');
    }
}
