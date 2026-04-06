<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visite extends Model
{
    use HasFactory;

    protected $table = 'visites';

    protected $fillable = [
        'stage_id',
        'date',
        'type',
        'compte_rendu'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
}