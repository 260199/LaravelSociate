<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilar extends Model
{
    use HasFactory;
    protected $primaryKey = 'PilarID';
    public $timestamps = false;

    protected $fillable = [
        'RenstraID', 'nama', 'NA', 'DCreated', 'UCreated', 'DEdited', 'UEdited'
    ];



        public function isustrategis()
        {
            return $this->hasMany(IsuStrategis::class, 'PilarID');
        }
        public function renstras()
        {
            return $this->belongsTo(Renstra::class, 'RenstraID');
        }
        public function notifications()
        {
            return $this->morphMany(\App\Models\Notification::class, 'taggable');
        }
}
