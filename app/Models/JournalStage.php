<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalStage extends Model
{
    use HasFactory;

    protected $table = 'journal_stages';

    protected $fillable = [
        'stage_id',
        'date',
        'activites',
        'heures'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
}