@extends('dashboard')

@section('title', 'Détails du Journal')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Journal de bord : {{ \Carbon\Carbon::parse($journal->date)->format('d/m/Y') }}</div>

<div class="panel" style="max-width: 900px; margin: 0 auto;">
    <div class="panel-header" style="border-bottom: 1px solid var(--border); margin-bottom: 2rem; padding-bottom: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <h2 style="font-size: 1.15rem; margin-bottom: 0.15rem;">Entrée du {{ \Carbon\Carbon::parse($journal->date)->format('l d F Y') }}</h2>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <p style="color: var(--text-muted); font-size: 0.85rem;">Stagiaire : {{ $journal->stage?->stagiaire?->utilisateur?->name ?? 'N/A' }}</p>
                    <span style="background: rgba(139, 92, 246, 0.2); color: #a78bfa; padding: 0.1rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">{{ $journal->heures ?? '0' }} heures</span>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            @if(!Auth::user()->hasRole('STAGIAIRE') || (Auth::user()->stagiaire && $journal->stage && $journal->stage->stagiaire_id === Auth::user()->stagiaire->id))
            <a href="{{ route('journaux.edit', $journal->id) }}" class="btn-sm">Modifier</a>
            @endif
            @if(!Auth::user()->hasRole('STAGIAIRE'))
<form action="{{ route('journaux.destroy', $journal->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Supprimer ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-sm" style="color: #ef4444;">Supprimer</button>
            </form>
@endif
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div>
            <h3 style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Activités réalisées</h3>
            <div style="background: rgba(37,99,235,0.04); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; line-height: 1.7; color: var(--text-main); font-size: 1rem;">
                {!! nl2br(e($journal->activites)) !!}
            </div>
        </div>


        <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: var(--text-muted);">
            <div>Stage chez : <strong>{{ $journal->stage->entreprise->nom ?? 'N/A' }}</strong></div>
            <a href="{{ route('stages.show', $journal->stage) }}" style="color: var(--accent);">Détails du stage →</a>
        </div>
    </div>

    <div style="margin-top: 3rem; display: flex; justify-content: center;">
        <a href="{{ route('journaux.index') }}" class="btn-sm" style="padding: 0.75rem 2.5rem;">Retour aux journaux</a>
    </div>
</div>
@endsection
