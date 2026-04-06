@extends('dashboard')

@section('title', 'Détails Entreprise')

@section('content')
<div class="header-title" style="margin-bottom: 2rem;">Fiche Entreprise: {{ $entreprise->nom }}</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
    <!-- Left Column: Info Card -->
    <div>
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Informations Générales</div>
                <a href="{{ route('entreprises.edit', $entreprise) }}" class="btn-sm">Modifier</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Nom</label>
                    <div style="font-weight: 600;">{{ $entreprise->nom }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Localisation</label>
                    <div>{{ $entreprise->adresse->rue ?? '' }}</div>
                    <div>{{ $entreprise->adresse->quartier ?? '' }}</div>
                    <div style="font-weight: 500;">{{ $entreprise->adresse->ville ?? 'N/A' }}, {{ $entreprise->adresse->pays ?? 'N/A' }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">CP: {{ $entreprise->adresse->code_postal ?? 'N/A' }}</div>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--text-muted); font-size: 0.9rem;">Stages total</span>
                        <span style="font-weight: 600;">{{ $entreprise->stages->count() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted); font-size: 0.9rem;">Encadrants</span>
                        <span style="font-weight: 600;">{{ $entreprise->encadrants->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Tabs/Related Lists -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Encadrants List -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Encadrants de l'entreprise</div>
            </div>
            @if($entreprise->encadrants->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($entreprise->encadrants as $encadrant)
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; background: rgba(37,99,235,0.04); border-radius: 8px; border: 1px solid var(--border);">
                            <div class="avatar" style="width: 32px; height: 32px; font-size: 0.8rem;">{{ substr($encadrant->utilisateur->name ?? 'E', 0, 1) }}</div>
                            <div style="flex-grow: 1;">
                                <div style="font-weight: 500; font-size: 0.95rem;">{{ $encadrant->utilisateur->name }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $encadrant->poste }} • {{ $encadrant->utilisateur->email }}</div>
                            </div>
                            <a href="{{ route('encadrants.show', $encadrant) }}" class="btn-sm">Détails</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--text-muted); font-size: 0.9rem; text-align: center;">Aucun encadrant enregistré.</p>
            @endif
        </div>

        <!-- Recent Stages -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Stages récents</div>
            </div>
            @if($entreprise->stages->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">
                                <th style="padding: 0.75rem 0.5rem;">Sujet</th>
                                <th style="padding: 0.75rem 0.5rem;">Stagiaire</th>
                                <th style="padding: 0.75rem 0.5rem;">Statut</th>
                                <th style="padding: 0.75rem 0.5rem; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entreprise->stages->take(5) as $stage)
                            <tr style="border-bottom: 1px solid rgba(37,99,235,0.08);">
                                <td style="padding: 0.75rem 0.5rem; font-size: 0.9rem;">{{ Str::limit($stage->sujet, 30) }}</td>
                                <td style="padding: 0.75rem 0.5rem; font-size: 0.85rem;">{{ $stage->stagiaire->utilisateur->name ?? 'N/A' }}</td>
                                <td style="padding: 0.75rem 0.5rem;">
                                    <span style="font-size: 0.7rem; padding: 0.1rem 0.4rem; border-radius: 100px; background: rgba(59,130,246,0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2);">
                                        {{ ucfirst($stage->statut) }}
                                    </span>
                                </td>
                                <td style="padding: 0.75rem 0.5rem; text-align: right;">
                                    <a href="{{ route('stages.show', $stage) }}" class="btn-sm" style="padding: 0.2rem 0.5rem;">Voir</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="color: var(--text-muted); font-size: 0.9rem; text-align: center;">Aucun stage enregistré.</p>
            @endif
        </div>
    </div>
</div>
@endsection
