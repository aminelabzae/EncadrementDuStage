@extends('dashboard')

@section('title', 'Nouveau Journal')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Rédiger une entrée de journal</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('journaux.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Stage concerné</label>
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
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Date du rapport</label>
                <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('date') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Nombre d'heures</label>
                <input type="number" step="0.5" name="heures" value="{{ old('heures') }}" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('heures') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Activités (Résumé de la période)</label>
                <textarea name="activites" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none; min-height: 200px;" placeholder="Décrivez vos réalisations...">{{ old('activites') }}</textarea>
                @error('activites') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('journaux.index') }}" class="btn-sm" style="display: flex; align-items: center; padding: 0.75rem 1.5rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">Publier le journal</button>
        </div>
    </form>
</div>
@endsection
