<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentStage extends Model
{
    use HasFactory;

    protected $table = 'document_stages';

    protected $fillable = [
        'stage_id',
        'type',
        'piece_jointe_id'
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