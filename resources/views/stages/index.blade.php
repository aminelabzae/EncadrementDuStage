@extends('dashboard')

@section('content')
@if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<div class="header-title" style="margin-bottom: 2rem;">Gestion des Stages</div>

<div class="panel">
    <div class="panel-header">
        <div class="panel-title">Liste des Stages</div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            @if(Auth::user()->hasRole('ADMIN'))
            <!-- Export -->
            <a href="{{ route('export.stages.excel') }}" class="btn-sm" style="display: flex; align-items: center; gap: 0.4rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Exporter Excel
            </a>

            <!-- Import -->
            <form action="{{ route('import.stages') }}" method="POST" enctype="multipart/form-data" id="importForm" style="display: none;">
                @csrf
                <input type="file" name="file" onchange="document.getElementById('importForm').submit()">
            </form>
            <button onclick="document.querySelector('#importForm input').click()" class="btn-sm" style="display: flex; align-items: center; gap: 0.4rem; background: #fff7ed; border: 1px solid #ffedd5; color: #9a3412; cursor: pointer;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Importer Données
            </button>

            <a href="{{ route('stages.create') }}" class="btn-sm" style="background: var(--accent); color: white; border: none; margin-left: 0.5rem;">+ Nouveau Stage</a>
            @endif
        </div>
    </div>

    @if($stages->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 1rem 0.5rem;">Sujet</th>
                    <th style="padding: 1rem 0.5rem;">Stagiaire</th>
                    <th style="padding: 1rem 0.5rem;">Entreprise</th>
                    <th style="padding: 1rem 0.5rem;">Statut</th>
                    <th style="padding: 1rem 0.5rem;">Période</th>
                    <th style="padding: 1rem 0.5rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stages as $stage)
                <tr style="border-bottom: 1px solid rgba(37,99,235,0.08);">
                    <td style="padding: 1rem 0.5rem; font-weight: 500;">{{ $stage->sujet ?? 'N/A' }}</td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div class="avatar" style="width: 24px; height: 24px; font-size: 0.6rem;">{{ substr($stage->stagiaire->utilisateur->name ?? 'S', 0, 1) }}</div>
                            {{ $stage->stagiaire->utilisateur->name ?? 'Non assigné' }}
                        </div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">{{ $stage->entreprise->nom ?? 'Non assigné' }}</td>
                    <td style="padding: 1rem 0.5rem;">
                        <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 100px;
                            background: {{ $stage->statut === 'en_cours' ? 'rgba(59,130,246,0.1)' : ($stage->statut === 'termine' ? 'rgba(16,185,129,0.1)' : 'rgba(107,114,128,0.1)') }};
                            color: {{ $stage->statut === 'en_cours' ? '#3b82f6' : ($stage->statut === 'termine' ? '#10b981' : '#9ca3af') }};
                            border: 1px solid {{ $stage->statut === 'en_cours' ? 'rgba(59,130,246,0.2)' : ($stage->statut === 'termine' ? 'rgba(16,185,129,0.2)' : 'rgba(107,114,128,0.2)') }};
                        ">
                            {{ ucfirst(str_replace('_', ' ', $stage->statut ?? 'Inconnu')) }}
                        </span>
                    </td>
                    <td style="padding: 1rem 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
                        {{ $stage->date_debut ? \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') : '' }} -
                        {{ $stage->date_fin ? \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') : 'À définir' }}
                    </td>
                    <td style="padding: 1rem 0.5rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('stages.show', $stage) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;" title="Voir">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        @if(Auth::user()->hasRole('ADMIN') || (Auth::user()->hasRole('ENCADRANT') && $stage->encadrant_id === Auth::user()->encadrant?->id))
                        <a href="{{ route('stages.edit', $stage) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;" title="Modifier">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        @endif
                        @if(Auth::user()->hasRole('ADMIN') || (Auth::user()->hasRole('ENCADRANT') && $stage->encadrant_id === Auth::user()->encadrant?->id))
                        <form action="{{ route('stages.destroy', $stage) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce stage ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0; background: transparent; color: #ef4444; border-color: rgba(239, 68, 68, 0.3); cursor: pointer;" title="Supprimer">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
        {{ $stages->links() }}
    </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <p>Aucun stage n'a été trouvé.</p>
        </div>
    @endif
</div>
@endsection
