<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $data = Department::all();
        return view('departments.index', compact('data'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        // Hapus _token sebelum disimpan
        $data = $request->except(['_token']);

        Department::create($data);

        return redirect()->route('departments.index')->with('success', 'Departemen ditambahkan');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate(['name' => 'required']);

        // Hapus _token dan _method sebelum update
        $data = $request->except(['_token', '_method']);

        $department->update($data);

        return redirect()->route('departments.index')->with('success', 'Departemen diperbarui');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Departemen dihapus');
    }
}
