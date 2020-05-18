<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $department = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description
        ];

        if ($this->other_information) {
            $department['other_information'] = json_decode($this->other_information);
        }

        if (count($this->employees)) {
            $department['employees'] = EmployeeResource::collection($this->employees);
        }

        if (count($this->childDepartments)) {
            $department['children'] = DepartmentResource::collection($this->childDepartments);
        }


        return $department;
    }
}
