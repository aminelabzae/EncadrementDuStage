<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalActivite extends Model
{
    use HasFactory;

    protected $table = 'journal_activites';

    protected $fillable = [
        'utilisateur_id',
        'action',
        'objet_type',
        'objet_id',
        'ip',
        'user_agent',
        'donnees_avant',
        'donnees_apres'
    ];

    protected $casts = [
        'donnees_avant' => 'array',
        'donnees_apres' => 'array'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function objet()
    {
        return $this->morphTo();
    }
}