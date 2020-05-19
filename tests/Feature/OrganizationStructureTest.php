<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationStructureTest extends TestCase
{
    public function testSetStructureCorrect() {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'token' => 'test',
        ])->json('POST', '/api/structure', $this->generateCorrectStructure());

        $this->removeStructure();

        $response->assertStatus(204);
    }

    public function testSetStructureIncorrect() {
        foreach ($this->generateIncorrectStructures() as $structure) {
            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'token' => 'test',
            ])->json('POST', '/api/structure', $structure);

            $this->removeStructure();

            $response->assertStatus(400);
        }
    }

    public function testGetStructure() {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'token' => 'test',
        ])->json('GET', '/api/structure');

        $response->assertStatus(200);
    }

    public function testRemoveTestUser() {
        $this->removeTestUser();
    }
}
