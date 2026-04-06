@extends('dashboard')

@section('title', 'Prochaines Visites')

@section('content')
@if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<div class="header-title" style="margin-bottom: 2rem;">Visites de Stage</div>

<div class="panel">
    <div class="panel-header">
        <div class="panel-title">Calendrier des visites</div>
        <a href="{{ route('visites.create') }}" class="btn-sm" style="background: var(--accent); color: white; border: none;">+ Planifier une Visite</a>
    </div>

    @if($visites->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase;">
                    <th style="padding: 1rem 0.5rem;">Date & Heure</th>
                    <th style="padding: 1rem 0.5rem;">Stagiaire</th>
                    <th style="padding: 1rem 0.5rem;">Entreprise</th>
                    <th style="padding: 1rem 0.5rem;">Type</th>
                    <th style="padding: 1rem 0.5rem;">Statut</th>
                    <th style="padding: 1rem 0.5rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visites as $visite)
                <tr style="border-bottom: 1px solid rgba(37,99,235,0.08);">
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-weight: 500;">{{ \Carbon\Carbon::parse($visite->date)->format('d/m/Y') }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($visite->date)->format('H:i') }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem; font-size: 0.9rem;">
                        {{ $visite->stage->stagiaire->utilisateur->name ?? 'N/A' }}
                    </td>
                    <td style="padding: 1rem 0.5rem; font-size: 0.9rem; color: var(--text-muted);">
                        {{ $visite->stage->entreprise->nom ?? 'N/A' }}
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <span style="font-size: 0.8rem; background: rgba(59,130,246,0.1); color: #3b82f6; padding: 0.2rem 0.5rem; border-radius: 4px;">{{ $visite->type }}</span>
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        @php $isPast = \Carbon\Carbon::parse($visite->date)->isPast(); @endphp
                        <span style="font-size: 0.75rem; color: {{ $isPast ? '#10b981' : '#f59e0b' }};">
                            {{ $isPast ? 'Effectuée' : 'À venir' }}
                        </span>
                    </td>
                    <td style="padding: 1rem 0.5rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('visites.show', $visite) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                        <a href="{{ route('visites.edit', $visite) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></a>
                        @if(!Auth::user()->hasRole('STAGIAIRE'))
<form action="{{ route('visites.destroy', $visite) }}" method="POST" style="margin:0;" onsubmit="return confirm('Supprimer cette visite ?');">
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
    <div style="margin-top: 1.5rem;">
        {{ $visites->links() }}
    </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <p>Aucune visite planifiée.</p>
        </div>
    @endif
</div>
@endsection
