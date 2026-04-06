@extends('dashboard')

@section('title', 'Modifier Visite')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Modifier la Visite</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('visites.update', $visite) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Stage</label>
                <div style="width: 100%; background: rgba(37,99,235,0.04); border: 1px solid var(--border); color: var(--text-muted); padding: 0.75rem; border-radius: 8px; cursor: not-allowed;">
                    {{ $visite->stage->stagiaire->utilisateur->name ?? 'N/A' }} - {{ $visite->stage->sujet }}
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Date et Heure</label>
                <input type="datetime-local" name="date" value="{{ old('date', \Carbon\Carbon::parse($visite->date)->format('Y-m-d\TH:i')) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('date') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Type de visite</label>
                <select name="type" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                    <option value="Suivi" {{ old('type', $visite->type) == 'Suivi' ? 'selected' : '' }}>Visite de suivi</option>
                    <option value="Évaluation" {{ old('type', $visite->type) == 'Évaluation' ? 'selected' : '' }}>Visite d'évaluation</option>
                    <option value="Installation" {{ old('type', $visite->type) == 'Installation' ? 'selected' : '' }}>Visite d'installation</option>
                    <option value="Clôture" {{ old('type', $visite->type) == 'Clôture' ? 'selected' : '' }}>Visite de clôture</option>
                </select>
                @error('type') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Compte-rendu / Notes</label>
                <textarea name="compte_rendu" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none; min-height: 150px;">{{ old('compte_rendu', $visite->compte_rendu) }}</textarea>
                @error('compte_rendu') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('visites.index') }}" class="btn-sm" style="display: flex; align-items: center; padding: 0.75rem 1.5rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">Enregistrer les modifications</button>
        </div>
    </form>
</div>
@endsection
