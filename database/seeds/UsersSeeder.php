<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder {    
 /**      * Run the database seeds. * * @return void      */    
  public function run()    
  { 
  	App\User::create(['nombre' => 'Gabriel Bejarano', 'email' => 'SeeDGabo@gmail.com','password' =>	Hash::make('gab23gab'), 'admin' => 1,
  		'categorias_id' => [1,2,3,4,5,6]]); 
  }
}
