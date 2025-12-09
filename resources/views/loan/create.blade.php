@extends('layouts.admin')

@section('title', 'Ajukan Kasbon')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-warning card-outline">
            <div class="card-header"><h3 class="card-title">Form Peminjaman</h3></div>
            <form action="{{ route('loan.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Maksimal cicilan adalah <strong>30%</strong> dari Gaji Pokok.
                    </div>

                    <div class="form-group mb-3">
                        <label>Nominal Pinjaman (Rp)</label>
                        <input type="number" name="amount" class="form-control" placeholder="Contoh: 1000000" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Tenor (Bulan)</label>
                        <select name="installments" class="form-control">
                            <option value="1">1 Bulan</option>
                            <option value="2">2 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="6">6 Bulan</option>
                            <option value="12">12 Bulan</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Alasan</label>
                        <textarea name="reason" class="form-control" rows="2" required></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Ajukan</button>
                    <a href="{{ route('loan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection