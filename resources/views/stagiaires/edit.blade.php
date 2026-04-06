@extends('dashboard')

@section('title', 'Modifier Stagiaire')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Modifier le Stagiaire: {{ $stagiaire->utilisateur->prenom }} {{ $stagiaire->utilisateur->nom }}</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <div class="panel-title">Informations du Stagiaire</div>
    </div>

    <form action="{{ route('stagiaires.update', $stagiaire) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Personal Info -->
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom', $stagiaire->utilisateur->prenom) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('prenom') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Nom</label>
                <input type="text" name="nom" value="{{ old('nom', $stagiaire->utilisateur->nom) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('nom') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Email</label>
                <input type="email" name="email" value="{{ old('email', $stagiaire->utilisateur->email) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('email') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $stagiaire->telephone ?? $stagiaire->utilisateur->telephone) }}" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('telephone') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <!-- Academic Info -->
            <div style="grid-column: span 2; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                <h3 style="font-size: 1rem; margin-bottom: 1rem;">Informations Académiques</h3>
            </div>

            <div style="grid-column: span 2;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">École / Université</label>
                <input type="text" name="ecole" value="{{ old('ecole', $stagiaire->ecole) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('ecole') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Filière / Spécialité</label>
                <input type="text" name="filiere" value="{{ old('filiere', $stagiaire->filiere) }}" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                @error('filiere') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Niveau d'études</label>
                <select name="niveau" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.75rem; border-radius: 8px; outline: none;">
                    <option value="1ere année" {{ old('niveau', $stagiaire->niveau) == '1ere année' ? 'selected' : '' }}>1ère année</option>
                    <option value="2eme année" {{ old('niveau', $stagiaire->niveau) == '2eme année' ? 'selected' : '' }}>2ème année</option>
                    <option value="3eme année" {{ old('niveau', $stagiaire->niveau) == '3eme année' ? 'selected' : '' }}>3ème année</option>
                    <option value="Master" {{ old('niveau', $stagiaire->niveau) == 'Master' ? 'selected' : '' }}>Master</option>
                    <option value="Doctorat" {{ old('niveau', $stagiaire->niveau) == 'Doctorat' ? 'selected' : '' }}>Doctorat</option>
                </select>
                @error('niveau') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('stagiaires.index') }}" class="btn-sm" style="display: flex; align-items: center; padding: 0.75rem 1.5rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
