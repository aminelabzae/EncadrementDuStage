<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'utilisateur_id',
        'type',
        'titre',
        'message',
        'lu_at',
        'canal',
        'meta'
    ];

    protected $casts = [
        'lu_at' => 'datetime',
        'meta' => 'array'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}