<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    use HasFactory;

    protected $table = 'adresses';

    protected $fillable = [
        'pays',
        'ville',
        'quartier',
        'rue',
        'code_postal',
        'latitude',
        'longitude'
    ];

    public function entreprise()
    {
        return $this->hasOne(Entreprise::class, 'adresse_id');
    }
}