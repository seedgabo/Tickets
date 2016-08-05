<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateCategoriasTicketsRequest;
use App\Http\Requests\UpdateCategoriasTicketsRequest;
use App\Repositories\CategoriasTicketsRepository;
use Illuminate\Http\Request;
use Flash;
use InfyOm\Generator\Controller\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CategoriasTicketsController extends AppBaseController
{
    /** @var  CategoriasTicketsRepository */
    private $categoriasTicketsRepository;

    function __construct(CategoriasTicketsRepository $categoriasTicketsRepo)
    {
        $this->categoriasTicketsRepository = $categoriasTicketsRepo;
        $this->middleware('auth');

    }

    /**
     * Display a listing of the CategoriasTickets.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->categoriasTicketsRepository->pushCriteria(new RequestCriteria($request));
        $categoriasTickets = $this->categoriasTicketsRepository->all();

        return view('categoriasTickets.index')
            ->with('categoriasTickets', $categoriasTickets);
    }

    /**
     * Show the form for creating a new CategoriasTickets.
     *
     * @return Response
     */
    public function create()
    {
        return view('categoriasTickets.create');
    }

    /**
     * Store a newly created CategoriasTickets in storage.
     *
     * @param CreateCategoriasTicketsRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoriasTicketsRequest $request)
    {
        $input = $request->all();

        $categoriasTickets = $this->categoriasTicketsRepository->create($input);

        Flash::success('CategoriasTickets guardado correctamente.');

        return redirect(route('categoriasTickets.index'));
    }

    /**
     * Display the specified CategoriasTickets.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $categoriasTickets = $this->categoriasTicketsRepository->findWithoutFail($id);

        if (empty($categoriasTickets)) {
            Flash::error('CategoriasTickets no encontrado');

            return redirect(route('categoriasTickets.index'));
        }

        return view('categoriasTickets.show')->with('categoriasTickets', $categoriasTickets);
    }

    /**
     * Show the form for editing the specified CategoriasTickets.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $categoriasTickets = $this->categoriasTicketsRepository->findWithoutFail($id);

        if (empty($categoriasTickets)) {
            Flash::error('CategoriasTickets no encontrado');

            return redirect(route('categoriasTickets.index'));
        }

        return view('categoriasTickets.edit')->with('categoriasTickets', $categoriasTickets);
    }

    /**
     * Update the specified CategoriasTickets in storage.
     *
     * @param  int              $id
     * @param UpdateCategoriasTicketsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoriasTicketsRequest $request)
    {
        $categoriasTickets = $this->categoriasTicketsRepository->findWithoutFail($id);

        if (empty($categoriasTickets)) {
            Flash::error('CategoriasTickets no encontrado');

            return redirect(route('categoriasTickets.index'));
        }

        $categoriasTickets = $this->categoriasTicketsRepository->update($request->all(), $id);

        Flash::success('CategoriasTickets guardado correctamente.');

        return redirect(route('categoriasTickets.index'));
    }

    /**
     * Remove the specified CategoriasTickets from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $categoriasTickets = $this->categoriasTicketsRepository->findWithoutFail($id);

        if (empty($categoriasTickets)) {
            Flash::error('CategoriasTickets no encontrado');

            return redirect(route('categoriasTickets.index'));
        }

        $this->categoriasTicketsRepository->delete($id);

        Flash::success('CategoriasTickets eliminado correctamente.');

        return redirect(route('categoriasTickets.index'));
    }
}
