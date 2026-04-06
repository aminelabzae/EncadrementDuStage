@extends('dashboard')

@section('title', 'Gestion des Entreprises')

@section('content')
@if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<div class="header-title" style="margin-bottom: 2rem;">Gestion des Entreprises</div>

<div class="panel">
    <div class="panel-header">
        <div class="panel-title">Liste des Entreprises</div>
        <div style="display: flex; gap: 1rem;">
            <form action="{{ route('entreprises.index') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." style="background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.85rem;">
                <button type="submit" class="btn-sm">Filtrer</button>
            </form>
            @if(Auth::user()->hasRole('ADMIN'))
            <!-- Export -->
            <a href="{{ route('export.entreprises.excel') }}" class="btn-sm" style="display: flex; align-items: center; gap: 0.4rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Exporter
            </a>

            <!-- Import -->
            <form action="{{ route('import.entreprises') }}" method="POST" enctype="multipart/form-data" id="importEntreprisesForm" style="display: none;">
                @csrf
                <input type="file" name="file" onchange="document.getElementById('importEntreprisesForm').submit()">
            </form>
            <button onclick="document.querySelector('#importEntreprisesForm input').click()" class="btn-sm" style="display: flex; align-items: center; gap: 0.4rem; background: #fff7ed; border: 1px solid #ffedd5; color: #9a3412; cursor: pointer;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Importer
            </button>

            <a href="{{ route('entreprises.create') }}" class="btn-sm" style="background: var(--accent); color: white; border: none;">+ Nouvelle Entreprise</a>
            @endif
        </div>
    </div>

    @if($entreprises->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 1rem 0.5rem;">Nom</th>
                    <th style="padding: 1rem 0.5rem;">Localisation</th>
                    <th style="padding: 1rem 0.5rem;">Stages</th>
                    <th style="padding: 1rem 0.5rem;">Encadrants</th>
                    <th style="padding: 1rem 0.5rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entreprises as $entreprise)
                <tr style="border-bottom: 1px solid rgba(37,99,235,0.08);">
                    <td style="padding: 1rem 0.5rem; font-weight: 500;">{{ $entreprise->nom }}</td>
                    <td style="padding: 1rem 0.5rem; color: var(--text-muted);">
                        {{ $entreprise->adresse->ville ?? 'N/A' }}, {{ $entreprise->adresse->pays ?? 'N/A' }}
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <span style="font-size: 0.8rem; background: rgba(37,99,235,0.1); color: var(--accent); padding: 0.2rem 0.6rem; border-radius: 100px;">
                            {{ $entreprise->stages_count ?? $entreprise->stages->count() }} Stages
                        </span>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                         <span style="font-size: 0.8rem; background: rgba(139,92,246,0.1); color: #8b5cf6; padding: 0.2rem 0.6rem; border-radius: 100px;">
                            {{ $entreprise->encadrants_count ?? $entreprise->encadrants->count() }} Encadrants
                        </span>
                    </td>
                    <td style="padding: 1rem 0.5rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('entreprises.show', $entreprise) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;" title="Voir">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        @if(Auth::user()->hasRole('ADMIN'))
                        <a href="{{ route('entreprises.edit', $entreprise) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;" title="Modifier">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        @endif
                        @if(Auth::user()->hasRole('ADMIN'))
                        <form action="{{ route('entreprises.destroy', $entreprise) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');">
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
        {{ $entreprises->links() }}
    </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <p>Aucune entreprise n'a été trouvée.</p>
        </div>
    @endif
</div>
@endsection
