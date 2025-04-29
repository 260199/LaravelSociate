<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renstra extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'RenstraID';
    protected $fillable = [
        'nama',
        'periodemulai',
        'periodeselesai',
        'na',
        'DCreated',
        'UCreated',
    ];

    public function pilars()
    {
        return $this->hasMany(Pilar::class, 'RenstraID');
    }

    public function notifications()
    {
        return $this->morphMany(\App\Models\Notification::class, 'taggable');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'UCreated', 'id');
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'UCreated');
    }
}
