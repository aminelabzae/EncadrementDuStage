<?php
// app/Models/Stagiaire.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stagiaire extends Model
{
    use HasFactory;

    protected $table = 'stagiaires';

    protected $fillable = [
        'utilisateur_id',
        'ecole',
        'filiere',
        'niveau',
        'telephone'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function stages()
    {
        return $this->hasMany(Stage::class, 'stagiaire_id');
    }
}