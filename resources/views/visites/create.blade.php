@extends('dashboard')

@section('title', 'Planifier une Visite')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Planifier une Visite</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('visites.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div style="grid-column: span 2;">
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
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Date et Heure</label>
                <input type="datetime-local" name="date" value="{{ old('date', date('Y-m-d\TH:i')) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('date') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Type de visite</label>
                <select name="type" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                    <option value="Suivi" {{ old('type') == 'Suivi' ? 'selected' : '' }}>Visite de suivi</option>
                    <option value="Évaluation" {{ old('type') == 'Évaluation' ? 'selected' : '' }}>Visite d'évaluation</option>
                    <option value="Installation" {{ old('type') == 'Installation' ? 'selected' : '' }}>Visite d'installation</option>
                    <option value="Clôture" {{ old('type') == 'Clôture' ? 'selected' : '' }}>Visite de clôture</option>
                </select>
                @error('type') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Compte-rendu / Notes</label>
                <textarea name="compte_rendu" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none; min-height: 150px;" placeholder="Notes sur le déroulement de la visite...">{{ old('compte_rendu') }}</textarea>
                @error('compte_rendu') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('visites.index') }}" class="btn-sm" style="display: flex; align-items: center; padding: 0.75rem 1.5rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">Planifier</button>
        </div>
    </form>
</div>
@endsection
