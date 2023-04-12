<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions  = [
            "user_add",
            "user_edit",
            "user_delete",
            "user_view",

            "role_add",
            "role_edit",
            "role_delete",
            "role_view",

            "origin_add",
            "origin_edit",
            "origin_delete",
            "origin_view",

            "type_add",
            "type_edit",
            "type_delete",
            "type_view",

            "medicine_add",
            "medicine_edit",
            "medicine_delete",
            "medicine_view",

            "order_edit",
            "order_view",

            "banner_add",
            "banner_edit",
            "banner_delete",
            "banner_view",

        ];

        //create permission
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }

        $roles = ['admin', "local-seller"];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
            ]);
        }
    }
}
