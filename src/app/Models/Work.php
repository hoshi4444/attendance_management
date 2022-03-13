<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_type_id',
        'on_date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function work_type() {
        return $this->belongsTo(WorkType::class);
    }

    public function work_stamps() {
        return $this->hasMany(WorkStamp::class);
    }
}
