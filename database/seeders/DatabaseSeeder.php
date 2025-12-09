<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\OfficeLocation;
use App\Models\EmployeeSalary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. KANTOR
        $office = OfficeLocation::create([
            'name' => 'Kantor Pusat',
            'address' => 'Jl. Jendral Sudirman No. 1',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'radius_meters' => 50000,
        ]);

        // 2. DEPARTEMEN
        $deptIT = Department::create(['name' => 'IT Development']);
        $deptHR = Department::create(['name' => 'Human Resources']);
        $deptFin = Department::create(['name' => 'Finance & Tax']);

        // 3. JABATAN (Relasi ke Dept)
        // Dept IT
        $posItMgr = Position::create(['department_id' => $deptIT->id, 'name' => 'IT Manager', 'base_salary_default' => 15000000]);
        $posItDev = Position::create(['department_id' => $deptIT->id, 'name' => 'Backend Developer', 'base_salary_default' => 8000000]);

        // Dept HR
        $posHrMgr = Position::create(['department_id' => $deptHR->id, 'name' => 'HR Manager', 'base_salary_default' => 12000000]);
        $posHrStf = Position::create(['department_id' => $deptHR->id, 'name' => 'Recruitment Staff', 'base_salary_default' => 6000000]);

        // 4. USER (AKTOR)

        // ADMIN (Tidak butuh jabatan spesifik)
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'office_id' => $office->id,
        ]);
        EmployeeSalary::create(['user_id' => $admin->id]);

        // MANAGER IT (Atasan)
        $mgrIT = User::create([
            'name' => 'Pak Budi (Manager IT)',
            'email' => 'manager.it@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'manager',
            'office_id' => $office->id,
            'department_id' => $deptIT->id, // Dept IT
            'position_id' => $posItMgr->id, // Jabatan IT Manager
        ]);
        EmployeeSalary::create(['user_id' => $mgrIT->id, 'basic_salary' => 15000000]);

        // STAFF IT (Bawahan)
        $stfIT = User::create([
            'name' => 'Andi (Programmer)',
            'email' => 'staff.it@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'staff',
            'office_id' => $office->id,
            'department_id' => $deptIT->id, // Dept IT (Sama dengan Manager)
            'position_id' => $posItDev->id, // Jabatan Developer
        ]);
        EmployeeSalary::create(['user_id' => $stfIT->id, 'basic_salary' => 8000000]);
    }
}
