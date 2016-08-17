<?php

use App\Funciones;
use Illuminate\Support\Facades\Input;

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers:Origin, Content-Type, Accept, Authorization, X-Requested-With,X-XSRF-TOKEN, Auth-Token');


Route::group(['middleware' => 'web'], function () {


	Route::group([], function() {
		Route::auth();
		Route::get('/', 'HomeController@index');
		Route::get('/home', 'HomeController@index');
		Route::any('/menu',   ['middleware' => [], 'uses' =>'HomeController@menu']);

		Route::get('profile' ,['middleware' => ['auth'], 'uses' => "HomeController@profile"]);
		Route::put('profile' ,['middleware' => ['auth'], 'uses' => "HomeController@profileUpdate"]);

		Route::get('/ticket',   ['middleware' => ['auth'], 'uses' =>'HomeController@tickets']);
		Route::get('/mis-tickets',   ['middleware' => ['auth'], 'uses' =>'HomeController@misTickets']);
		Route::get('/tickets/todos',   ['middleware' => ['auth'], 'uses' =>'HomeController@Todostickets']);
		Route::get('/tickets/categoria/{categoria}',   ['middleware' => ['auth'], 'uses' =>'HomeController@porCategoria']);
		Route::get('/ticket/ver/{id}',   ['middleware' => ['auth'], 'uses' =>'HomeController@ticketVer']);
		Route::get('ticket/eliminar/{id}', ['middleware' => ['auth'], 'uses' =>'HomeController@ticketEliminar']);

		Route::get('ver-documentos', ['midleware' => ['auth'], 'uses' => 'HomeController@listarCategorias']);
		Route::get('ver-documentos/{categoria}', ['midleware' => ['auth'], 'uses' => 'HomeController@listarDocumentos']);

		Route::get('getDocumento/{id}', ['middleware' => ['auth'], 'uses' =>'HomeController@getDocumento']);		
		Route::get('getEncryptedFile/ticket/{id}/{clave}', ['middleware' => ['auth'], 'uses' =>'HomeController@getFileTicketEncrypted']);
		Route::get('getEncryptedFile/comentario/{id}/{clave}', ['middleware' => ['auth'], 'uses' =>'HomeController@getFileComentarioTicketEncrypted']);
	
		Route::get('busqueda',['middleware' => ['auth'], 'uses' => "HomeController@busqueda"]);
	});


	Route::group(['prefix' => 'ajax'], function() {
		Route::any('/setEstadoTicket/{id}' , 'AjaxController@setEstadoTicket');
		Route::any('/addComentarioTicket' , 'AjaxController@addComentarioTicket');
		Route::any('/deleteComentarioTicket/{id}' , 'AjaxController@deleteComentarioTicket');
		Route::any('/setGuardianTicket/{id}' , 'AjaxController@setGuardianTicket');
		Route::any('/setVencimiento/{id}' , 'AjaxController@setVencimientoTicket');
		Route::any('/getUsersbyCategoria' , 'AjaxController@getUsersbyCategoria');
	});


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




	Route::post('tickets/', [
		'as' => 'tickets.store',
		'uses' => 'TicketsController@store',
	]);

	Route::group(['prefix' => 'api', 'middleware' => ['api','auth.basic.once']], function(){
			Route::get('login', 'ApiController@doLogin');
			Route::get('getCategorias', 'ApiController@getCategorias');
			Route::get('documentos/getCategorias', 'ApiController@getCategoriasDocumentos');
			Route::get('{categoria}/getTickets', 'ApiController@getTickets');
			Route::get('getTicket/{ticket_id}', 'ApiController@getTicket');			
			Route::get('{categoria}/getDocumentos', 'ApiController@getDocumentos');
			
	});

	Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin']], function(){
		CRUD::resource('categorias', 'Admin\CategoriasCrudController');
		CRUD::resource('usuarios', 'Admin\UsuariosCrudController');
		CRUD::resource('tickets', 'Admin\TicketsCrudController');
		CRUD::resource('documentos', 'Admin\DocumentoCrudController');
		CRUD::resource('categoriadocumentos', 'Admin\CategoriaDocumentosCrudController');
	});
	
});

Route::get('api/auth', ['middleware' => 'auth.basic.once', 'uses' => 'ApiController@doLogin']);

Route::get('getListaCategorias', function(Request $request) {

	return view('lista-documentos-tree');

});