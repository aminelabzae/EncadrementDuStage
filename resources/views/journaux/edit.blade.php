@extends('dashboard')

@section('title', 'Modifier Journal')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Modifier l'entrée de journal</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('journaux.update', $journal->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Stage</label>
                <div style="width: 100%; background: rgba(37,99,235,0.04); border: 1px solid var(--border); color: var(--text-muted); padding: 0.75rem; border-radius: 8px; cursor: not-allowed;">
                    {{ $journal->stage->stagiaire->utilisateur->name ?? 'N/A' }} - {{ $journal->stage->sujet }}
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Date du rapport</label>
                <input type="date" name="date" value="{{ old('date', $journal->date->format('Y-m-d')) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('date') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Nombre d'heures</label>
                <input type="number" step="0.5" name="heures" value="{{ old('heures', $journal->heures) }}" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('heures') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Activités</label>
                <textarea name="activites" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none; min-height: 200px;">{{ old('activites', $journal->activites) }}</textarea>
                @error('activites') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('journaux.index') }}" class="btn-sm" style="display: flex; align-items: center; padding: 0.75rem 1.5rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
