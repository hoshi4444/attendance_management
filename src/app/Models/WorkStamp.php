<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkStamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'stamp_at'
    ];

    public function work() {
        $this->belongsTo(Work::class);
    }
}
