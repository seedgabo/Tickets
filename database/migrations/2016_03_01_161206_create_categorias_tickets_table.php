<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriasTicketsTable extends Migration
{


    public function up()
    {
        Schema::create('categorias_tickets', function(Blueprint $table) {
            $table->increments('id');
			$table->string('nombre');
			$table->text('descripción')->nullable();
            $table->integer('parent_id');
			$table->timestamps();
			$table->softDeletes();
        });
    }


    public function down()
    {
        Schema::drop('categorias_tickets');
    }
}
