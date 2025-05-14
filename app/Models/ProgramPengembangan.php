<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramPengembangan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'program_pengembangans';
    protected $primaryKey = 'ProgramPengembanganID';

    protected $fillable = [
        'ProgramPengembanganID',
        'IsuID',
        'nama',
        'NA',
        'DCreated',
        'UCreated',
        'DEdited',
        'UEdited',
    ];

    public function isuStrategis()
    {
        return $this->belongsTo(IsuStrategis::class, 'IsuID');
    }
}
