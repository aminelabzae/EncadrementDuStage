<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceJointe extends Model
{
    use HasFactory;

    protected $table = 'pieces_jointes';

    protected $fillable = [
        'objet_type',
        'objet_id',
        'nom_fichier',
        'chemin',
        'mime',
        'taille',
        'uploaded_by'
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function objet()
    {
        return $this->morphTo();
    }
}