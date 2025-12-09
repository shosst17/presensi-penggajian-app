<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    // === SOLUSI ERROR MASS ASSIGNMENT ===
    // Kita izinkan semua kolom diisi, KECUALI kolom ID.
    protected $guarded = ['id'];

    // Relasi ke Peminjam
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Penyetuju (Manager/Direktur)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
