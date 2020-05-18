<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FunctionalGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $functionalGroup = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description
        ];

        if ($this->other_information) {
            $functionalGroup['other_information'] = json_decode($this->other_information);
        }

        if (count($this->employees)) {
            $functionalGroup['employees'] = EmployeeResource::collection($this->employees);
        }

        if (count($this->childDepartments)) {
            $functionalGroup['children'] = FunctionalGroupResource::collection($this->childFunctionaGroup);
        }


        return $functionalGroup;
    }
}
