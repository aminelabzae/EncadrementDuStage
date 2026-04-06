@extends('dashboard')

@section('title', 'Gestion des Stagiaires')

@section('content')
@if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<div class="header-title" style="margin-bottom: 2rem;">Gestion des Stagiaires</div>

<div class="panel">
    <div class="panel-header">
        <div class="panel-title">Liste des Stagiaires</div>
        <div style="display: flex; gap: 1rem;">
            <form action="{{ route('stagiaires.index') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, Ecole..." style="background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.85rem;">
                <select name="niveau" style="background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.85rem; outline: none;">
                    <option value="">Tous les niveaux</option>
                    <option value="1ere année" {{ request('niveau') == '1ere année' ? 'selected' : '' }}>1ère année</option>
                    <option value="2eme année" {{ request('niveau') == '2eme année' ? 'selected' : '' }}>2ème année</option>
                    <option value="3eme année" {{ request('niveau') == '3eme année' ? 'selected' : '' }}>3ème année</option>
                    <option value="Master" {{ request('niveau') == 'Master' ? 'selected' : '' }}>Master</option>
                </select>
                <button type="submit" class="btn-sm">Filtrer</button>
            </form>
            @if(Auth::user()->hasRole('ADMIN'))
            <!-- Export -->
            <a href="{{ route('export.stagiaires.excel') }}" class="btn-sm" style="display: flex; align-items: center; gap: 0.4rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Exporter
            </a>

            <!-- Import -->
            <form action="{{ route('import.stagiaires') }}" method="POST" enctype="multipart/form-data" id="importStagiairesForm" style="display: none;">
                @csrf
                <input type="file" name="file" onchange="document.getElementById('importStagiairesForm').submit()">
            </form>
            <button onclick="document.querySelector('#importStagiairesForm input').click()" class="btn-sm" style="display: flex; align-items: center; gap: 0.4rem; background: #fff7ed; border: 1px solid #ffedd5; color: #9a3412; cursor: pointer;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Importer
            </button>

            <a href="{{ route('stagiaires.create') }}" class="btn-sm" style="background: var(--accent); color: white; border: none;">+ Nouveau Stagiaire</a>
            @endif
        </div>
    </div>

    @if($stagiaires->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase;">
                    <th style="padding: 1rem 0.5rem;">Stagiaire</th>
                    <th style="padding: 1rem 0.5rem;">Formation</th>
                    <th style="padding: 1rem 0.5rem;">École</th>
                    <th style="padding: 1rem 0.5rem;">Stages</th>
                    <th style="padding: 1rem 0.5rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stagiaires as $stagiaire)
                <tr style="border-bottom: 1px solid rgba(37,99,235,0.08);">
                    <td style="padding: 1rem 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="avatar" style="width: 32px; height: 32px; font-size: 0.8rem; background: rgba(59,130,246,0.1); color: #3b82f6;">{{ substr($stagiaire->utilisateur->prenom ?? 'S', 0, 1) }}</div>
                            <div>
                                <div style="font-weight: 500;">{{ $stagiaire->utilisateur->prenom }} {{ $stagiaire->utilisateur->nom }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $stagiaire->utilisateur->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-size: 0.9rem;">{{ $stagiaire->filiere }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $stagiaire->niveau }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem; font-size: 0.9rem;">{{ $stagiaire->ecole }}</td>
                    <td style="padding: 1rem 0.5rem;">
                        <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 100px; background: rgba(37,99,235,0.08); border: 1px solid var(--border);">
                            {{ $stagiaire->stages_count ?? $stagiaire->stages->count() }} Stage(s)
                        </span>
                    </td>
                    <td style="padding: 1rem 0.5rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('stagiaires.show', $stagiaire) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                        @if(Auth::user()->hasRole('ADMIN'))
                        <a href="{{ route('stagiaires.edit', $stagiaire) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></a>
                        <form action="{{ route('stagiaires.destroy', $stagiaire) }}" method="POST" style="margin:0;" onsubmit="return confirm('Supprimer ce stagiaire ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm" style="color: #ef4444;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
        {{ $stagiaires->links() }}
    </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <p>Aucun stagiaire trouvé.</p>
        </div>
    @endif
</div>
@endsection
