@extends('layouts.admin')
@section('title', 'Edit Pegawai')
@section('content')
<form action="{{ route('employees.update', $employee->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-warning card-outline">
                <div class="card-header"><h3 class="card-title">Data Akun & Status</h3></div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password Baru</label>
                        <input type="text" name="password" class="form-control" placeholder="Kosongkan jika tidak ganti">
                    </div>
                    <div class="form-group mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="staff" {{ $employee->role == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="manager" {{ $employee->role == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="director" {{ $employee->role == 'director' ? 'selected' : '' }}>Direktur</option>
                            <option value="admin" {{ $employee->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Status Pegawai</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $employee->is_active == 1 ? 'selected' : '' }}>Aktif (Bekerja)</option>
                            <option value="0" {{ $employee->is_active == 0 ? 'selected' : '' }}>Non-Aktif (Resign)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3"><label>NIP</label><input type="text" name="nip" class="form-control" value="{{ $employee->nip }}"></div>
                    <div class="form-group mb-3"><label>No HP</label><input type="text" name="phone" class="form-control" value="{{ $employee->phone }}"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-success card-outline">
                <div class="card-header"><h3 class="card-title">Penempatan & Gaji</h3></div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>Penempatan Kantor</label>
                        <select name="office_id" class="form-control" required>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" {{ $employee->office_id == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Departemen</label>
                        <select name="department_id" id="dept_select" class="form-control" required>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" {{ $employee->department_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Jabatan</label>
                        <select name="position_id" id="pos_select" class="form-control" required>
                            </select>
                    </div>

                    <hr>
                    <h6 class="text-success fw-bold">Pendapatan</h6>
                    <div class="form-group mb-2">
                        <label>Gaji Pokok</label>
                        <input type="number" name="basic_salary" class="form-control" value="{{ $employee->salary->basic_salary ?? 0 }}" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Tunjangan Jabatan</label>
                        <input type="number" name="position_allowance" class="form-control" value="{{ $employee->salary->position_allowance ?? 0 }}">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Uang Makan</label>
                            <input type="number" name="daily_meal_allowance" class="form-control" value="{{ $employee->salary->daily_meal_allowance ?? 0 }}">
                        </div>
                        <div class="col-6">
                            <label>Transport</label>
                            <input type="number" name="daily_transport_allowance" class="form-control" value="{{ $employee->salary->daily_transport_allowance ?? 0 }}">
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-danger fw-bold">Potongan Tetap (Bulanan)</h6>
                    <div class="row">
                        <div class="col-6">
                            <label>BPJS (Nominal Rp)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="bpjs" class="form-control" value="{{ $employee->salary->bpjs ?? 0 }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Pajak PPH21 (Persen %)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" name="tax" class="form-control" value="{{ $employee->salary->tax ?? 0 }}">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-warning">Update Data</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const allPositions = @json($positions);
        const deptSelect = document.getElementById('dept_select');
        const posSelect = document.getElementById('pos_select');
        const currentPosId = "{{ $employee->position_id }}";

        function renderPositions(deptId, selectedId = null) {
            posSelect.innerHTML = '<option value="">- Pilih Jabatan -</option>';
            if(deptId) {
                const filtered = allPositions.filter(p => p.department_id == deptId);
                filtered.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.id;
                    option.text = p.name;
                    if(p.id == selectedId) option.selected = true;
                    posSelect.appendChild(option);
                });
            }
        }
        renderPositions(deptSelect.value, currentPosId);
        deptSelect.addEventListener('change', function() {
            renderPositions(this.value);
        });
    });
</script>
@endsection