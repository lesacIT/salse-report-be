<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\LocalProvinceModel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::insert([
        //     'name' => 'admin',
        //     'username' => 'admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('123456789'), // Mật khẩu mặc định là 'password'
        // ]);

        $a=[
        ['Role.index','api'],
        ['Role.store','api'],
        ['Role.show','api'],
        ['Role.edit','api'],
        ['Role.update','api'],
        ['Role.destroy','api'],
        ['AddPermissions.usersHaveRoles','api'],
        ['AddPermissions.postusersHaveRoles','api'],
        ['AddPermissions.postusersHaveRoles','api'],
        ['AddPermissions.RolesHavePermission','api'],
        ['AddPermissions.postRolesHavePermission','api'],
        ['AddPermissions.postusersHaveRoles','api'],
        ];
        foreach ($a as $permission) {
          $name = $permission[0]; // Permission name
          $guard_name = $permission[1]; // Guard name

          // Check if the permission already exists
          $existingPermission = Permission::where('name', $name)->where('guard_name', $guard_name)->first();

          if (!$existingPermission) {
              // Create the permission if it doesn't exist
              Permission::create([
                  'name' => $name,
                  'guard_name' => $guard_name,
              ]);
            }
            }




       
    }
}
