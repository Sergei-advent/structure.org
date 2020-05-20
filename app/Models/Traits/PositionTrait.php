<?php

namespace App\Models\Traits;

use App\Models\Position;

trait PositionTrait {

    /**
     * @var integer
     */
    public $current_entity;

    /**
     * @var integer
     */
    public $current_employee;

    /**
     * @var string
     */
    protected $name_field;

    public function position() {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }
}