@extends('dashboard')

@section('title', 'Modifier Encadrant')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Modifier l'Encadrant: {{ $encadrant->utilisateur->prenom }} {{ $encadrant->utilisateur->nom }}</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <div class="panel-title">Informations de l'Encadrant</div>
    </div>

    <form action="{{ route('encadrants.update', $encadrant) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- User Info -->
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom', $encadrant->utilisateur->prenom) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('prenom') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Nom</label>
                <input type="text" name="nom" value="{{ old('nom', $encadrant->utilisateur->nom) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('nom') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Email</label>
                <input type="email" name="email" value="{{ old('email', $encadrant->utilisateur->email) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('email') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Professional Info -->
            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Entreprise</label>
                <select name="entreprise_id" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                    @foreach($entreprises as $entreprise)
                        <option value="{{ $entreprise->id }}" {{ old('entreprise_id', $encadrant->entreprise_id) == $entreprise->id ? 'selected' : '' }}>{{ $entreprise->nom }}</option>
                    @endforeach
                </select>
                @error('entreprise_id') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Poste / Fonction</label>
                <input type="text" name="poste" value="{{ old('poste', $encadrant->poste) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('poste') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $encadrant->telephone ?? $encadrant->utilisateur->telephone) }}" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('telephone') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('encadrants.index') }}" class="btn-sm" style="display: flex; align-items: center; padding: 0.75rem 1.5rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">Enregistrer les modifications</button>
        </div>
    </form>
</div>
@endsection
