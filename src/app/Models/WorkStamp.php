<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkStamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'stamp_at'
    ];

    public function work() {
        $this->belongsTo(Work::class);
    }
}
