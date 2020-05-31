<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    private $roles = [
        [
            'id' => 1,
            'name' => 'пользователь',
            'code_name' => 'user',
        ],
        [
            'id' => 2,
            'name' => 'менеджер',
            'code_name' => 'manager',
        ]
    ];

    private $permissions = [
        [
            'id' => 1,
            'code_name' => 'create',
        ],
        [
            'id' => 2,
            'code_name' => 'update',
        ],
        [
            'id' => 3,
            'code_name' => 'delete',
        ],
        [
            'id' => 4,
            'code_name' => 'upload',
        ],
    ];

    private $rolePermissions = [
        [
            'id' => 1,
            'role_id' => 2,
            'permission_id' => 1,
            'entity' => 'department'
        ],
        [
            'id' => 2,
            'role_id' => 2,
            'permission_id' => 2,
            'entity' => 'department'
        ],
        [
            'id' => 3,
            'role_id' => 2,
            'permission_id' => 3,
            'entity' => 'department'
        ],
        [
            'id' => 4,
            'role_id' => 2,
            'permission_id' => 1,
            'entity' => 'employee'
        ],
        [
            'id' => 5,
            'role_id' => 2,
            'permission_id' => 2,
            'entity' => 'employee'
        ],
        [
            'id' => 6,
            'role_id' => 2,
            'permission_id' => 3,
            'entity' => 'employee'
        ],
        [
            'id' => 7,
            'role_id' => 2,
            'permission_id' => 1,
            'entity' => 'position'
        ],
        [
            'id' => 8,
            'role_id' => 2,
            'permission_id' => 2,
            'entity' => 'position'
        ],
        [
            'id' => 9,
            'role_id' => 2,
            'permission_id' => 3,
            'entity' => 'position'
        ],
        [
            'id' => 10,
            'role_id' => 2,
            'permission_id' => 1,
            'entity' => 'functionalGroup'
        ],
        [
            'id' => 11,
            'role_id' => 2,
            'permission_id' => 2,
            'entity' => 'functionalGroup'
        ],
        [
            'id' => 12,
            'role_id' => 2,
            'permission_id' => 3,
            'entity' => 'functionalGroup'
        ],
        [
            'id' => 13,
            'role_id' => 2,
            'permission_id' => 4,
            'entity' => 'structure'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $role) {
            $roleExist = Role::where(['code_name' => $role['code_name']])->first();
            if (!$roleExist) {
                Role::updateOrCreate(['id' => $role['id']], $role);
            }
        }

        foreach ($this->permissions as $permission) {
            Permission::updateOrCreate(['id' => $permission['id']], $permission);
        }

        foreach ($this->rolePermissions as $rolePermission) {
            RolePermission::updateOrCreate(['id' => $rolePermission['id']], $rolePermission);
        }
    }
}
