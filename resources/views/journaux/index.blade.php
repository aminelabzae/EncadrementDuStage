@extends('dashboard')

@section('title', "Journaux d'Activité")

@section('content')
@if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<div class="header-title" style="margin-bottom: 2rem;">Journaux de Bord</div>

<div class="panel">
    <div class="panel-header">
        <div class="panel-title">Dernières entrées</div>
        <a href="{{ route('journaux.create') }}" class="btn-sm" style="background: var(--accent); color: white; border: none;">+ Nouvelle Entrée</a>
    </div>

    @if($journaux->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase;">
                    <th style="padding: 1rem 0.5rem;">Date</th>
                    <th style="padding: 1rem 0.5rem;">Heures</th>
                    <th style="padding: 1rem 0.5rem;">Stagiaire</th>
                    <th style="padding: 1rem 0.5rem;">Aperçu des activités</th>
                    <th style="padding: 1rem 0.5rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($journaux as $journal)
                <tr style="border-bottom: 1px solid rgba(37,99,235,0.08);">
                    <td style="padding: 1rem 0.5rem; font-size: 0.9rem;">
                        {{ \Carbon\Carbon::parse($journal->date)->format('d/m/Y') }}
                    </td>
                    <td style="padding: 1rem 0.5rem; font-size: 0.9rem; color: var(--accent);">
                        {{ $journal->heures ?? '-' }}h
                    </td>
                    <td style="padding: 1rem 0.5rem;">
                        <div style="font-weight: 500;">{{ $journal->stage->stagiaire->utilisateur->name ?? 'N/A' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $journal->stage->entreprise->nom ?? 'N/A' }}</div>
                    </td>
                    <td style="padding: 1rem 0.5rem; font-size: 0.85rem; color: var(--text-muted); max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ Str::limit($journal->activites, 100) }}
                    </td>
                    <td style="padding: 1rem 0.5rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('journaux.show', $journal->id) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                        @if(!Auth::user()->hasRole('STAGIAIRE') || (Auth::user()->stagiaire && $journal->stage && $journal->stage->stagiaire_id === Auth::user()->stagiaire->id))
                        <a href="{{ route('journaux.edit', $journal->id) }}" class="btn-sm" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></a>
                        @endif
                        @if(!Auth::user()->hasRole('STAGIAIRE'))
<form action="{{ route('journaux.destroy', $journal->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Supprimer ce journal ?');">
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
        {{ $journaux->links() }}
    </div>
    @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <p>Aucun journal n'a été rédigé.</p>
        </div>
    @endif
</div>
@endsection
