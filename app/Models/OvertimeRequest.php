<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke User (Siapa yang mengajukan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Approver (Siapa yang menyetujui)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
