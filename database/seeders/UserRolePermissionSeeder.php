<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;


class UserRolePermissionSeeder extends Seeder
{

    public function run(): void
    {
        $default_user_value= [
            'password' => Hash::make('password')
        ];

        $user = User::create(array_merge([
            'name' => 'user',
            'email'=> 'user@email.com',
            'role' => 'User',
        ], $default_user_value));

        $admin = User::create(array_merge([
            'name' => 'admin',
            'email'=> 'admin@email.com',
            'role' => 'Admin',
        ], $default_user_value));

        $mitra = User::create(array_merge([
            'name' => 'mitra',
            'email'=> 'mitra@email.com',
            'role' => 'Mitra',
        ], $default_user_value));

        $role_admin = Role::create(['name' => 'admin']);
        $role_user = Role::create(['name' => 'user']);
        $role_mitra = Role::create(['name' => 'mitra']);

        $permission = Permission::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'user']);
        $permission = Permission::create(['name' => 'mitra']);

        $role_admin->givePermissionTo('admin');
        $role_user->givePermissionTo('user');
        $role_mitra->givePermissionTo('mitra');

        $admin->assignRole('admin'); 
        $user->assignRole('user'); 
        $mitra->assignRole('mitra'); 

    }
}
