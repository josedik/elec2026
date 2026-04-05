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

        Permission::create(['name' => 'admin','description'=>'View all'])->syncRoles([$role3]);

        Permission::create(['name' => 'admin.departments.index','description'=>'View departments'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.departments.create','description'=>'Create departments'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.departments.edit','description'=>'Edit departments'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.departments.destroy','description'=>'Delete departments'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.provinces.index','description'=>'View provinces'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.provinces.create','description'=>'Create provinces'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.provinces.edit','description'=>'Edit provinces'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.provinces.destroy','description'=>'Delete provinces'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.districts.index','description'=>'View districts'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.districts.create','description'=>'Create districts'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.districts.edit','description'=>'Edit districts'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.districts.destroy','description'=>'Delete districts'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.mesas.index','description'=>'View mesas'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.mesas.create','description'=>'Create mesas'])->syncRoles([$role1, $role3,]);
        Permission::create(['name' => 'admin.mesas.edit','description'=>'Edit mesas'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.mesas.destroy','description'=>'Delete mesas'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.mesas.show','description'=>'Show mesas'])->syncRoles([$role1, $role3, $role4]);

        Permission::create(['name' => 'admin.schools.index','description'=>'View schools'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.schools.create','description'=>'Create schools'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.schools.edit','description'=>'Edit schools'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.schools.destroy','description'=>'Delete schools'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.voters.index','description'=>'View voters'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.voters.create','description'=>'Create voters'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.voters.edit','description'=>'Edit voters'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.voters.destroy','description'=>'Delete voters'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.parties.index','description'=>'View parties'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.parties.create','description'=>'Create parties'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.parties.edit','description'=>'Edit parties'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.parties.destroy','description'=>'Delete parties'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.districtsparty.index','description'=>'View districts by party'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.districtsparty.create','description'=>'Create districts by party'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.districtsparty.edit','description'=>'Edit districts by party'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.districtsparty.show','description'=>'Show districts by party'])->syncRoles([$role1, $role3, $role4]);

        Permission::create(['name' => 'admin.candidates.index','description'=>'View candidates'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.candidates.create','description'=>'Create candidates'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.candidates.edit','description'=>'Edit candidates'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.candidates.destroy','description'=>'Delete candidates'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.reports.index','description'=>'View reports'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.reports.create','description'=>'Create reports'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.reports.edit','description'=>'Edit reports'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.reports.destroy','description'=>'Delete reports'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.muestreos.index','description'=>'View surveys'])->syncRoles([$role1, $role3,  $role4]);
        Permission::create(['name' => 'admin.muestreos.create','description'=>'Create surveys'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.muestreos.edit','description'=>'Edit surveys'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.muestreos.destroy','description'=>'Delete surveys'])->syncRoles([$role1, $role3]);

        Permission::create(['name' => 'admin.votos.index','description'=>'View votes'])->syncRoles([$role1, $role3, $role2, $role4]);
        Permission::create(['name' => 'admin.votos.create','description'=>'Create votes'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.votos.edit','description'=>'Edit votes'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.votos.destroy','description'=>'Delete votes'])->syncRoles([$role1, $role3]);
    }
}
