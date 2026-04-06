<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'Évaluation de Stage</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.4; font-size: 11pt; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #1e3a5f; margin: 0; font-size: 24px; text-transform: uppercase; }
        
        .section { margin-bottom: 25px; }
        .section-title { background: #f0f6ff; color: #1e3a5f; padding: 8px 12px; font-weight: bold; border-left: 4px solid #2563eb; margin-bottom: 12px; }
        
        .info-grid { display: block; margin-bottom: 15px; }
        .info-row { margin-bottom: 8px; }
        .label { font-weight: bold; width: 150px; display: inline-block; color: #64748b; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { background: #f8fafc; color: #1e3a5f; }
        
        .note { font-weight: bold; font-size: 16px; color: #2563eb; }
        
        .footer { margin-top: 50px; border-top: 1px solid #e2e8f0; padding-top: 20px; text-align: center; font-size: 10px; color: #94a3b8; }
        
        .signatures { margin-top: 60px; }
        .sig-box { width: 45%; display: inline-block; vertical-align: top; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport de Synthèse d'Évaluation</h1>
        <div style="margin-top: 5px;">Année Universitaire : 2025-2026</div>
    </div>

    <div class="section">
        <div class="section-title">Identification du Stage</div>
        <div class="info-row"><span class="label">Stagiaire :</span> <strong>{{ $stage->stagiaire->utilisateur->name }}</strong></div>
        <div class="info-row"><span class="label">Entreprise :</span> {{ $stage->entreprise->nom ?? 'N/A' }}</div>
        <div class="info-row"><span class="label">Période :</span> Du {{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }} au {{ $stage->date_fin ? \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') : 'En cours' }}</div>
        <div class="info-row"><span class="label">Sujet :</span> <em>{{ $stage->sujet }}</em></div>
        <div class="info-row"><span class="label">Encadrant :</span> {{ $stage->encadrant->utilisateur->name ?? 'N/A' }}</div>
    </div>

    <div class="section">
        <div class="section-title">Synthèse des Notes</div>
        <table>
            <thead>
                <tr>
                    <th>Date d'évaluation</th>
                    <th>Critères / Commentaires</th>
                    <th style="width: 80px; text-align: center;">Note / 20</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stage->evaluations as $eval)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($eval->date_evaluation)->format('d/m/Y') }}</td>
                    <td>{{ $eval->commentaire ?? 'Pas de commentaire particulier.' }}</td>
                    <td style="text-align: center;" class="note">{{ $eval->note }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px; color: #94a3b8;">Aucune évaluation enregistrée pour ce stage.</td>
                </tr>
                @endforelse
            </tbody>
            @if($stage->evaluations->count() > 0)
            <tfoot>
                <tr style="background: #f1f5f9;">
                    <td colspan="2" style="text-align: right; font-weight: bold;">Moyenne Générale :</td>
                    <td style="text-align: center;" class="note">{{ round($stage->evaluations->avg('note'), 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">Appréciation Globale & Observations</div>
        <div style="min-height: 100px; border: 1px solid #e2e8f0; padding: 15px; border-radius: 4px; background: #fff;">
            @if($stage->evaluations->count() > 0)
                {{-- On pourrait mettre ici le dernier commentaire ou une synthèse --}}
                L'étudiant a démontré une capacité d'adaptation et une progression constante tout au long de son stage.
            @else
                En attente des évaluations finales.
            @endif
        </div>
    </div>

    <div class="signatures">
        <div class="sig-box">
            <div style="font-weight: bold; border-bottom: 1px solid #2563eb; margin-bottom: 70px;">Visa de l'Encadrant Professionnel</div>
        </div>
        <div style="width: 9%;"></div>
        <div class="sig-box">
            <div style="font-weight: bold; border-bottom: 1px solid #2563eb; margin-bottom: 70px;">Visa du Tuteur Pédagogique</div>
        </div>
    </div>

    <div class="footer">
        Document généré automatiquement par StagesApp le {{ $date }}<br>
        © StagesApp - Système de Gestion Intégrée des Stages
    </div>
</body>
</html>
