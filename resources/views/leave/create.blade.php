@extends('layouts.admin')

@section('title', 'Ajukan Cuti/Izin')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Form Pengajuan</h3></div>
            <form action="{{ route('leave.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    
                    <div class="form-group mb-3">
                        <label>Jenis Pengajuan</label>
                        <select name="type" class="form-control" id="type_select">
                            <option value="izin">Izin (Potong Gaji/Tidak)</option>
                            <option value="sakit">Sakit (Wajib Surat Dokter)</option>
                            <option value="cuti_tahunan">Cuti Tahunan (Potong Kuota)</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label>Mulai Tanggal</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Alasan</label>
                        <textarea name="reason" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label>Lampiran (Surat Dokter/Bukti)</label>
                        <input type="file" name="attachment" class="form-control">
                        <small class="text-muted">Format: JPG/PNG, Max 2MB.</small>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                    <a href="{{ route('leave.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection