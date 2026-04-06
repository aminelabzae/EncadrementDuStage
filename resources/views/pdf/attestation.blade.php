<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation de Stage</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .container { border: 10px double #bfdbfe; padding: 50px; position: relative; min-height: 800px; }
        .header { text-align: center; margin-bottom: 50px; }
        .header h1 { font-size: 32px; color: #1e3a5f; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 2px; }
        .header .subtitle { font-size: 14px; color: #64748b; font-style: italic; }
        
        .content { margin-top: 60px; font-size: 18px; text-align: justify; }
        .content p { margin-bottom: 25px; }
        
        .highlight { font-weight: bold; color: #1e3a5f; }
        
        .footer { margin-top: 100px; }
        .signatures { display: table; width: 100%; margin-top: 50px; }
        .signature-box { display: table-cell; width: 50%; text-align: center; }
        
        .date-box { text-align: right; margin-bottom: 30px; font-size: 14px; }
        
        .stamp { 
            position: absolute; bottom: 50px; left: 50px; 
            opacity: 0.1; font-size: 80px; transform: rotate(-30deg);
            color: #2563eb; font-weight: bold; pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="stamp">OFFICIEL</div>
        
        <div class="header">
            <h1>Attestation de Stage</h1>
            <div class="subtitle">Confirmation de l'accomplissement des activités professionnelles</div>
        </div>

        <div class="date-box">
            Fait le {{ $date }}
        </div>

        <div class="content">
            <p>
                Nous soussignés, <strong>{{ $stage->entreprise->nom ?? 'L\'entreprise d\'accueil' }}</strong>, 
                attestons par la présente que :
            </p>
            
            <p style="text-align: center; font-size: 22px; margin: 30px 0;">
                M. / Mme <span class="highlight">{{ $stage->stagiaire->utilisateur->name }}</span>
            </p>

            <p style="text-indent: 30px;">
                A effectué un stage au sein de notre établissement dans le cadre de sa formation. 
                Le stage s'est déroulé durant la période allant du 
                <span class="highlight">{{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}</span> au 
                <span class="highlight">{{ $stage->date_fin ? \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') : 'la date de fin prévue' }}</span>.
            </p>

            <p>
                Durant cette période, le stagiaire a travaillé sur le sujet suivant : <br>
                <span class="highlight">« {{ $stage->sujet }} »</span>
            </p>

            <p>
                Ses missions ont été accomplies avec sérieux et professionnalisme, sous la supervision de 
                <span class="highlight">{{ $stage->encadrant->utilisateur->name ?? 'son tuteur' }}</span>.
            </p>

            <p>
                Cette attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.
            </p>
        </div>

        <div class="signatures">
            <div class="signature-box">
                <div style="font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 100px;">Cachet et Signature de l'Entreprise</div>
            </div>
            <div class="signature-box">
                <div style="font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 100px;">Direction Pédagogique</div>
            </div>
        </div>
    </div>
</body>
</html>
