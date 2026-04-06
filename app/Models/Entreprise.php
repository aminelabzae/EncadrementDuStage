<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $table = 'entreprises';

    protected $fillable = [
        'nom',
        'adresse_id'
    ];

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'adresse_id');
    }

    public function stages()
    {
        return $this->hasMany(Stage::class, 'entreprise_id');
    }

    public function encadrants()
    {
        return $this->hasMany(Encadrant::class, 'entreprise_id');
    }
}