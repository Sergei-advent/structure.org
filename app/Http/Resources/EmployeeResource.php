<?php

namespace App\Http\Resources;

use App\Models\EmployeeDepartment;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $employee = [
            'id' => $this->id,
            'name' => $this->name
        ];

        if ($this->other_information) {
            $employee['other_information'] = json_decode($this->other_information);
        }

        if ($this->position) {
            $employee['position'] = PositionResource::make($this->position);
        } else {
            $positions = EmployeeDepartment::where(['employee_id' => $this->id])->get();

            $employee['position'] = [];

            foreach ($positions as $position) {
                $employee['position'][] = [
                    'position' => PositionResource::make($position->position),
                    'department_id' => $position->department_id
                ];
            }
        }

        return $employee;
    }
}
