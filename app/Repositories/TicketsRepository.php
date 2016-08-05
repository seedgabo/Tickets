<?php

namespace App\Repositories;

use App\Models\Tickets;
use InfyOm\Generator\Common\BaseRepository;

class TicketsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        "titulo",
		"contenido",
		"user_id",
		"estado",
		"categoria_id",
		"archivo"
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Tickets::class;
    }
}
