@extends('layouts.admin')

@section('title', 'Ajukan Lembur')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Form Lembur</h3>
            </div>
            <form action="{{ route('overtime.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    <div class="form-group mb-3">
                        <label>Tanggal Lembur</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label>Jam Mulai</label>
                                <input type="time" name="start_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label>Jam Selesai</label>
                                <input type="time" name="end_time" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Alasan Lembur</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Contoh: Menyelesaikan laporan akhir tahun..." required></textarea>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Kirim Pengajuan
                    </button>
                    <a href="{{ route('overtime.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection