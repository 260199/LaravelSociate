<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsuStrategis extends Model
{
    use HasFactory;
    protected $primaryKey = 'IsuID';
    public $timestamps = false;
    protected $table = 'isustrategis';

    protected $fillable = [
        'PilarID',
        'nama',
        'NA',
        'DCreated',
        'UCreated',
        'DEdited',
        'UEdited',
    ];


    public function pilar()
    {
        return $this->belongsTo(Pilar::class, 'PilarID'); // <- pastikan PilarID dipakai
    }



    public function renstra()
    {
        return $this->belongsTo(Renstra::class, Pilar::class);
    }


    public function programpengembangan()
    {
        return $this->hasMany(ProgramPengembangan::class, 'IsuID', 'IsuID');
    }

}
