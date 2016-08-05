<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateComentariosTicketsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios_tickets', function(Blueprint $table) {
            $table->increments('id');
			$table->text('texto');
            $table->integer('user_id');
            $table->integer('ticket_id');
            $table->string('archivo')->nullable();
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
        Schema::drop('comentarios_tickets');
    }
}
