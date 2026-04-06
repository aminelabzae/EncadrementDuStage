@extends('dashboard')

@section('title', 'Profil Stagiaire')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Fiche Stagiaire: {{ $stagiaire->utilisateur->prenom }} {{ $stagiaire->utilisateur->nom }}</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
    <!-- Left Column: Profile Card -->
    <div>
        <div class="panel" style="text-align: center;">
            <div class="avatar" style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto 1.5rem; background: rgba(59,130,246,0.1); color: #3b82f6;">{{ substr($stagiaire->utilisateur->prenom ?? 'S', 0, 1) }}</div>
            <h2 style="font-size: 1.25rem; margin-bottom: 0.25rem;">{{ $stagiaire->utilisateur->prenom }} {{ $stagiaire->utilisateur->nom }}</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">{{ $stagiaire->ecole }}</p>
            
            <div style="text-align: left; display: flex; flex-direction: column; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                <div>
                    <label style="display: block; font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Filière</label>
                    <div style="font-weight: 500;">{{ $stagiaire->filiere }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Niveau</label>
                    <div style="font-weight: 500;">{{ $stagiaire->niveau }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Email</label>
                    <div style="font-size: 0.9rem;">{{ $stagiaire->utilisateur->email }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Téléphone</label>
                    <div style="font-size: 0.9rem;">{{ $stagiaire->telephone ?? $stagiaire->utilisateur->telephone ?? 'Non renseigné' }}</div>
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 0.5rem;">
                <a href="{{ route('stagiaires.edit', $stagiaire) }}" class="btn-sm" style="flex: 1; justify-content: center; display: flex; align-items: center;">Modifier</a>
                @if(!Auth::user()->hasRole('STAGIAIRE'))
<form action="{{ route('stagiaires.destroy', $stagiaire) }}" method="POST" style="flex: 0 0 auto; margin: 0;" onsubmit="return confirm('Supprimer ce profil ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-sm" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2);"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </form>
@endif
            </div>
        </div>
    </div>

    <!-- Right Column: Stages History -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Historique des Stages</div>
        </div>

        @if($stagiaire->stages->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">
                            <th style="padding: 1rem 0.5rem;">Sujet</th>
                            <th style="padding: 1rem 0.5rem;">Entreprise</th>
                            <th style="padding: 1rem 0.5rem;">Encadrant</th>
                            <th style="padding: 1rem 0.5rem;">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stagiaire->stages as $stage)
                        <tr style="border-bottom: 1px solid rgba(37,99,235,0.08);">
                            <td style="padding: 1rem 0.5rem;">
                                <a href="{{ route('stages.show', $stage) }}" style="color: var(--accent); text-decoration: none; font-weight: 500;">{{ $stage->sujet }}</a>
                            </td>
                            <td style="padding: 1rem 0.5rem; font-size: 0.9rem;">{{ $stage->entreprise->nom ?? 'N/A' }}</td>
                            <td style="padding: 1rem 0.5rem; font-size: 0.9rem;">{{ $stage->encadrant->utilisateur->name ?? 'N/A' }}</td>
                            <td style="padding: 1rem 0.5rem;">
                                <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 100px; 
                                    background: {{ $stage->statut === 'en_cours' ? 'rgba(59,130,246,0.1)' : 'rgba(16,185,129,0.1)' }};
                                    color: {{ $stage->statut === 'en_cours' ? '#3b82f6' : '#10b981' }};">
                                    {{ ucfirst($stage->statut) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                <p>Aucun stage effectué pour le moment.</p>
            </div>
        @endif
    </div>
</div>
@endsection
