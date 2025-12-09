@extends('layouts.admin')

@section('title', 'Slip Gaji')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-dark text-white text-center">
                <h4>SLIP GAJI: {{ $payroll->month }}</h4>
            </div>
            <div class="card-body p-5">
                
                <div class="row mb-4">
                    <div class="col-6">
                        <h5><strong>{{ $payroll->user->name }}</strong></h5>
                        <p class="text-muted">{{ strtoupper($payroll->user->role) }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <h3 class="text-primary fw-bold">Rp {{ number_format($payroll->net_salary) }}</h3>
                        <span class="badge bg-success">LUNAS / PAID</span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-6">
                        <h6 class="text-muted border-bottom pb-2">PENDAPATAN</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Gaji Pokok</span>
                                <strong>Rp {{ number_format($payroll->basic_salary) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Tunjangan</span>
                                <strong>Rp {{ number_format($payroll->allowances) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Upah Lembur</span>
                                <strong>Rp {{ number_format($payroll->overtime_pay) }}</strong>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted border-bottom pb-2">POTONGAN</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between text-danger">
                                <span>Total Potongan</span>
                                <strong>- Rp {{ number_format($payroll->deductions) }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="card-footer text-center bg-white">
                <a href="{{ route('payroll.print', $payroll->id) }}" class="btn btn-outline-dark">
                    <i class="bi bi-printer"></i> Download PDF
                </a>
                <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection