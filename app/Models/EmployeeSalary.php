<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    use HasFactory;

    // KUNCI SAKTINYA ADA DI SINI:
    // Kita izinkan SEMUA kolom (termasuk bpjs & tax) untuk diisi.
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
