<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class rolesPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("roles")->insert(['name' => 'SuperAdmin']);  //1
        DB::table("roles")->insert(['name' => 'Administrar Clinicas']); // 2
        DB::table("roles")->insert(['name' => 'Administrar Casos']);  // 3
        DB::table("roles")->insert(['name' => 'Administrar Usuarios']); // 4
        DB::table("roles")->insert(['name' => 'Administrar Permisos']); // 5
        DB::table("roles")->insert(['name' => 'Administrar Documentos']); // 6
        DB::table("roles")->insert(['name' => 'Correos Masivos']); // 7
        DB::table("roles")->insert(['name' => 'Administrar Respaldos']); // 8

        DB::table("permissions")->insert(['name' => 'Agregar Casos']);
        DB::table("permissions")->insert(['name' => 'Editar Casos']);
        DB::table("permissions")->insert(['name' => 'Eliminar Casos']);

        DB::table("permissions")->insert(['name' => 'Agregar Usuarios']);
        DB::table("permissions")->insert(['name' => 'Editar Usuarios']);
        DB::table("permissions")->insert(['name' => 'Eliminar Usuarios']);

        DB::table("permissions")->insert(['name' => 'Agregar Documentos']);
        DB::table("permissions")->insert(['name' => 'Editar Documentos']);
        DB::table("permissions")->insert(['name' => 'Eliminar Documentos']);	


        DB::table("permissions")->insert(['name' => 'Agregar Pacientes']);
        DB::table("permissions")->insert(['name' => 'Editar Pacientes']);
        DB::table("permissions")->insert(['name' => 'Eliminar Pacientes']); 


        DB::table("permissions")->insert(['name' => 'Agregar Casos Medicos']);
        DB::table("permissions")->insert(['name' => 'Editar Casos Medicos']);
        DB::table("permissions")->insert(['name' => 'Eliminar Casos Medicos']);


        DB::table("permissions")->insert(['name' => 'Agregar Incapacidades']);
        DB::table("permissions")->insert(['name' => 'Editar Incapacidades']);
        DB::table("permissions")->insert(['name' => 'Eliminar Incapacidades']);            
        
        DB::table("permissions")->insert(['name' => 'Agregar Recomendaciones']);
        DB::table("permissions")->insert(['name' => 'Editar Recomendaciones']);
        DB::table("permissions")->insert(['name' => 'Eliminar Recomendaciones']);     

        DB::table("permissions")->insert(['name' => 'Administar Tablas Medicos']);  

        
        //Medicos
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Agregar Casos Medicos');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Editar Casos Medicos');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Eliminar Casos Medicos');

        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Agregar Pacientes');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Editar Pacientes');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Eliminar Pacientes');

        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Agregar Incapacidades');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Editar Incapacidades');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Eliminar Incapacidades');

        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Agregar Recomendaciones');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Editar Recomendaciones');
        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Eliminar Recomendaciones');

        Backpack\PermissionManager\app\Models\Role::find(2)->GivePermissionTo('Administar Tablas Medicos');



        // Tickets de Soporte
        Backpack\PermissionManager\app\Models\Role::find(3)->GivePermissionTo('Agregar Casos');
        Backpack\PermissionManager\app\Models\Role::find(3)->GivePermissionTo('Editar Casos');
        Backpack\PermissionManager\app\Models\Role::find(3)->GivePermissionTo('Eliminar Casos');



        //Usuarios
        Backpack\PermissionManager\app\Models\Role::find(4)->GivePermissionTo('Agregar Usuarios');
        Backpack\PermissionManager\app\Models\Role::find(4)->GivePermissionTo('Editar Usuarios');
        Backpack\PermissionManager\app\Models\Role::find(4)->GivePermissionTo('Eliminar Usuarios');



        //Documentos
        Backpack\PermissionManager\app\Models\Role::find(6)->GivePermissionTo('Agregar Documentos');
        Backpack\PermissionManager\app\Models\Role::find(6)->GivePermissionTo('Editar Documentos');
        Backpack\PermissionManager\app\Models\Role::find(6)->GivePermissionTo('Eliminar Documentos');


        foreach (Backpack\PermissionManager\app\Models\Permission::all() as $permiso) {
           Backpack\PermissionManager\app\Models\Role::find(1)->GivePermissionTo($permiso);
        }
        DB::table("role_users")->insert(['user_id' => 1, 'role_id' => 1]);

    }
}
