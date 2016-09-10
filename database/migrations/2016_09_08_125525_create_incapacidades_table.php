<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incapacidades', function(Blueprint $table) {
            $table->increments('id');
            $table->string('paciente_id')->nullable();
            $table->string('caso_id')->nullable();
            $table->datetime('fecha_ingreso')->nullable();
            $table->datetime('fecha_incapacidad')->nullable();
            $table->datetime('fecha_liquidacion')->nullable();
            $table->string('entidad')->default('particular');  // EPC , ARL , Particular
            $table->integer('eps_id')->nullable();
            $table->integer('dias_incapacidad')->default(0);
            $table->string('prorroga')->default('no'); // si, no
            $table->string('origen')->default('laboral'); /// comun o laboral
            $table->integer('cie10_id');
            $table->string("sistema_afectado")->nullable();
            $table->string("caso_especial")->nullable();
            $table->integer('medico_id');
            $table->string('estado')->default('por revisión');  // abierto estado por revision 
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
        Schema::drop('incapacidades');
    }
}
