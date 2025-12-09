<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    // KUNCI PEMBUKA: Izinkan semua kolom diisi kecuali ID
    protected $guarded = ['id'];

    // Relasi ke User (Penerima Gaji)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
