<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $fillable = [
        'type',
        'message','message_admin',        
        'taggable_id',
        'taggable_type',
        'read',
        'user_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function taggable()
    {
        return $this->morphTo();
    }
    public function notifications()
{
    return $this->morphMany(Notification::class, 'taggable');
}

}