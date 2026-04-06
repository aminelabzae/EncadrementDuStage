@extends('dashboard')

@section('title', 'Nouveau Stage')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">
    <a href="{{ route('stages.index') }}" style="color: var(--text-muted); text-decoration: none; margin-right: 0.5rem;">Stages</a> 
    <span style="color: var(--text-muted);">/</span> 
    Nouveau
</div>

<div class="panel" style="max-width: 800px;">
    <div class="panel-header">
        <div class="panel-title">Créer un nouveau stage</div>
    </div>

    @if ($errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('stages.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf

        <div>
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Sujet du stage <span style="color: #ef4444;">*</span></label>
            <input type="text" name="sujet" required value="{{ old('sujet') }}" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; color: var(--text-main); font-family: inherit;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Type de stage <span style="color: #ef4444;">*</span></label>
                <select name="type" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; color: var(--text-main); font-family: inherit;">
                    <option value="">Sélectionner un type</option>
                    <option value="initiation" {{ old('type') == 'initiation' ? 'selected' : '' }}>Initiation</option>
                    <option value="perfectionnement" {{ old('type') == 'perfectionnement' ? 'selected' : '' }}>Perfectionnement</option>
                    <option value="pfe" {{ old('type') == 'pfe' ? 'selected' : '' }}>PFE (Projet de Fin d'Études)</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Entreprise d'accueil <span style="color: #ef4444;">*</span></label>
                <select name="entreprise_id" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; color: var(--text-main); font-family: inherit;">
                    <option value="">Sélectionner une entreprise</option>
                    @foreach($entreprises as $entreprise)
                        <option value="{{ $entreprise->id }}" {{ old('entreprise_id') == $entreprise->id ? 'selected' : '' }}>{{ $entreprise->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Stagiaire <span style="color: #ef4444;">*</span></label>
                <select name="stagiaire_id" required style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; color: var(--text-main); font-family: inherit;">
                    <option value="">Sélectionner un stagiaire</option>
                    @foreach($stagiaires as $stagiaire)
                        <option value="{{ $stagiaire->id }}" {{ old('stagiaire_id') == $stagiaire->id ? 'selected' : '' }}>{{ $stagiaire->utilisateur->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Encadrant (Optionnel)</label>
                <select name="encadrant_id" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; color: var(--text-main); font-family: inherit;">
                    <option value="">Sélectionner un encadrant</option>
                    @foreach($encadrants as $encadrant)
                        <option value="{{ $encadrant->id }}" {{ old('encadrant_id') == $encadrant->id ? 'selected' : '' }}>{{ $encadrant->utilisateur->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Date de début <span style="color: #ef4444;">*</span></label>
                <input type="date" name="date_debut" required value="{{ old('date_debut') }}" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; color: var(--text-main); font-family: inherit; color-scheme: light;">
            </div>
            
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Date de fin</label>
                <input type="date" name="date_fin" value="{{ old('date_fin') }}" style="width: 100%; background: #f0f6ff; border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; color: var(--text-main); font-family: inherit; color-scheme: light;">
            </div>
        </div>

        <div style="margin-top: 1rem; display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid var(--border); padding-top: 1.5rem;">
            <a href="{{ route('stages.index') }}" class="btn-sm" style="padding: 0.6rem 1.2rem; font-size: 0.9rem;">Annuler</a>
            <button type="submit" class="btn-sm" style="background: var(--accent); color: white; border: none; padding: 0.6rem 1.2rem; font-size: 0.9rem; cursor: pointer;">Créer le stage</button>
        </div>
    </form>
</div>
@endsection
