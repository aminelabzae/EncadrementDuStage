@extends('dashboard')

@section('title', 'Nouvelle Évaluation')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Ajouter une Évaluation</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('evaluations.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Stage à évaluer</label>
                <select name="stage_id" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                    <option value="">Sélectionner un stage</option>
                    @foreach($stages as $stage)
                        <option value="{{ $stage->id }}" {{ (isset($selected_stage) && $selected_stage->id == $stage->id) || old('stage_id') == $stage->id ? 'selected' : '' }}>
                            {{ $stage->stagiaire->utilisateur->name ?? 'Stagiaire #'.$stage->stagiaire_id }} - {{ $stage->sujet }}
                        </option>
                    @endforeach
                </select>
                @error('stage_id') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Date de l'évaluation</label>
                <input type="date" name="date_evaluation" value="{{ old('date_evaluation', date('Y-m-d')) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('date_evaluation') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Note / 20</label>
                <input type="number" step="0.25" min="0" max="20" name="note" value="{{ old('note') }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;" placeholder="Ex: 15.5">
                @error('note') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Commentaires / Observations</label>
                <textarea name="commentaire" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none; min-height: 150px;" placeholder="Détails de l'évaluation...">{{ old('commentaire') }}</textarea>
                @error('commentaire') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('evaluations.index') }}" class="btn-sm" style="display: flex; align-items: center; padding: 0.75rem 1.5rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">Enregistrer l'évaluation</button>
        </div>
    </form>
</div>
@endsection
