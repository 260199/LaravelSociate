<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'kegiatan',
        'jekeg_id',
        'deskripsi',
        'status',
        'done_at',
        'created_at'
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

public function filedailies()
{
    return $this->hasMany(Filedaily::class, 'daily_id');
}
public function notifications()
{
    return $this->morphMany(\App\Models\Notification::class, 'taggable');
}
public function jekeg()
{
    return $this->belongsTo(jekeg::class);
}
}
