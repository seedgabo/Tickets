<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function(Blueprint $table) {
            $table->increments('id');
			$table->string('titulo');
			$table->text('contenido');
            $table->string('user_id');
            $table->string('guardian_id')->nullable();
            $table->string('mail_alert_24')->nullable();
            $table->string('mail_alert_3')->nullable();
            $table->string('mail_alert_vencido')->nullable();
			$table->string('estado')->default('abierto');
			$table->string('categoria_id');
			$table->string('archivo')->nullable();
            $table->boolean("transferible")->default(true);
            $table->boolean("encriptado")->default(false);
            $table->string("clave")->nullable();
            $table->dateTime('vencimiento')->nullable();
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
        Schema::drop('tickets');
    }
}
