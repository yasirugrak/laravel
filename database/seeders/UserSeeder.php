<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
        'name' => "Ayşe Yıldız",
        'email' => 'admin@demo.net',
            'password' => Hash::make('1234567')
        ]);

        $role_admin = Role::create(['name' => 'ROLE_ADMIN']);

        $permissions = Permission::pluck('id','id')->all();

        $role_admin->syncPermissions($permissions);

        $admin->assignRole([$role_admin->id]);


        

        $user = User::create([
        'name' => "Ahmet Yıldız",
        'email' => 'demo@demo.net',
            'password' => Hash::make('1234567')
        ]);

        $role_user = Role::create(['name' => 'ROLE_USER']);

        // $permissions = Permission::pluck('id','id')->all();

        $role_user->syncPermissions($permissions);

        $user->assignRole([$role_user->id]);


    }
}
