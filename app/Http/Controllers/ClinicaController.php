<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Casos_medicos;
use App\Models\Incapacidad;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClinicaController extends Controller
{
   
  	public function verPaciente(Request $request, $id)
  	{
  		$paciente = Paciente::with("medico","eps","arl","casos")->find($id);

  		return view("clinica.ver-paciente")
  		->withPaciente($paciente)
  		->withTitle($paciente->full_name);
  	}

  	public function verCasoMedico(Request $request, $id)
  	{
  		$caso = Casos_medicos::find($id);
  		$paciente = $caso->paciente;
      $recomendaciones = $caso->recomendaciones()->orderBy("created_at","desc")->get();

  		return view("clinica.ver-caso")
  		->withPaciente($paciente)
  		->withCaso($caso)
      ->withRecomendaciones($recomendaciones)
  		->withTitle("Caso de Paciente: " . $paciente->full_name);
  	}

    public function verIncapacidad(Request $request, $id)
    {
      $incapacidad = Incapacidad::find($id);
      $paciente = $incapacidad->paciente;
      $caso = $incapacidad->caso;

      return view("clinica.ver-incapacidad")
      ->withPaciente($paciente)
      ->withCaso($caso)
      ->withIncapacidad($incapacidad)
      ->withTitle("Incapacidad de Paciente: " . $paciente->full_name);
    }

    public function iniciarSeguimiento(Request $request, $id)
    {
      $caso_medico = Casos_medicos::findorFail($id);
      $ticket = \App\Models\Tickets::Create([
            "titulo" => "Seguimiento al Caso del paciente " . $caso_medico->paciente->full_name,
            "contenido" => "Origen del Caso: " . $caso_medico->origen_del_caso,
            "categoria_id" => Casos_medicos::categoria_id_casos,
            "user_id" =>  Auth::user()->id,
            "guardian_id" => Auth::user()->id,
            "estado" => "abierto",
            "transferible" => "1",
        ]);
      $caso_medico->ticket_id = $ticket->id;
      $caso_medico->save();
      \App\Funciones::sendMailNewTicket($ticket, $ticket->user, $ticket->guardian);
      return redirect("/ticket/ver/" . $ticket->id);
    }

    public function buscarPaciente(Request $request)
    {
      $query = $request->input('query');

      $paciente = Paciente::where("cedula","=", $query)->first();
      if (!isset($paciente))
      {
          $paciente = Paciente::orwhere("nombres","LIKE", "%" . $query . "%")->orWhere("apellidos", "LIKE","%" . $query . "%")->first();
      }
      if (!isset($paciente))
      {
          return "No se Encontró ningún paciente";
      }
      return redirect('admin/ver-paciente/'. $paciente->id);
    }

    public function cargarArchivoPaciente(Request $request, $id)
    {
       $archivo = $request->file("archivo");
       $nombre = $archivo->getClientOriginalName();
       $entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
       $archivo->move(storage_path("app/archivos/"), $entrada->id);
       DB::table("archivo_paciente")->insert(["archivo_id" => $entrada->id, 'paciente_id' => $id]);
       \Alert::success("archivo Cargado Correctamente")->flash();
       return back();
    }
    
    public function cargarArchivoCaso(Request $request, $id)
    {
       $archivo = $request->file("archivo");
       $nombre = $archivo->getClientOriginalName();
       
       $entrada = \App\Models\Archivos::create(['nombre' => $nombre]);
       $archivo->move(storage_path("app/archivos/"), $entrada->id);

       DB::table("archivo_casos")->insert(["archivo_id" => $entrada->id, 'caso_id' => $id]);
       \Alert::success("archivo Cargado Correctamente")->flash();
       return back();
    }
}
