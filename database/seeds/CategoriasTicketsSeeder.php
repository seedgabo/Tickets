<?php

use Illuminate\Database\Seeder;

class CategoriasTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\CategoriasTickets::create([
        	'nombre' => 'Soporte', 
        	'descripción'  => 'Soporte',
        	]); 
        \App\Models\CategoriasTickets::create([
        	'nombre' => 'Nomina', 
        	'descripción'  => 'Nomina',
        	]); 
        \App\Models\CategoriasTickets::create([
        	'nombre' => 'Ventas', 
        	'descripción'  => 'Ventas',
        	]); 
        \App\Models\CategoriasTickets::create([
        	'nombre' => 'General', 
        	'descripción'  => 'General',
        	]); 
       	\App\Models\CategoriasTickets::create([
        	'nombre' => 'Planeación', 
        	'descripción'  => 'Planeación',
        	]); 
        \App\Models\CategoriasTickets::create([
            'nombre' => 'Clínica', 
            'descripción'  => 'Deparamento de Salud',
            ]); 
    }
}
