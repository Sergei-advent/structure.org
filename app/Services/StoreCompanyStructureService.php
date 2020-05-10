<?php

namespace App\Services;

use App\Models\Department;

class StoreCompanyStructureService
{
    /**
     * Store structure in data base
     *
     * @param array $structure
     */
    public function storeStructure($structure) {
        $structure = $this->convertToCorrectStructure($structure);

        $this->storeDepartments($structure);
    }

    /**
     * Store info about departments in data base
     *
     * @param array $structure
     */
    private function storeDepartments(Array $structure) {
        foreach ($structure as $department) {
            [$departmentSyncField, $departmentSyncValue] = $this->getSyncField($department);

            $parentSyncField = 'parent_department_' . $departmentSyncField;
            $parentDepartmentId = Department::where($departmentSyncField, $department[$parentSyncField])->first();


            /**
             * TODO parent save
             */
            /*while(!$parentDepartmentId) {
                $parentDepartmentId = Department::where($departmentSyncField, $department[$parentSyncField])->first();


                $parentDepartmentId = array_search($department[$parentSyncField], array_column($structure, $departmentSyncField));

                $currentDepartment = $structure[$parentDepartmentId];


            }

            if (!$parentDepartmentId) {
                $parentDepartmentId = array_search($department[$parentSyncField], array_column($structure, $departmentSyncField));
                Department::create([
                    'parent_department_id' => $parentDepartmentId,
                    'name' => $department['name'],
                    'code_name' => isset($department['code_name']) ? $department['code_name'] : null,
                    'description' => isset($department['description']) ? $department['description'] : null,
                    'other_information' => isset($department['other_information']) ? json_encode($department['other_information']) : null,
                ]);
            }*/

            Department::updateOrCreate(
                [
                    $departmentSyncField => $departmentSyncValue
                ],
                [
                    'parent_department_id' => $parentDepartmentId,
                    'name' => $department['name'],
                    'code_name' => isset($department['code_name']) ? $department['code_name'] : null,
                    'description' => isset($department['description']) ? $department['description'] : null,
                    'other_information' => isset($department['other_information']) ? json_encode($department['other_information']) : null,
                ]
            );
        }
    }

    /**
     * Convert format structure into correct format
     *
     * @param $structure
     * @return array
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

    private function createDepartmentForSave($department, $parentSyncField = 'id', $parentSyncValue = null) {
        [$departmentSyncField, $departmentSyncValue] = $this->getSyncField($department);

        $correctDepartment = [];

        if (isset($department['children']) && count($department['children']) !== 0) {

            $correctDepartment[] = $this->setParrentDepartment(
                $department,
                $parentSyncField,
                $parentSyncValue
            );

            foreach ($department['children'] as $child) {
                $correctDepartment = array_merge($this->setParrentDepartment(
                    $this->createDepartmentForSave($child, $departmentSyncField, $departmentSyncValue),
                    $parentSyncField,
                    $parentSyncValue
                ), $correctDepartment);
            }
        } else {
            $correctDepartment[] = $this->setParrentDepartment($department, $parentSyncField, $parentSyncValue);
        }

        return $correctDepartment;
    }

    private function setParrentDepartment($department, $parentSyncField = 'id', $parentSyncValue = null) {
        unset($department['children']);

        if ($parentSyncValue && isset($department[$parentSyncField]) && ($parentSyncValue !== $department[$parentSyncField])) {
            $department['parent_department_' . $parentSyncField] = $parentSyncValue;
        }

        return $department;
    }

    private function getSyncField($department) {
        $departmentSyncField = 'id';
        $departmentSyncValue = isset($department['id']) ? $department['id'] : null;

        if (isset($department['code_name'])) {
            $departmentSyncField = 'code_name';
            $departmentSyncValue = $department['code_name'];
        }

        return [$departmentSyncField, $departmentSyncValue];
    }
}