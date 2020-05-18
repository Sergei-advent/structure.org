<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $position = [
            'id' => $this->id,
            'name' => $this->name
        ];

        if ($this->other_information) {
            $employee['other_information'] = json_decode($this->other_information);
        }

        return $position;
    }
}
