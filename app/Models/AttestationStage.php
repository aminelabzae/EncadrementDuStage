<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttestationStage extends Model
{
    use HasFactory;

    protected $table = 'attestation_stages';

    protected $fillable = [
        'stage_id',
        'numero',
        'date_emission',
        'piece_jointe_id'
    ];

    protected $casts = [
        'date_emission' => 'date',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function pieceJointe()
    {
        return $this->belongsTo(PieceJointe::class, 'piece_jointe_id');
    }
}