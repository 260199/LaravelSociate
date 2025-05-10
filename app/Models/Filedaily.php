<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filedaily extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_id',
        'image_path',
        'desc'
    ];

    public function daily()
    {
        return $this->belongsTo(Daily::class);
    }
}
