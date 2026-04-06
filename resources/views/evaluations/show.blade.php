@extends('dashboard')

@section('title', 'Détails Évaluation')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Évaluation de {{ $evaluation->stage->stagiaire->utilisateur->name }}</div>

<div class="panel" style="max-width: 900px; margin: 0 auto;">
    <div class="panel-header" style="border-bottom: 1px solid var(--border); margin-bottom: 2rem; padding-bottom: 1rem;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="background: {{ $evaluation->note >= 10 ? 'rgba(16,185,129,0.1)' : 'rgba(239, 68, 68, 0.1)' }}; 
                        color: {{ $evaluation->note >= 10 ? '#10b981' : '#ef4444' }}; 
                        width: 80px; height: 80px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; border: 1px solid {{ $evaluation->note >= 10 ? 'rgba(16,185,129,0.2)' : 'rgba(239, 68, 68, 0.2)' }};">
                {{ number_format($evaluation->note, 1) }}
            </div>
            <div>
                <h2 style="font-size: 1.25rem; margin-bottom: 0.25rem;">Note Finale</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Attribuée le {{ \Carbon\Carbon::parse($evaluation->date_evaluation)->format('d/m/Y') }}</p>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            @if(!Auth::user()->hasRole('STAGIAIRE'))
            <a href="{{ route('evaluations.edit', $evaluation) }}" class="btn-sm">Modifier</a>
            @endif
            @if(!Auth::user()->hasRole('STAGIAIRE'))
<form action="{{ route('evaluations.destroy', $evaluation) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Supprimer ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-sm" style="color: #ef4444;">Supprimer</button>
            </form>
@endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
        <div>
            <h3 style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Informations Stage</h3>
            <div style="background: rgba(37,99,235,0.04); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">Sujet</label>
                    <div style="font-weight: 500;">{{ $evaluation->stage->sujet }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">Entreprise</label>
                    <div style="font-weight: 500;">{{ $evaluation->stage->entreprise->nom ?? 'N/A' }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">Encadrant</label>
                    <div style="font-weight: 500;">{{ $evaluation->stage->encadrant->utilisateur->name ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div>
            <h3 style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Commentaires & Observations</h3>
            <div style="background: rgba(37,99,235,0.04); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; min-height: 140px; line-height: 1.6; color: rgba(255,255,255,0.9);">
                {!! nl2br(e($evaluation->commentaire)) ?: '<span style="color: var(--text-muted); font-style: italic;">Aucun commentaire.</span>' !!}
            </div>
        </div>
    </div>

    <div style="margin-top: 3rem; display: flex; justify-content: center;">
        <a href="{{ route('evaluations.index') }}" class="btn-sm" style="padding: 0.75rem 2.5rem;">Retour à la liste</a>
    </div>
</div>
@endsection
