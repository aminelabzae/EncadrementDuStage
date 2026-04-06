<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationStage extends Model
{
    use HasFactory;

    protected $table = 'evaluation_stages';

    protected $fillable = [
        'stage_id',
        'note',
        'commentaire',
        'date_evaluation'
    ];

    protected $casts = [
        'date_evaluation' => 'date',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
}