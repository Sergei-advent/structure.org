<?php

use App\Models\Role;
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
    }
}
