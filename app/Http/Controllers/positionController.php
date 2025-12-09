<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Department;

class PositionController extends Controller
{
    public function index()
    {
        $data = Position::with('department')->get();
        return view('positions.index', compact('data'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required',
            'base_salary_default' => 'required|numeric'
        ]);

        Position::create($request->all());
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    // === INI YANG KEMARIN KURANG ===
    public function edit($id)
    {
        $position = Position::findOrFail($id);
        $departments = Department::all(); // Kita butuh data departemen untuk dropdown
        return view('positions.edit', compact('position', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required',
            'base_salary_default' => 'required|numeric'
        ]);

        $position = Position::findOrFail($id);
        $position->update($request->all());

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui.');
    }
    // ===============================

    public function destroy($id)
    {
        Position::destroy($id);
        return back()->with('success', 'Jabatan dihapus.');
    }
}
