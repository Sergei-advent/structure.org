<?php

namespace Tests;

use App\Models\Department;
use App\Models\Employee;
use App\Models\FunctionalGroup;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function generateCorrectStructure() {
        return [
            'structure' => [
                [
                    'name' => 'dep1',
                    'code_name' => 'dep1',
                    'description' => 'test',
                    'other_information' => [
                        'year' => 1995
                    ],
                    'children' => [
                        [
                            'name' => 'dep11',
                            'code_name' => 'dep11',
                            'description' => 'test',
                            'other_information' => [
                                'year' => 1995
                            ],
                        ],
                        [
                            'name' => 'dep12',
                            'code_name' => 'dep12',
                            'description' => 'test',
                            'other_information' => [
                                'year' => 1995
                            ],
                        ]
                    ],
                    'employees' => [
                        [
                            'id' => 1,
                            'name' => 'emp1',
                            'position' => [
                                'code_name' => 'pos1',
                                'name' => 'pos1'
                            ]
                        ],
                        [
                            'id' => 2,
                            'name' => 'emp2',
                            'position' => 'pos2'
                        ]
                    ]
                ],
                [
                    'name' => 'dep2',
                    'code_name' => 'dep2',
                    'description' => 'test',
                    'other_information' => [
                        'year' => 1995
                    ],
                    'children' => [
                        [
                            'name' => 'dep21',
                            'code_name' => 'dep21',
                            'description' => 'test',
                            'other_information' => [
                                'year' => 1995
                            ],
                            'employees' => [
                                [
                                    'id' => 4,
                                    'name' => 'emp4',
                                    'position' => [
                                        'code_name' => 'pos1',
                                        'name' => 'pos1'
                                    ]
                                ],
                                [
                                    'id' => 5,
                                    'name' => 'emp2',
                                    'position' => 'pos2'
                                ]
                            ]
                        ],
                        [
                            'name' => 'dep22',
                            'code_name' => 'dep22',
                            'description' => 'test',
                            'other_information' => [
                                'year' => 1995
                            ],
                            'children' => [
                                [
                                    'name' => 'dep221',
                                    'code_name' => 'dep221',
                                    'description' => 'test',
                                    'other_information' => [
                                        'year' => 1995
                                    ],
                                ]
                            ]
                        ]
                    ],
                    'employees' => [
                        [
                            'id' => 3,
                            'name' => 'emp3',
                            'position' => [
                                'code_name' => 'pos1',
                                'name' => 'pos1'
                            ]
                        ],
                        [
                            'id' => 2,
                            'name' => 'emp2',
                            'position' => 'pos2'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function generateIncorrectStructures() {
        return [
            [
                [
                    'name' => 'dep1',
                    'code_name' => 'dep1',
                    'description' => 'test',
                    'other_information' => [
                        'year' => 1995
                    ],
                ],
                [
                    'structure' => [
                        [
                            'code_name' => 'dep1',
                            'description' => 'test',
                            'other_information' => [
                                'year' => 1995
                            ],
                        ]
                    ]
                ],
                [
                    'structure' => [
                        [
                            'name' => 'dep1',
                            'description' => 'test',
                            'other_information' => [
                                'year' => 1995
                            ],
                        ]
                    ]
                ],
            ]
        ];
    }

    public function generateCorrectDepartment() {
        return [
            'name' => 'depTest1',
            'code_name' => 'depTest1',
            'description' => 'test',
            'other_information' => [
                'year' => 1995
            ],
            'employees' => [['id' => $this->findCreatedEmployee()->id, 'position_id' => $this->findCreatedPosition()->id]]
        ];
    }

    public function generateIncorrectDepartment() {
        return [
            'code_name' => 'depTest1',
            'description' => 'test',
            'other_information' => [
                'year' => 1995
            ]
        ];
    }

    public function generateCorrectEmployee() {
        return [
            'name' => 'empTest1',
            'other_information' => [
                'test' => true
            ]
        ];
    }

    public function generateIncorrectEmployee() {
        return [
            'other_information' => [
                'year' => 1995
            ]
        ];
    }

    public function generateCorrectPosition() {
        return [
            'name' => 'posTest1',
            'code_name' => 'depTest1',
            'description' => 'test',
            'other_information' => [
                'year' => 1995
            ],
        ];
    }

    public function generateIncorrectPosition() {
        return [
            'code_name' => 'posTest1',
            'description' => 'test',
            'other_information' => [
                'year' => 1995
            ],
        ];
    }

    public function generateCorrectGroup() {
        return [
            'name' => 'groupTest1',
            'code_name' => 'groupTest1',
            'description' => 'test',
            'other_information' => [
                'year' => 1995
            ],
            'employees' => [['id' => $this->findCreatedEmployee()->id, 'position_id' => $this->findCreatedPosition()->id]],
        ];
    }

    public function generateIncorrectGroup() {
        return [
            'code_name' => 'groupTest1',
            'description' => 'test',
            'other_information' => [
                'year' => 1995
            ],
            'employees' => [['id' => $this->findCreatedEmployee()->id, 'position_id' => $this->findCreatedPosition()->id]],
        ];
    }

    public function makeRequest($method = 'POST', $id = false, $body = false, $entityType = 'department', $responseStatus = 200) {
        $url = '/api/' . $entityType;

        if ($id) {
            $methodName = 'findCreated' . ucfirst($entityType);
            $url .= '/' . $this->$methodName()->id;
        }

        $data = [];

        if ($body) {
            $methodName = 'generateCorrect';

            if ($responseStatus === 400) {
                $methodName = 'generateIncorrect';
            }

            $methodName .= ucfirst($entityType);

            $data = $this->$methodName();
        }

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'token' => 'test',
        ])->json($method, $url, $data);

        $response->assertStatus($responseStatus);
    }

    public function findCreatedEmployee() {
        return Employee::where(['name' => $this->generateCorrectEmployee()['name']])->firstOrFail();
    }

    public function findCreatedDepartment() {
        return Department::where(['name' => $this->generateCorrectDepartment()['name']])->firstOrFail();
    }

    public function findCreatedPosition() {
        return Position::where(['name' => $this->generateCorrectPosition()['name']])->firstOrFail();
    }

    public function findCreatedGroup() {
        return FunctionalGroup::where(['name' => $this->generateCorrectGroup()['name']])->firstOrFail();
    }

    public function removeStructure() {
        Department::truncate();
        Employee::truncate();
        Position::truncate();
        FunctionalGroup::truncate();
    }

    public function createTestUser() {
        User::updateOrCreate(['token' => 'test'], [
            'email' => 'test@test.test',
            'token' => 'test',
            'token_active_before' => now()->addHours(4)
        ]);
    }

    public function removeTestUser() {
        User::where(['token' => 'test'])->delete();
    }
}
