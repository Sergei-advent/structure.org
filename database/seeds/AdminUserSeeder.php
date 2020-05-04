<?php

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminUser::create([
           'name' => 'admin',
           'email' =>  env('ADMIN_EMAIL', 'admin@structure.org'),
           'password' => bcrypt('admin'),
        ]);
    }
}
