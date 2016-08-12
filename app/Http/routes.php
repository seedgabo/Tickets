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

		Route::get('getEncryptedFile/ticket/{id}/{clave}', ['middleware' => ['auth'], 'uses' =>'HomeController@getFileTicketEncrypted']);
		Route::get('getEncryptedFile/comentario/{id}/{clave}', ['middleware' => ['auth'], 'uses' =>'HomeController@getFileComentarioTicketEncrypted']);
	});


	Route::group(['prefix' => 'ajax'], function() {
		Route::any('/setEstadoTicket/{id}' , 'AjaxController@setEstadoTicket');
		Route::any('/addComentarioTicket' , 'AjaxController@addComentarioTicket');
		Route::any('/deleteComentarioTicket/{id}' , 'AjaxController@deleteComentarioTicket');
		Route::any('/setGuardianTicket/{id}' , 'AjaxController@setGuardianTicket');
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
	});


	Route::group(['prefix' => 'upload'], function() {
		Route::any('/cargarImagenes/{id}', ['uses' =>'UploadController@cargarImagenes']);
	});

	Route::post('tickets/', [
		'as' => 'tickets.store',
		'uses' => 'TicketsController@store',
	]);

	Route::group(['prefix' => 'api', 'middleware' => ['api','auth.basic.once']], function(){

	});
});

Route::get('api/auth', ['middleware' => 'auth.basic.once', 'uses' => 'ApiController@doLogin']);
