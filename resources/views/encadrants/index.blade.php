@extends('dashboard')

@section('title', 'Gestion des Encadrants')

@section('content')
@if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<div class="header-title" style="margin-bottom: 2rem;">Gestion des Encadrants</div>

<div class="panel">
    <div class="panel-header">
        <div class="panel-title">Liste des Encadrants</div>
        <div style="display: flex; gap: 1rem;">
            <form action="{{ route('encadrants.index') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, Email..." style="background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.85rem;">
                <select name="entreprise_id" style="background: #f0f6ff; border: 1px solid var(--border); color: var(--text-main); padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.85rem; outline: none;">
                    <option value="">Toutes les entreprises</option>
                    @foreach($entreprises as $entreprise)
                        <option value="{{ $entreprise->id }}" {{ request('entreprise_id') == $entreprise->id ? 'selected' : '' }}>{{ $entreprise->nom }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-sm">Filtrer</button>
            </form>
            @if(Auth::user()->hasRole('ADMIN'))
            <a href="{{ route('encadrants.create') }}" class="btn-sm" style="background: var(--accent); color: white; border: none;">+ Nouvel Encadrant</a>
            @endif
        </div>
    </div>

    @if($encadrants->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 1rem 0.5rem;">Encadrant</th>
                    <th style="padding: 1rem 0.5rem;">Poste</th>
                    <th style="padding: 1rem 0.5rem;">Entreprise</th>
                    <th style="padding: 1rem 0.5rem;">Contact</th>
                    <th style="padding: 1rem 0.5rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($encadrants as $encadrant)
                <tr style="border-bottom: 1px solid rgba(37,99,235,0.08);">
                    <td style="padding: 1rem 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div class="avatar" style="width: 32px; height: 32px; font-size: 0.8rem;">{{ substr($encadrant->utilisateur->prenom ?? 'E', 0, 1) }}</div>
                            <div>
                                <div style="font-weight: 500;">{{ $encadrant->utilisateur->prenom }} {{ $encadrant->utilisateur->nom }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $encadrant->utilisateur->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1rem 0.5rem; font-size: 0.9rem;">{{ $encadrant->poste }}</td>
                    <td style="padding: 1rem 0.5rem; font-size: 0.9rem;">{{ $encadrant->entreprise->nom ?? 'N/A' }}</td>
                    <td style="padding: 1rem 0.5rem; font-size: 0.9rem; color: var(--text-muted);">{{ $encadrant->telephone ?? $encadrant->utilisateur->telephone ?? 'N/A' }}</td>
                    <td style="padding: 1rem 0.5rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('encadrants.show', $encadrant) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;" title="Voir">
                             <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        @if(Auth::user()->hasRole('ADMIN'))
                        <a href="{{ route('encadrants.edit', $encadrant) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;" title="Modifier">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        <form action="{{ route('encadrants.destroy', $encadrant) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Êtes-vous sûr ? Cela supprimera également le compte utilisateur.');">
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
        {{ $encadrants->links() }}
    </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <p>Aucun encadrant trouvé.</p>
        </div>
    @endif
</div>
@endsection
