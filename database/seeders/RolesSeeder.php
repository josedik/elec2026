<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(attributes: ['name' => 'Admin', 'guard_name' => 'web']);
        $role2 = Role::create(['name' => 'User', 'guard_name' => 'web']);
        $role3 = Role::create(['name' => 'mainAdmin', 'guard_name' => 'web']);
        $role4 = Role::create(['name' => 'Klerk', 'guard_name' => 'web']);

        Permission::create(['name' => 'admin'])->syncRoles([$role3]);

        Permission::create(['name' => 'admin.departments.index'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.departments.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.departments.edit'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.departments.destroy'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.provinces.index'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.provinces.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.provinces.edit'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.provinces.destroy'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.districts.index'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.districts.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.districts.edit'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.districts.destroy'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.mesas.index'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.mesas.create'])->syncRoles([$role1, $role3,]);
        Permission::create(['name' => 'admin.mesas.edit'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.mesas.destroy'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.mesas.show'])->syncRoles([$role1, $role3, $role4]);

        Permission::create(['name' => 'admin.schools.index'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.schools.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.schools.edit'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.schools.destroy'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.voters.index'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.voters.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.voters.edit'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.voters.destroy'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.parties.index'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.parties.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.parties.edit'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.parties.destroy'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.districtsparty.index'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.districtsparty.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.districtsparty.edit'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.districtsparty.show'])->syncRoles([$role1, $role3, $role4]);
        
        Permission::create(['name' => 'admin.candidates.index'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.candidates.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.candidates.edit'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.candidates.destroy'])->syncRoles([$role1, $role3]);

    }
}
