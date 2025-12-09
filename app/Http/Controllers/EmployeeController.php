<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\EmployeeSalary;
use App\Models\OfficeLocation;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', '!=', 'admin')
            ->with(['department', 'position', 'office'])
            ->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $offices = OfficeLocation::all();
        return view('employees.create', compact('departments', 'positions', 'offices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'office_id' => 'required',
            'basic_salary' => 'required|numeric',
            'is_active' => 'required|boolean',
            // BPJS & Tax nullable (boleh kosong/0)
            'bpjs' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'office_id' => $request->office_id,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
            'must_change_password' => true
        ]);

        EmployeeSalary::create([
            'user_id' => $user->id,
            'basic_salary' => $request->basic_salary,
            'position_allowance' => $request->position_allowance ?? 0,
            'daily_transport_allowance' => $request->daily_transport_allowance ?? 0,
            'daily_meal_allowance' => $request->daily_meal_allowance ?? 0,
            // SIMPAN DATA POTONGAN
            'bpjs' => $request->bpjs ?? 0, // Nominal
            'tax'  => $request->tax ?? 0,  // Persen
        ]);

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function edit($id)
    {
        $employee = User::with('salary')->findOrFail($id);
        $departments = Department::all();
        $positions = Position::all();
        $offices = OfficeLocation::all();
        return view('employees.edit', compact('employee', 'departments', 'positions', 'offices'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'office_id' => 'required',
            'is_active' => 'required|boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'office_id' => $request->office_id,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        EmployeeSalary::updateOrCreate(
            ['user_id' => $user->id],
            [
                'basic_salary' => $request->basic_salary,
                'position_allowance' => $request->position_allowance ?? 0,
                'daily_transport_allowance' => $request->daily_transport_allowance ?? 0,
                'daily_meal_allowance' => $request->daily_meal_allowance ?? 0,
                // UPDATE DATA POTONGAN
                'bpjs' => $request->bpjs ?? 0,
                'tax'  => $request->tax ?? 0,
            ]
        );

        return redirect()->route('employees.index')->with('success', 'Data pegawai diperbarui');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'Pegawai dihapus');
    }
}
