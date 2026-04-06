<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceStage extends Model
{
    use HasFactory;

    protected $table = 'presence_stages';

    protected $fillable = [
        'stage_id',
        'date',
        'present'
    ];

    protected $casts = [
        'date' => 'date',
        'present' => 'boolean',
    ];

    public $timestamps = false;

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
}