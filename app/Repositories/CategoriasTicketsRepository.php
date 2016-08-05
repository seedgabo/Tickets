<?php

namespace App\Repositories;

use App\Models\CategoriasTickets;
use InfyOm\Generator\Common\BaseRepository;

class CategoriasTicketsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        "nombre",
		"descripción",
		"user_id"
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CategoriasTickets::class;
    }
}
