<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EntityCRUDTest extends TestCase
{
    public function testCreateTestUser() {
        $this->createTestUser();
    }

    /**
     * Position test
     */
    public function testSetPositionCorrect() {
        $this->makeRequest('POST', false, true, 'position', 200);
    }

    public function testSetPositionIncorrect() {
        $this->makeRequest('POST', false, true, 'position', 400);
    }

    public function testUpdatePosition() {
        $this->makeRequest('PATCH', true, true, 'position', 200);
    }

    public function testGetOnePosition() {
        $this->makeRequest('GET', true, false, 'position', 200);
    }

    public function testGetPositions() {
        $this->makeRequest('GET', false, false, 'position', 200);
    }

    /**
     * Employee test
     */
    public function testSetEmployeeCorrect() {
        $this->makeRequest('POST', false, true, 'employee', 200);
    }

    public function testSetEmployeeIncorrect() {
        $this->makeRequest('POST', false, true, 'employee', 400);
    }

    public function testUpdateEmployee() {
        $this->makeRequest('PATCH', true, true, 'employee', 200);
    }

    public function testGetOneEmployee() {
        $this->makeRequest('GET', true, false, 'employee', 200);
    }

    public function testGetEmployees() {
        $this->makeRequest('GET', false, false, 'employee', 200);
    }

    /**
     * Department test
     */
    public function testSetDepartmentCorrect() {
        $this->makeRequest('POST', false, true, 'department', 200);
    }

    public function testSetDepartmentIncorrect() {
        $this->makeRequest('POST', false, true, 'department', 400);
    }

    public function testUpdateDepartment() {
        $this->makeRequest('PATCH', true, true, 'department', 200);
    }

    public function testGetOneDepartment() {
        $this->makeRequest('GET', true, false, 'department', 200);
    }

    public function testGetDepartment() {
        $this->makeRequest('GET', false, false, 'department', 200);
    }

    /**
     * Employee test
     */
    public function testSetGroupCorrect() {
        $this->makeRequest('POST', false, true, 'group', 200);
    }

    public function testSetGroupIncorrect() {
        $this->makeRequest('POST', false, true, 'group', 400);
    }

    public function testUpdateGroup() {
        $this->makeRequest('PATCH', true, true, 'group', 200);
    }

    public function testGetOneGroup() {
        $this->makeRequest('GET', true, false, 'group', 200);
    }

    public function testGetGroup() {
        $this->makeRequest('GET', false, false, 'group', 200);
    }

    /**
     * Delete test's entity
     */

    public function testDeleteGroup() {
        $this->makeRequest('DELETE', true, false, 'group', 204);
    }

    public function testDeleteDepartment() {
        $this->makeRequest('DELETE', true, false, 'department', 204);
    }

    public function testDeleteEmployee() {
        $this->makeRequest('DELETE', true, false, 'employee', 204);
    }

    public function testDeletePosition() {
        $this->makeRequest('DELETE', true, false, 'position', 204);
    }

    public function testTruncateTable() {
        $this->removeStructure();
    }
}
