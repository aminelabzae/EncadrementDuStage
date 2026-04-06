@extends('dashboard')

@section('title', 'Détails du Stage')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Détails du Stage : {{ $stage->sujet }}</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <!-- Informations Générales -->
    <div class="panel">
        <div class="panel-header" style="border-bottom: 1px solid var(--border); margin-bottom: 1rem; padding-bottom: 0.5rem;">
            <div class="panel-title">Informations Générales</div>
            <a href="{{ route('stages.edit', $stage) }}" class="btn-sm">Modifier</a>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <label style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Sujet / Projet</label>
                <div style="font-size: 1.1rem; font-weight: 600;">{{ $stage->sujet }}</div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Type</label>
                    <div style="font-weight: 500;">{{ $stage->type }}</div>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Statut</label>
                    <div>
                        <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 100px;
                            background: {{ $stage->statut === 'en_cours' ? 'rgba(59,130,246,0.1)' : ($stage->statut === 'termine' ? 'rgba(16,185,129,0.1)' : 'rgba(107,114,128,0.1)') }};
                            color: {{ $stage->statut === 'en_cours' ? '#3b82f6' : ($stage->statut === 'termine' ? '#10b981' : '#9ca3af') }};
                            border: 1px solid {{ $stage->statut === 'en_cours' ? 'rgba(59,130,246,0.2)' : ($stage->statut === 'termine' ? 'rgba(16,185,129,0.2)' : 'rgba(107,114,128,0.2)') }};
                        ">
                            {{ ucfirst(str_replace('_', ' ', $stage->statut ?? 'Inconnu')) }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Date Début</label>
                    <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}</div>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Date Fin</label>
                    <div style="font-weight: 500;">{{ $stage->date_fin ? \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') : 'Non définie' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acteurs -->
    <div class="panel">
        <div class="panel-header" style="border-bottom: 1px solid var(--border); margin-bottom: 1rem; padding-bottom: 0.5rem;">
            <div class="panel-title">Acteurs du Stage</div>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="avatar" style="width: 48px; height: 48px; background: rgba(59,130,246,0.1); color: #3b82f6;">
                    {{ substr($stage->stagiaire->utilisateur->name ?? 'S', 0, 1) }}
                </div>
                <div>
                    <label style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Stagiaire</label>
                    <div style="font-weight: 600;">{{ $stage->stagiaire->utilisateur->name ?? 'N/A' }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $stage->stagiaire->filiere ?? '' }} - {{ $stage->stagiaire->niveau_etudes ?? '' }}</div>
                </div>
                <a href="{{ route('stagiaires.show', $stage->stagiaire) }}" style="margin-left: auto; color: var(--accent); font-size: 0.8rem;">Profil →</a>
            </div>

            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="avatar" style="width: 48px; height: 48px; background: rgba(139,92,246,0.1); color: #8b5cf6;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <label style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Entreprise</label>
                    <div style="font-weight: 600;">{{ $stage->entreprise->nom ?? 'N/A' }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $stage->entreprise->adresse->ville ?? '' }}</div>
                </div>
                <a href="{{ route('entreprises.show', $stage->entreprise) }}" style="margin-left: auto; color: var(--accent); font-size: 0.8rem;">Fiche →</a>
            </div>

            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="avatar" style="width: 48px; height: 48px; background: rgba(16,185,129,0.1); color: #10b981;">
                    {{ substr($stage->encadrant->utilisateur->name ?? 'E', 0, 1) }}
                </div>
                <div>
                    <label style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Encadrant Entreprise</label>
                    <div style="font-weight: 600;">{{ $stage->encadrant->utilisateur->name ?? 'N/A' }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $stage->encadrant->poste ?? 'Responsable' }}</div>
                </div>
                <a href="{{ route('encadrants.show', $stage->encadrant) }}" style="margin-left: auto; color: var(--accent); font-size: 0.8rem;">Profil →</a>
            </div>
        </div>
    </div>
</div>

<!-- Rapports & Suivi -->
<div style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <div class="panel">
        <div class="panel-header" style="border-bottom: 1px solid var(--border); margin-bottom: 1rem; padding-bottom: 0.5rem;">
            <div class="panel-title">Dernières Évaluations</div>
            <a href="{{ route('evaluations.create', ['stage_id' => $stage->id]) }}" class="btn-sm">+ Note</a>
        </div>
        @forelse($stage->evaluations as $eval)
            <div style="padding: 0.75rem; background: rgba(37,99,235,0.03); border-radius: 8px; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight: 600; color: {{ $eval->note >= 10 ? '#10b981' : '#ef4444' }};">{{ $eval->note }}/20</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($eval->date_evaluation)->format('d/m/Y') }}</div>
                </div>
                <div style="font-size: 0.85rem; flex-grow: 1; margin-left: 1rem; color: var(--text-muted);">{{ Str::limit($eval->commentaire, 50) }}</div>
            </div>
        @empty
            <p style="color: var(--text-muted); font-size: 0.9rem; text-align: center; padding: 1rem;">Aucune évaluation pour le moment.</p>
        @endforelse
    </div>

    <div class="panel">
        <div class="panel-header" style="border-bottom: 1px solid var(--border); margin-bottom: 1rem; padding-bottom: 0.5rem;">
            <div class="panel-title">Prochaines Visites</div>
            <a href="{{ route('visites.create', ['stage_id' => $stage->id]) }}" class="btn-sm">+ Visite</a>
        </div>
        @forelse($stage->visites as $visite)
            <div style="padding: 0.75rem; background: rgba(37,99,235,0.03); border-radius: 8px; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="background: rgba(245,158,11,0.1); color: #f59e0b; padding: 0.4rem; border-radius: 6px;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div style="font-weight: 500; font-size: 0.9rem;">{{ $visite->type }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($visite->date)->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        @empty
            <p style="color: var(--text-muted); font-size: 0.9rem; text-align: center; padding: 1rem;">Aucune visite enregistrée.</p>
        @endforelse
    </div>
</div>

<div class="panel" style="margin-top: 1.5rem;">
    <div class="panel-header" style="border-bottom: 1px solid var(--border); margin-bottom: 1rem; padding-bottom: 0.5rem;">
        <div class="panel-title">Journal de Bord</div>
        <a href="{{ route('journaux.create', ['stage_id' => $stage->id]) }}" class="btn-sm">+ Entrée</a>
    </div>
    @forelse($stage->journalStages as $journal)
        <div style="padding: 1rem; border-bottom: 1px solid rgba(37,99,235,0.1);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <div style="font-weight: 600; color: var(--accent);">Semaine du {{ \Carbon\Carbon::parse($journal->date)->format('d/m/Y') }}</div>
                @if($journal->heures)
                    <span style="font-size: 0.75rem; background: rgba(37,99,235,0.08); color: var(--accent); padding: 0.1rem 0.5rem; border-radius: 4px;">{{ $journal->heures }}h</span>
                @endif
            </div>
            <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-main);">{{ $journal->activites }}</p>
        </div>
    @empty
        <p style="color: var(--text-muted); font-size: 0.9rem; text-align: center; padding: 2rem;">Le journal de bord est actuellement vide.</p>
    @endforelse
</div>

<div class="panel" style="margin-top: 1.5rem;">
    <div class="panel-header" style="border-bottom: 1px solid var(--border); margin-bottom: 1rem; padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <div class="panel-title">Documents Officiels</div>
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px; color: var(--accent);"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </div>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('stages.report', $stage) }}" class="btn-sm" style="display: flex; align-items: center; gap: 0.5rem; background: #f0f6ff; border: 1px solid #bfdbfe; color: #1e3a5f; padding: 0.75rem 1.25rem;">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Télécharger le Rapport d'Évaluation (PDF)
        </a>

        @if($stage->statut === 'termine' || Auth::user()->hasRole('ADMIN'))
        <a href="{{ route('stages.attestation', $stage) }}" class="btn-sm" style="display: flex; align-items: center; gap: 0.5rem; background: var(--accent); border: none; color: white; padding: 0.75rem 1.25rem;">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138z"/></svg>
            Générer l'Attestation de Stage (PDF)
        </a>
        @endif
    </div>
    <div style="margin-top: 0.75rem; font-size: 0.75rem; color: var(--text-muted);">
        * Ces documents sont générés automatiquement à partir des données saisies dans le système.
    </div>
</div>

<div style="margin-top: 2rem; display: flex; justify-content: center;">
    <a href="{{ route('stages.index') }}" class="btn-sm" style="padding: 0.75rem 2rem;">Retour à la liste</a>
</div>
@endsection
