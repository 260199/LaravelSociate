<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jekeg extends Model
{
    use HasFactory;
    /**
     * @var array
     */
    protected $fillable = ['kegiatan', 'DCreated', 'UCreated', 'DEdited', 'UEdited'];
    public function daily()
    {
        return $this->belongsTo(Daily::class);
    }
  
    public function user()
    {
        return $this->belongsTo(User::class, 'UCreated');
    }
    
}
