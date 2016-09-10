<?php

use App\Funciones;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;


header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers:Origin, Content-Type, Accept, Authorization, X-Requested-With,X-XSRF-TOKEN, Auth-Token');
Carbon::setLocale('es');

Route::group(['middleware' => 'web'], function () {

	//Home
	Route::group([], function() {
		Route::auth();
		Route::get('/', 'HomeController@index');
		Route::get('/home', 'HomeController@index');
		Route::any('/menu',   ['middleware' => [], 'uses' =>'HomeController@menu']);

		Route::get('profile' ,['middleware' => ['auth'], 'uses' => "HomeController@profile"]);
		Route::put('profile' ,['middleware' => ['auth'], 'uses' => "HomeController@profileUpdate"]);

		Route::get('agregar-ticket', ['middleware' => ['auth'], 'uses' =>'HomeController@ticketAgregar']);
		Route::get('/ticket',   ['middleware' => ['auth'], 'uses' =>'HomeController@tickets']);
		Route::get('/mis-tickets',   ['middleware' => ['auth'], 'uses' =>'HomeController@misTickets']);
		Route::get('/tickets/todos',   ['middleware' => ['auth'], 'uses' =>'HomeController@Todostickets']);
		Route::get('/tickets/categoria/{categoria}',   ['middleware' => ['auth'], 'uses' =>'HomeController@porCategoria']);
		Route::get('/ticket/ver/{id}',   ['middleware' => ['auth'], 'uses' =>'HomeController@ticketVer']);
		Route::get('ticket/eliminar/{id}', ['middleware' => ['auth'], 'uses' =>'HomeController@ticketEliminar']);
		Route::put('editar-ticket/{id}', ['middleware' => ['auth'], 'uses' => 'HomeController@ticketEditar']);

		Route::get('ver-documentos', ['midleware' => ['auth'], 'uses' => 'HomeController@listarCategorias']);
		Route::get('ver-documentos/{categoria}', ['midleware' => ['auth'], 'uses' => 'HomeController@listarDocumentos']);

		Route::get('archivo/{id}',['midleware' => ['auth'], 'uses' => 'HomeController@descargarArchivo']);

		Route::get('getDocumento/{id}', ['middleware' => ['auth'], 'uses' =>'HomeController@getDocumento']);		
		Route::get('getEncryptedFile/ticket/{id}/{clave}', ['middleware' => ['auth'], 'uses' =>'HomeController@getFileTicketEncrypted']);
		Route::get('getEncryptedFile/comentario/{id}/{clave}', ['middleware' => ['auth'], 'uses' =>'HomeController@getFileComentarioTicketEncrypted']);
	
		Route::get('busqueda',['middleware' => ['auth'], 'uses' => "HomeController@busqueda"]);
	});

	//ajax
	Route::group(['prefix' => 'ajax'], function() {
		Route::any('/setEstadoTicket/{id}' , 'AjaxController@setEstadoTicket');
		Route::any('/setInvitadosTickets/{id}' , 'AjaxController@setInvitadosTickets');
		Route::any('/addComentarioTicket' , 'AjaxController@addComentarioTicket');
		Route::any('/deleteComentarioTicket/{id}' , 'AjaxController@deleteComentarioTicket');
		Route::any('/setGuardianTicket/{id}' , 'AjaxController@setGuardianTicket');
		Route::any('/setVencimiento/{id}' , 'AjaxController@setVencimientoTicket');
		Route::any('/getUsersbyCategoria' , 'AjaxController@getUsersbyCategoria');
		Route::any('/email' , 'AjaxController@email');
	});

	//Recursos
	Route::group(['middleware' =>['auth','isAdmin']], function() {

		Route::resource('Usuarios', 'UsuarioController');
		Route::any('Usuarios/delete/{id}', [
			'as' => 'usuario.delete',
			'uses' => 'UsuarioController@destroy'
		]);

		Route::resource("tickets", "TicketsController");
		Route::get('tickets/delete/{id}', [
			'as' => 'tickets.delete',
			'uses' => 'TicketsController@destroy',
		]);

		Route::resource("categoriasTickets", "CategoriasTicketsController");
		Route::get('categoriasTickets/delete/{id}', [
			'as' => 'categoriasTickets.delete',
			'uses' => 'CategoriasTicketsController@destroy',
		]);
		
		Route::resource('documentos', 'DocumentosController');
		Route::get('Documentos/delete/{id}', [
			'as' => 'documentos.delete',
			'uses' => 'DocumentosController@destroy',
		]);
	});

	//recursos Tickets
	Route::post('tickets/', [
		'as' => 'tickets.store',
		'uses' => 'TicketsController@store',
	]);

	//Api
	Route::group(['prefix' => 'api', 'middleware' => ['api','auth.basic.once']], function(){
			Route::get('login', 'ApiController@doLogin');
			Route::get('getCategorias', 'ApiController@getCategorias');
			Route::get('getAllCategorias', 'ApiController@getAllCategorias');
			Route::get('documentos/getCategorias', 'ApiController@getCategoriasDocumentos');
			Route::get('{categoria}/getTickets', 'ApiController@getTickets');
			Route::get('getTicket/{ticket_id}', 'ApiController@getTicket');			
			Route::get('{categoria}/getDocumentos', 'ApiController@getDocumentos');
			Route::get('getDocumento/{id}','HomeController@getDocumento');		
			Route::get('getUsuariosCategoria/{categoria_id}', 'ApiController@getUsuariosCategoria');
			Route::get('search', 'ApiController@busqueda');

			Route::get('getMisTickets', 'ApiController@getMisTickets');
			Route::get('getAllTickets', 'ApiController@getAllTickets');
			Route::get('getTicketsVencidos', 'ApiController@getTicketsVencidos');
			Route::get('getTicketsAbiertos', 'ApiController@getTicketsAbiertos');

			Route::post('addTicket', 'ApiController@addTicket');
			Route::post('addComentario/{ticket_id}','ApiController@addComentarioTicket');		
			Route::delete('deleteComenarioTicket/{comentario_id}','ApiController@deleteComentarioTicket');	

			Route::get('getEncryptedFile/ticket/{id}/{clave}','HomeController@getFileTicketEncrypted');
			Route::get('getEncryptedFile/comentario/{id}/{clave}','HomeController@getFileComentarioTicketEncrypted');


			Route::get('getPacientes','ApiController@getPacientes');
			Route::get('getCaso/{id}','ApiController@getCaso');
			Route::get('getIncapacidad/{id}','ApiController@getIncapacidad');
			Route::get('iniciar-seguimiento/{id}', 'ApiController@iniciarSeguimiento');

			Route::resource('dispositivos', 'DispositivosController');
	});

	Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function(){

		CRUD::resource('categorias', 'Admin\CategoriasCrudController');
		CRUD::resource('usuarios', 'Admin\UsuariosCrudController');
		CRUD::resource('tickets', 'Admin\TicketsCrudController');
		CRUD::resource('documentos', 'Admin\DocumentoCrudController');
		CRUD::resource('categoriadocumentos', 'Admin\CategoriaDocumentosCrudController');
		CRUD::resource('alertas', 'Admin\AlertaCrudController');
		CRUD::resource('archivos', 'Admin\ArchivosCrudController');

		Route::get('email/departamento', 'AdminController@emailPorDepartamento');
		Route::get('email/puesto', 'AdminController@emailPorPuesto');
		Route::get('email/usuarios', 'AdminController@emailAUsuarios');
		Route::get('email/usuarios/departamento', 'AdminController@emailAUsuariosPorDepartamento');

		Route::get('categorias-masivas/{categoria}', 'AdminController@categoriasUsuarios');
		Route::put('categorias-masivas/{categoria}', 'AdminController@agregarmasivamente');
	});

	//Clinica
	Route::group(['prefix' => 'admin', 'middleware' => []], function(){
		// Historias Clinicas
		CRUD::resource('pacientes', 'Admin\PacienteCrudController');
		CRUD::resource('casos-medicos', 'Admin\Casos_medicosCrudController');
		CRUD::resource('recomendaciones', 'Admin\RecomendacionCrudController');
		CRUD::resource('incapacidades', 'Admin\IncapacidadCrudController');
		CRUD::resource('puestos', 'Admin\PuestoCrudController');
		CRUD::resource('fondos-de-pensiones', 'Admin\FondosDePensionesCrudController');
		CRUD::resource('eps', 'Admin\EpsCrudController');
		CRUD::resource('arl', 'Admin\ArlCrudController');
		CRUD::resource('cie10', 'Admin\Cie10CrudController');


		Route::get('ver-paciente/{id}', 'ClinicaController@verPaciente');
		Route::get('ver-caso/{id}', 'ClinicaController@verCasoMedico');
		Route::get('ver-incapacidad/{id}', 'ClinicaController@verIncapacidad');
		Route::get('iniciar-seguimiento/{id}', 'ClinicaController@iniciarSeguimiento');
		Route::get('paciente/buscar', 'ClinicaController@buscarPaciente');
		
		Route::post('cargar-archivo/paciente/{id}','ClinicaController@cargarArchivoPaciente');
		Route::post('cargar-archivo/caso/{id}','ClinicaController@cargarArchivoCaso');
	});

	//audiotira
	Route::group(['prefix' => 'admin/auditar','middleware' => ['isAdmin']], function(){
		Route::get('usuario/{user_id?}', 'AdminController@auditarUsuario');		
	});
	
	//miscelaneo
	Route::group([], function(){
		Route::get('getListaCategoriasDocumentos', function(Request $request) {return view('lista-documentos-tree');});
		Route::get('getListaCategoriasTickets', function(Request $request) {return view('lista-tickets-tree');});
		Route::get('emitir-alerta', function(Request $request){$alerta =\App\Models\Alerta::findorFail(Input::get('alerta_id'));$alerta->emitir();});
		Route::get('email', function(Request $request){
			$correos = App\Models\Paciente::get()->lists('email','full_name');
			return view("Admin.email")->withCorreos($correos)->withActivo('emailPorUsuario');
		});
	});
});

Route::get('api/auth', ['middleware' => 'auth.basic.once', 'uses' => 'ApiController@doLogin']);


