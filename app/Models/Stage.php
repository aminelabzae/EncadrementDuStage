<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $table = 'stages';

    protected $fillable = [
        'entreprise_id',
        'stagiaire_id',
        'type',
        'date_debut',
        'date_fin',
        'sujet',
        'statut',
        'encadrant_id'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class, 'stagiaire_id');
    }

    public function encadrant()
    {
        return $this->belongsTo(Encadrant::class, 'encadrant_id');
    }

    public function journalStages()
    {
        return $this->hasMany(JournalStage::class, 'stage_id');
    }

    public function objectifs()
    {
        return $this->hasMany(ObjectifStage::class, 'stage_id');
    }

    public function evaluations()
    {
        return $this->hasMany(EvaluationStage::class, 'stage_id');
    }

    public function visites()
    {
        return $this->hasMany(Visite::class, 'stage_id');
    }

    public function documents()
    {
        return $this->hasMany(DocumentStage::class, 'stage_id');
    }

    public function presences()
    {
        return $this->hasMany(PresenceStage::class, 'stage_id');
    }

    public function incidents()
    {
        return $this->hasMany(IncidentStage::class, 'stage_id');
    }

    public function attestations()
    {
        return $this->hasMany(AttestationStage::class, 'stage_id');
    }
}