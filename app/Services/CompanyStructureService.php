<?php

namespace App\Services;

use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Position;
use SoapBox\Formatter\Formatter;

class CompanyStructureService
{
    /**
     * Array with inserted ids
     */

    /**
     * @var array
     */
    public $departmentsIds = [];

    /**
     * @var array
     */
    public $employeesIds = [];

    /**
     * @var array
     */
    public $positionsIds = [];

    /**
     * Get organization structure
     *
     * @param $type
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getStructure($type) {
        $structure = Department::doesntHave('parentDepartment')->get();
        $correctStructure = DepartmentResource::collection($structure);

        if ($type === 'xml') {
            $departments['departments'] = $correctStructure;
            $formatter = Formatter::make(json_encode($departments), Formatter::JSON);
            $correctStructure = $formatter->toXml();
        }

        return $correctStructure;
    }

    /**
     * Store structure in data base
     *
     * @param $structure
     * @throws \Exception
     */
    public function storeStructure($structure) {
        $structure = $this->convertArray($structure['item'], $structure);

        $structure = $this->convertToCorrectStructure($structure);

        $this->clearIdsArrays();

        $this->storeDepartments($structure);

        $this->removeDiff();
    }

    /**
     *  Clear array before save
     */
    private function clearIdsArrays() {
        $this->positionsIds = [];
        $this->employeesIds = [];
        $this->departmentsIds = [];
    }

    /**
     * Remove unused entity
     */
    private function removeDiff() {
        Position::whereNotIn('id', $this->positionsIds)->delete();
        Department::whereNotIn('id', $this->departmentsIds)->delete();
        Employee::whereNotIn('id', $this->employeesIds)->delete();
    }

    /**
     * Store info about departments in data base
     *
     * @param  array  $structure
     * @throws \Exception
     */
    private function storeDepartments(Array $structure) {
        foreach ($structure as &$department) {

            if (isset($department['saved']) && $department['saved']) {
                continue;
            }

            [$departmentSyncField, $departmentSyncValue] = $this->getSyncField($department);
            $parentSyncField = 'parent_department_' . $departmentSyncField;
            $parentDepartmentId = null;

            if (isset($department[$parentSyncField])) {
                $parentDepartment = Department::where($departmentSyncField, $department[$parentSyncField])->first();

                if (!$parentDepartment) {
                    $parentDepartmentKey = array_search($department[$parentSyncField], array_column($structure, $departmentSyncField));

                    $parentDepartmentId = $this->storeParentDepartment(
                        $structure[$parentDepartmentKey],
                        $parentSyncField,
                        $structure
                    );
                } else {
                    $parentDepartmentId = $parentDepartment->id;
                }
            }

            $this->storeDepartment($departmentSyncField, $departmentSyncValue, $parentDepartmentId,$department);
        }
    }

    /**
     * Store info about parent department in data base
     *
     * @param $department
     * @param $parentSyncField
     * @param $structure
     * @return mixed
     * @throws \Exception
     */
    private function storeParentDepartment(&$department, $parentSyncField, $structure) {
        $departmentParentId = null;

        [$departmentSyncField, $departmentSyncValue] = $this->getSyncField($department);

        if (isset($department[$parentSyncField])) {
            $parentDepartmentKey = array_search($department[$parentSyncField], array_column($structure, $departmentSyncField));
            $departmentParentId = $this->storeParentDepartment($structure[$parentDepartmentKey], $parentSyncField, $structure);
        }

        $savedDepartment = $this->storeDepartment($departmentSyncField, $departmentSyncValue, $departmentParentId,$department);

        return $savedDepartment->id;
    }

    /**
     * Store info about one department in data base
     *
     * @param $departmentSyncField
     * @param $departmentSyncValue
     * @param $departmentParentId
     * @param $department
     * @return mixed
     * @throws \Exception
     */
    private function storeDepartment($departmentSyncField, $departmentSyncValue, $departmentParentId, &$department) {
        $storedDepartment = Department::updateOrCreate(
            [
                $departmentSyncField => $departmentSyncValue
            ],
            [
                'parent_department_id' => $departmentParentId,
                'name' => $department['name'],
                'code_name' => isset($department['code_name']) ? $department['code_name'] : null,
                'description' => isset($department['description']) ? $department['description'] : null,
                'other_information' => isset($department['other_information']) ? json_encode($department['other_information']) : null,
            ]
        );

        $this->departmentsIds[] = $storedDepartment->id;

        $storedDepartment->employees()->detach();

        if (isset($department['employees'])) {
            $this->storeEmployees($department['employees'], $storedDepartment->id);
        }

        $department['saved'] = true;

        return $storedDepartment;
    }

    /**
     * Store employees in data base
     *
     * @param $employees
     * @param $departmentId
     * @throws \Exception
     */
    private function storeEmployees($employees, $departmentId) {
        $employees = $this->convertArray($employees['employee'], $employees);

        foreach ($employees as $key=>$employee) {
            $positionId = null;

            if (isset($employee['position'])) {
                $positionId = $this->storePosition($employee['position']);
            }

            if (!isset($employee['name'])) {
                throw new \Exception('Missing require field "name" in employee object: ' . json_encode($employee));
            }

            if (!isset($employee['id'])) {
                throw new \Exception('Missing require sync field "id" in employee object: ' . json_encode($employee));
            }

            $newEmployee = Employee::updateOrCreate(
                [
                    'id' => $employee['id']
                ],
                [
                    'name' => $employee['name'],
                    'other_information' => isset($employee['other_information']) ? $employee['other_information'] : null
                ]
            );

            $newEmployeeId = $newEmployee->id;
            $this->employeesIds[] = $newEmployee->id;

            $newEmployee->departments()->attach($departmentId);

            $departmentEmployee = EmployeeDepartment::where(['department_id' => $departmentId, 'employee_id' => $newEmployeeId])->firstOrFail();

            $departmentEmployee->position_id = $positionId;
            $departmentEmployee->save();
        }
    }

    /**
     * Store position in data base
     *
     * @param $position
     * @return |null
     * @throws \Exception
     */
    private function storePosition($position) {
        if ($position) {
            if (is_array($position)) {
                [$positionSyncField, $positionSyncValue] = $this->getSyncField($position);

                if (!isset($position['name'])) {
                    throw new \Exception('Missing require field "name" in position object: ' . json_encode($position));
                }

                $position = Position::updateOrCreate(
                    [
                        $positionSyncField => $positionSyncValue
                    ],
                    [
                        'name' => $position['name'],
                        'code_name' => isset($position['code_name']) ? $position['code_name'] : null,
                        'description' => isset($position['description']) ? $position['description'] : null,
                        'other_information' => isset($position['other_information']) ?
                            json_encode($position['other_information']) :
                            null,
                    ]
                );
            } else {
                $position = Position::updateOrCreate(
                    [
                        'name' => $position
                    ]
                );
            }

            $id = $position->id;

            $this->positionsIds[] = $id;
        } else {
            $id = null;
        }

        return $id;
    }

    /**
     * Convert format structure into correct format
     *
     * @param $structure
     * @return array
     * @throws \Exception
     */
    private function convertToCorrectStructure($structure) {
        $correctStructure = [];

        if (is_numeric(array_key_first($structure))) {
            foreach ($structure as $department) {
                $correctStructure = array_merge($correctStructure, $this->createDepartmentForSave($department));
            }
        } else {
            $correctStructure = $this->createDepartmentForSave($structure);
        }

        return $correctStructure;
    }

    /**
     * Recursive function which is converting tree structure in correct structure
     *
     * @param $department
     * @param  string  $parentSyncField
     * @param  null  $parentSyncValue
     * @return array
     * @throws \Exception
     */
    private function createDepartmentForSave($department, $parentSyncField = 'id', $parentSyncValue = null) {
        [$departmentSyncField, $departmentSyncValue] = $this->getSyncField($department);

        $correctDepartment = [];

        if (isset($department['children']) && count($department['children']) !== 0) {
            $department['children'] = $this->convertArray($department['children']['child'], $department['children']);

            $correctDepartment[] = $this->setParentDepartment(
                $department,
                $parentSyncField,
                $parentSyncValue
            );

            foreach ($department['children'] as $child) {
                $correctDepartment = array_merge($this->setParentDepartment(
                    $this->createDepartmentForSave($child, $departmentSyncField, $departmentSyncValue),
                    $parentSyncField,
                    $parentSyncValue
                ), $correctDepartment);
            }
        } else {
            $correctDepartment[] = $this->setParentDepartment($department, $parentSyncField, $parentSyncValue);
        }

        return $correctDepartment;
    }

    /**
     * Add parent field in one department object and remove array with children department
     *
     * @param $department
     * @param  string  $parentSyncField
     * @param  null  $parentSyncValue
     * @return mixed
     */
    private function setParentDepartment($department, $parentSyncField = 'id', $parentSyncValue = null) {
        unset($department['children']);

        if ($parentSyncValue && (isset($department[$parentSyncField]) && ($parentSyncValue !== $department[$parentSyncField]))) {
            $department['parent_department_' . $parentSyncField] = $parentSyncValue;
        }

        return $department;
    }

    /**
     * Get sync field and value
     *
     * @param $item
     * @return array
     * @throws \Exception
     */
    private function getSyncField($item) {
        $syncField = 'id';
        $syncValue = isset($item['id']) ? $item['id'] : null;

        if (isset($item['code_name'])) {
            $syncField = 'code_name';
            $syncValue = $item['code_name'];
        }

        if (!$syncValue) {
            throw new \Exception('Missing require sync field "id" or "code_name" in position object or department object: ' . json_encode($item));
        }

        return [$syncField, $syncValue];
    }

    private function convertArray($incorrectArray, $correctArray) {
        if (isset($incorrectArray)) {
            if (is_numeric(array_key_first($incorrectArray))) {
                $correctArray = $incorrectArray;
            } else {
                $correctArray = [$incorrectArray];
            }
        }

        return $correctArray;
    }
}