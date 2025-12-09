<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficeLocation;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = OfficeLocation::all();
        return view('office.index', compact('offices'));
    }

    public function create()
    {
        return view('office.create');
    }

    public function store(Request $request)
    {
        // Validasi SEMUA field
        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius_meters' => 'required|numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'entry_grace_minutes' => 'required|numeric|min:0', // Toleransi Masuk
            'exit_grace_minutes' => 'required|numeric|min:0',  // Toleransi Pulang
            'min_overtime_minutes' => 'required|numeric|min:0', // Min Lembur
            'max_overtime_minutes' => 'required|numeric|min:0', // Max Lembur
        ]);

        OfficeLocation::create($request->except(['_token']));
        return redirect()->route('office.index')->with('success', 'Lokasi & Aturan berhasil disimpan.');
    }

    public function edit($id)
    {
        $office = OfficeLocation::findOrFail($id);
        return view('office.edit', compact('office'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius_meters' => 'required|numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'entry_grace_minutes' => 'required|numeric|min:0',
            'exit_grace_minutes' => 'required|numeric|min:0',
            'min_overtime_minutes' => 'required|numeric|min:0',
            'max_overtime_minutes' => 'required|numeric|min:0',
        ]);

        $office = OfficeLocation::findOrFail($id);
        $office->update($request->except(['_token', '_method']));

        return redirect()->route('office.index')->with('success', 'Lokasi & Aturan diperbarui.');
    }

    public function destroy($id)
    {
        OfficeLocation::destroy($id);
        return back()->with('success', 'Lokasi dihapus.');
    }
}
