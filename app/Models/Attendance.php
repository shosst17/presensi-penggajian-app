<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // KUNCI SAKTINYA DI SINI:
    // Kita izinkan semua kolom diisi, KECUALI kolom 'id'.
    protected $guarded = ['id'];

    // Relasi balik ke User (Opsional tapi bagus untuk nanti)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
