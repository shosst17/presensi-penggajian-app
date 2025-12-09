<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;

    // KUNCI: Izinkan semua kolom diisi kecuali ID
    protected $guarded = ['id'];
}
