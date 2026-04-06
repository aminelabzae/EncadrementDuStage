<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectifStage extends Model
{
    use HasFactory;

    protected $table = 'objectif_stages';

    protected $fillable = [
        'stage_id',
        'libelle',
        'statut'
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
}