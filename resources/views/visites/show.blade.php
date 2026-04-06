@extends('dashboard')

@section('title', 'Détails Visite')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Détails de la Visite</div>

<div class="panel" style="max-width: 900px; margin: 0 auto;">
    <div class="panel-header" style="border-bottom: 1px solid var(--border); margin-bottom: 2rem; padding-bottom: 1rem;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="background: rgba(59,130,246,0.1); color: #3b82f6; width: 64px; height: 64px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 style="font-size: 1.25rem; margin-bottom: 0.25rem;">{{ $visite->type }}</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Prévue le {{ \Carbon\Carbon::parse($visite->date)->format('d/m/Y à H:i') }}</p>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('visites.edit', $visite) }}" class="btn-sm">Modifier</a>
            @if(!Auth::user()->hasRole('STAGIAIRE'))
<form action="{{ route('visites.destroy', $visite) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Annuler cette visite ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-sm" style="color: #ef4444;">Supprimer</button>
            </form>
@endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div>
                <h3 style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Intervenants</h3>
                <div style="background: rgba(37,99,235,0.04); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">Stagiaire</label>
                        <div style="font-weight: 500;">{{ $visite->stage->stagiaire->utilisateur->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">Entreprise</label>
                        <div style="font-weight: 500;">{{ $visite->stage->entreprise->nom ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">Encadrant (Entreprise)</label>
                        <div style="font-weight: 500;">{{ $visite->stage->encadrant->utilisateur->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Compte-rendu de visite</h3>
            <div style="background: rgba(37,99,235,0.04); border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem; min-height: 200px; line-height: 1.6; color: var(--text-main);">
                {!! nl2br(e($visite->compte_rendu)) ?: '<span style="color: var(--text-muted); font-style: italic;">Aucun compte-rendu saisi pour le moment.</span>' !!}
            </div>
        </div>
    </div>

    <div style="margin-top: 3rem; display: flex; justify-content: center;">
        <a href="{{ route('visites.index') }}" class="btn-sm" style="padding: 0.75rem 2.5rem;">Retour au planning</a>
    </div>
</div>
@endsection
