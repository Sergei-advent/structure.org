<?php

namespace App\Services;

use App\Models\Department;

class StoreCompanyStructureService
{
    /**
     * Type input content
     *
     * @var string
     */
    private $type;

    /**
     * File or json info about structure
     *
     * @var mixed
     */
    private $content;

    /**
     * @param  String  $type
     * @param mixed $content
     */
    public function setParams(String $type, $content) {
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Store structure in data base
     */
    public function storeStructure() {
        $structure = $this->content;

        if ($this->type === 'xml') {
            $structure = $this->convertXml();
        }

        $structure = structure_convert($structure);

        $this->storeDepartments($structure);
    }

    /**
     * Store info about departments in data base
     *
     * @param array $structure
     */
    private function storeDepartments(Array $structure) {
        foreach ($structure as $department) {
            $departmentSyncField = 'id';
            $departmentSyncValue = isset($department->id) ? $department->id : null;

            if (isset($department->code_name)) {
                $departmentSyncField = 'code_name';
                $departmentSyncValue = $department->code_name;
            }

            Department::updateOrCreate(
                [
                    $departmentSyncField => $departmentSyncValue
                ],
                [
                    'parent_department_id' => $department->parent_department_id,
                    'name' => $department->name,
                    'code_name' => isset($department->code_name) ? $department->code_name : null,
                    'description' => isset($department->description) ? $department->description : null,
                    'other_information' => isset($department->other_information) ? $department->other_information : null,
                ]
            );
        }
    }

    /**
     * convert XML structure into array
     *
     * @return array
     */
    private function convertXml() {
        $structure = $this->content;

        return $structure;
    }
}