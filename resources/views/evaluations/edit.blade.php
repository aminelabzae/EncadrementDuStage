@extends('dashboard')

@section('title', 'Modifier Évaluation')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Modifier l'Évaluation</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('evaluations.update', $evaluation) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Stage</label>
                <div style="width: 100%; background: rgba(37,99,235,0.04); border: 1px solid var(--border); color: var(--text-muted); padding: 0.75rem; border-radius: 8px; cursor: not-allowed;">
                    {{ $evaluation->stage->stagiaire->utilisateur->name ?? 'N/A' }} - {{ $evaluation->stage->sujet }}
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Date de l'évaluation</label>
                <input type="date" name="date_evaluation" value="{{ old('date_evaluation', $evaluation->date_evaluation) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('date_evaluation') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Note / 20</label>
                <input type="number" step="0.25" min="0" max="20" name="note" value="{{ old('note', $evaluation->note) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('note') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Commentaires / Observations</label>
                <textarea name="commentaire" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none; min-height: 150px;">{{ old('commentaire', $evaluation->commentaire) }}</textarea>
                @error('commentaire') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('evaluations.index') }}" class="btn-sm" style="display: flex; align-items: center; padding: 0.75rem 1.5rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
