@extends('dashboard')

@section('title', 'Modifier Entreprise')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Modifier l'Entreprise: {{ $entreprise->nom }}</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <div class="panel-title">Informations de l'Entreprise</div>
    </div>

    <form action="{{ route('entreprises.update', $entreprise) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Nom de l'entreprise</label>
                <input type="text" name="nom" value="{{ old('nom', $entreprise->nom) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('nom') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Pays</label>
                    <input type="text" name="pays" value="{{ old('pays', $entreprise->adresse->pays ?? 'Maroc') }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                    @error('pays') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Ville</label>
                    <input type="text" name="ville" value="{{ old('ville', $entreprise->adresse->ville ?? '') }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                    @error('ville') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Quartier</label>
                    <input type="text" name="quartier" value="{{ old('quartier', $entreprise->adresse->quartier ?? '') }}" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Code Postal</label>
                    <input type="text" name="code_postal" value="{{ old('code_postal', $entreprise->adresse->code_postal ?? '') }}" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Rue / Adresse</label>
                <textarea name="rue" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none; min-height: 80px;">{{ old('rue', $entreprise->adresse->rue ?? '') }}</textarea>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('entreprises.index') }}" class="btn-sm" style="display: flex; align-items: center; padding: 0.75rem 1.5rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">Enregistrer les modifications</button>
        </div>
    </form>
</div>
@endsection
