<?php

namespace App\Http\Resources;

use App\Models\EmployeeFunctionalGroup;
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
            'description' => $this->description,
            'parent_functional_group_id' => $this->parent_functional_group_id
        ];

        if ($this->other_information) {
            $functionalGroup['other_information'] = json_decode($this->other_information);
        }

        if (count($this->employees)) {
            foreach ($this->employees as &$employee) {
                $position = EmployeeFunctionalGroup::where(['functional_group_id' => $this->id, 'employee_id' => $employee['id']])->first();

                if ($position) {
                    $employee->position = $position->position;
                }
            }

            $functionalGroup['employees'] = EmployeeResource::collection($this->employees);
        }

        if (count($this->childFunctionalGroups)) {
            $functionalGroup['children'] = FunctionalGroupResource::collection($this->childFunctionalGroups);
        }


        return $functionalGroup;
    }
}
