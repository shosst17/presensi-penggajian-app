@extends('layouts.admin')
@section('title', 'Tambah Pegawai')
@section('content')
<form action="{{ route('employees.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">Data Akun & Status</h3></div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password Default</label>
                        <input type="text" name="password" class="form-control" value="12345678" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="staff">Staff</option>
                            <option value="manager">Manager</option>
                            <option value="director">Direktur</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Status Pegawai</label>
                        <select name="is_active" class="form-control">
                            <option value="1">Aktif (Bekerja)</option>
                            <option value="0">Non-Aktif (Resign)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3"><label>NIP</label><input type="text" name="nip" class="form-control"></div>
                    <div class="form-group mb-3"><label>No HP</label><input type="text" name="phone" class="form-control"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-success card-outline">
                <div class="card-header"><h3 class="card-title">Penempatan & Gaji</h3></div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>Penempatan Kantor <span class="text-danger">*</span></label>
                        <select name="office_id" class="form-control" required>
                            <option value="">- Pilih Kantor -</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Departemen</label>
                        <select name="department_id" id="dept_select" class="form-control" required>
                            <option value="">- Pilih Dept -</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Jabatan</label>
                        <select name="position_id" id="pos_select" class="form-control" required disabled>
                            <option value="">- Pilih Dept Dulu -</option>
                        </select>
                    </div>

                    <hr>
                    <h6 class="text-success fw-bold">Pendapatan</h6>
                    <div class="form-group mb-2">
                        <label>Gaji Pokok</label>
                        <input type="number" name="basic_salary" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Tunjangan Jabatan</label>
                        <input type="number" name="position_allowance" class="form-control" value="0">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Uang Makan/Hari</label>
                            <input type="number" name="daily_meal_allowance" class="form-control" value="0">
                        </div>
                        <div class="col-6">
                            <label>Transport/Hari</label>
                            <input type="number" name="daily_transport_allowance" class="form-control" value="0">
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-danger fw-bold">Potongan Tetap (Bulanan)</h6>
                    <div class="row">
                        <div class="col-6">
                            <label>BPJS (Nominal Rp)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="bpjs" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Pajak PPH21 (Persen %)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" name="tax" class="form-control" value="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
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
        const salaryInput = document.getElementsByName('basic_salary')[0];

        deptSelect.addEventListener('change', function() {
            const deptId = this.value;
            posSelect.innerHTML = '<option value="">- Pilih Jabatan -</option>';
            salaryInput.value = ''; 
            if(deptId) {
                const filtered = allPositions.filter(p => p.department_id == deptId);
                filtered.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.id;
                    option.text = p.name;
                    option.setAttribute('data-gaji', p.base_salary_default);
                    posSelect.appendChild(option);
                });
                posSelect.disabled = false;
            } else {
                posSelect.disabled = true;
            }
        });

        posSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const gaji = selectedOption.getAttribute('data-gaji');
            if(gaji) salaryInput.value = parseInt(gaji);
        });
    });
</script>
@endsection