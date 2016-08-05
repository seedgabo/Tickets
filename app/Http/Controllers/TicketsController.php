<?php

namespace App\Http\Controllers;

use App\Funciones;
use App\Http\Requests;
use App\Http\Requests\CreateTicketsRequest;
use App\Http\Requests\UpdateTicketsRequest;
use App\Models\Tickets;
use App\Repositories\TicketsRepository;
use App\User;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Controller\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class TicketsController extends AppBaseController
{
    /** @var  TicketsRepository */
    private $ticketsRepository;

    function __construct(TicketsRepository $ticketsRepo)
    {
        $this->ticketsRepository = $ticketsRepo;
        $this->middleware('auth');

    }

    /**
     * Display a listing of the Tickets.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->ticketsRepository->pushCriteria(new RequestCriteria($request));
        $tickets = Tickets::like("titulo",$request->input('titulo'))->get();
        return view('tickets.index')
            ->with('tickets', $tickets);
    }

    /**
     * Show the form for creating a new Tickets.
     *
     * @return Response
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created Tickets in storage.
     *
     * @param CreateTicketsRequest $request
     *
     * @return Response
     */
    public function store(CreateTicketsRequest $request)
    {
        $input = $request->except("archivo");
        $tickets = \App\Models\Tickets::create($input);
        $tickets->created_at = new \Carbon\Carbon();
        $tickets->save();
        if($request->hasFile('archivo'))
        {   
            $nombre = $request->file("archivo")->getClientOriginalName();
            $request->file('archivo')->move(public_path("archivos/tickets/"), $nombre );
            $tickets->archivo = $nombre;
            $tickets->save();
        }
        Funciones::sendMailNewTicket($tickets, \App\User::find($tickets->user_id), \App\User::find($tickets->guardian_id));
        Flash::success('Tickets guardado correctamente.');

        return back();
    }

    /**
     * Display the specified Tickets.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tickets = $this->ticketsRepository->findWithoutFail($id);

        if (empty($tickets)) {
            Flash::error('Tickets no encontrado');

            return redirect(route('tickets.index'));
        }

        return view('tickets.show')->with('tickets', $tickets);
    }

    /**
     * Show the form for editing the specified Tickets.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tickets = $this->ticketsRepository->findWithoutFail($id);

        if (empty($tickets)) {
            Flash::error('Tickets no encontrado');

            return redirect(route('tickets.index'));
        }

        return view('tickets.edit')->with('tickets', $tickets);
    }

    /**
     * Update the specified Tickets in storage.
     *
     * @param  int              $id
     * @param UpdateTicketsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicketsRequest $request)
    {
        $tickets = $this->ticketsRepository->findWithoutFail($id);
        if (empty($tickets)) {
            Flash::error('Tickets no encontrado');

            return redirect(route('tickets.index'));
        }

        $tickets = $this->ticketsRepository->update($request->except("archivo"), $id);

        if($request->hasFile('archivo'))
        {
            $nombre = $request->file("archivo")->getClientOriginalName();
            $request->file('archivo')->move(public_path("archivos/tickets/"), $nombre );
            $tickets->archivo = $nombre;
            $tickets->save();
        }

        Flash::success('Tickets guardado correctamente.');

        return back();
    }

    /**
     * Remove the specified Tickets from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tickets = $this->ticketsRepository->findWithoutFail($id);

        if (empty($tickets)) {
            Flash::error('Tickets no encontrado');

            return redirect(route('tickets.index'));
        }

        $this->ticketsRepository->delete($id);

        Flash::success('Tickets eliminado correctamente.');

        return redirect(route('tickets.index'));
    }
}
