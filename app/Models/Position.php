<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // INI YANG HILANG TADI: Relasi ke Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
