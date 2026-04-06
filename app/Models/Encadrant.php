<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encadrant extends Model
{
    use HasFactory;

    protected $table = 'encadrants';

    protected $fillable = [
        'utilisateur_id',
        'entreprise_id',
        'poste',
        'telephone'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function stages()
    {
        return $this->hasMany(Stage::class, 'encadrant_id');
    }
}