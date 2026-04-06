<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentStage extends Model
{
    use HasFactory;

    protected $table = 'incident_stages';

    protected $fillable = [
        'stage_id',
        'description',
        'date',
        'gravite'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
}