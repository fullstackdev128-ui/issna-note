<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de Notes - {{ $etudiant->matricule }}</title>
    <style>
        @page { margin: 20px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #000; margin: 0; padding: 0; }
        .header { display: table; width: 100%; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
        .header-cell { display: table-cell; vertical-align: middle; }
        .logo-img { height: 70px; }
        .header-center { text-align: center; width: 100%; }
        .inst-name { font-size: 12px; font-weight: bold; color: #8B0000; margin-bottom: 2px; }
        .inst-sub { font-size: 9px; font-weight: bold; margin-bottom: 2px; }
        .inst-details { font-size: 7px; margin-bottom: 1px; }
        .transcript-title { font-size: 14px; font-weight: bold; margin-top: 5px; border: 1px solid #000; padding: 2px 10px; display: inline-block; }
        
        .student-info { width: 100%; margin-bottom: 10px; border-collapse: collapse; }
        .student-info td { padding: 2px 0; vertical-align: top; }
        .info-label { font-weight: bold; }
        
        .ue-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .ue-table th { background-color: #2c5282; color: white; padding: 4px; font-size: 8px; border: 1px solid #000; text-align: center; }
        .ue-table td { padding: 3px 5px; border: 1px solid #000; }
        .ue-row { background-color: #e8f0fe; font-weight: bold; }
        .ec-row { font-size: 9px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .semester-summary { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .semester-summary td, .semester-summary th { border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold; }
        .summary-header { background-color: #f2f2f2; }
        
        .footer { margin-top: 15px; width: 100%; }
        .grades-table { width: 40%; border-collapse: collapse; float: left; }
        .grades-table td, .grades-table th { border: 0.5px solid #ccc; padding: 1px 3px; font-size: 7px; text-align: center; }
        .signatures { width: 50%; float: right; text-align: right; margin-top: 10px; }
        .signature-box { margin-top: 30px; }
        
        .legal-mention { clear: both; text-align: center; font-style: italic; font-size: 7px; margin-top: 20px; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-cell" style="width: 80px;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/IFPSCID.png'))) }}" class="logo-img">
        </div>
        <div class="header-cell header-center">
            <div class="inst-name">INSTITUT SUPERIEUR DE SANTE ET DE NUTRITION APPLIQUÉE (ISSNA)</div>
            <div class="inst-sub">INSTITUT DE FORMATIONS PROFESSIONNELLES EN SCIENCES DU DÉVELOPPEMENT</div>
            <div class="inst-details">ARRÊTÉ N°002/MINEFOP/SG/DFOP/SDGSF/SACD DU 10 Juin 2020</div>
            <div class="inst-details">AUTORISATION N° 26-02379/NHA/MINESUP/DDES/ESUP/SDA/NS</div>
            <div class="inst-details">RCCM : CM-DLA-03-2026-B13-00225</div>
            <div class="transcript-title">RELEVÉ DE NOTES / TRANSCRIPT</div>
            <div style="font-size: 8px; margin-top: 2px;">Ref N° ISSNA/SCOL/{{ $annee }}</div>
        </div>
        <div class="header-cell" style="width: 80px; text-align: right;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/ISSNA.png'))) }}" class="logo-img">
        </div>
    </div>

    <table class="student-info">
        <tr>
            <td style="width: 65%;">
                <span class="info-label">Noms et prénoms / Surname and name :</span> {{ strtoupper($etudiant->nom_complet) }}<br>
                <span class="info-label">Né(e) le / Born on :</span> {{ $etudiant->date_naissance ? $etudiant->date_naissance->format('d/m/Y') : 'N/A' }} 
                <span class="info-label">À / At :</span> {{ $etudiant->lieu_naissance ?? 'N/A' }}<br>
                <span class="info-label">Cycle :</span> @php
                    $cycle = match($etudiant->specialite->type_diplome) {
                        'BTS'     => 'BTS',
                        'Licence' => 'LICENCE PROFESSIONNELLE',
                        'Master1' => 'MASTER 1',
                        'Master2' => 'MASTER 2',
                        default   => 'BTS',
                    };
                @endphp {{ strtoupper($cycle) }}<br>
                <span class="info-label">Filière / Field of study :</span> {{ $etudiant->specialite->filiere->nom }}
            </td>
            <td style="width: 35%;">
                <span class="info-label">Matricule :</span> {{ $etudiant->matricule }}<br>
                <span class="info-label">Niveau :</span> {{ $etudiant->niveau_actuel }}<br>
                <span class="info-label">Année académique :</span> {{ $annee }}<br>
                <span class="info-label">Spécialité :</span> {{ $etudiant->specialite->nom }}
            </td>
        </tr>
    </table>

    @foreach($semestres as $s)
        <div style="margin-top: 10px;">
            <div style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">SEMESTRE {{ $s['numero'] }}</div>
            <table class="ue-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">CODE UE</th>
                        <th style="width: 40px;">TYPE</th>
                        <th>UNITÉS D'ENSEIGNEMENT / ÉLÉMENTS CONSTITUTIFS</th>
                        <th style="width: 60px;">NOTE/20</th>
                        <th style="width: 60px;">MOYENNE UE</th>
                        <th style="width: 50px;">CRÉDIT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($s['resultat']['detail_ues'] as $resUE)
                        <tr class="ue-row">
                            <td class="text-center">{{ $resUE['ue']->code_ue }}</td>
                            <td class="text-center">{{ strtoupper(substr($resUE['ue']->type_ue, 0, 4)) }}</td>
                            <td>{{ strtoupper($resUE['ue']->nom) }}</td>
                            <td class="text-center"></td>
                            <td class="text-center">{{ number_format($resUE['moyenne'], 2) }}</td>
                            <td class="text-center">{{ $resUE['credits_ue'] }}</td>
                        </tr>
                        @foreach($resUE['detail_ecs'] as $ec)
                            <tr class="ec-row">
                                <td class="text-center" style="font-size: 7px; color: #666;">{{ $ec['code_ec'] ?? '' }}</td>
                                <td class="text-center"></td>
                                <td style="padding-left: 20px;">{{ $ec['nom'] }}</td>
                                <td class="text-center">{{ number_format($ec['note'], 2) }}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <table class="semester-summary">
                <tr class="summary-header">
                    <td>TOTAL CRÉDIT/30</td>
                    <td>MOYENNE SEMESTRIELLE/20</td>
                    <td>MGP / GPA</td>
                    <td>GRADE</td>
                    <td>DÉCISION DU JURY</td>
                </tr>
                <tr>
                    <td>{{ $s['resultat']['credits_valides'] }}/30</td>
                    <td>{{ number_format($s['resultat']['moyenne_sem'], 2) }}</td>
                    <td>{{ number_format($s['resultat']['mgp'], 1) }}</td>
                    <td>{{ $s['resultat']['grade'] }}</td>
                    <td>{{ $s['en_base'] ? $s['en_base']->decision_jury_formatee : '---' }}</td>
                </tr>
            </table>
        </div>
    @endforeach

    @if($est_annuel && $resultat_annuel)
    <div style="margin-top: 15px; page-break-inside: avoid;">
        <div style="font-weight: bold; text-decoration: underline; margin-bottom: 5px; color: #1e3a8a;">RÉSULTAT ANNUEL (Synthèse)</div>
        <table style="width:100%; border-collapse:collapse; background:#f8fafc; border: 2px solid #1e3a8a;"> 
            <tr style="background:#1e3a8a; color:white;"> 
                <th style="padding:6px; text-align:center; border:1px solid #1e3a8a; font-size:9px;">TOTAL CRÉDIT ANNUEL</th> 
                <th style="padding:6px; text-align:center; border:1px solid #1e3a8a; font-size:9px;">MOYENNE ANNUELLE/20</th> 
                <th style="padding:6px; text-align:center; border:1px solid #1e3a8a; font-size:9px;">MGP / GPA</th> 
                <th style="padding:6px; text-align:center; border:1px solid #1e3a8a; font-size:9px;">GRADE</th> 
                <th style="padding:6px; text-align:center; border:1px solid #1e3a8a; font-size:9px;">DÉCISION DU JURY</th> 
            </tr> 
            <tr> 
                <td style="padding:8px; text-align:center; border:1px solid #1e3a8a; font-weight:bold; font-size:11px;">{{ $resultat_annuel->credits_valides_total }}/60</td> 
                <td style="padding:8px; text-align:center; border:1px solid #1e3a8a; font-weight:bold; font-size:16px; color:#1e3a8a;">{{ number_format($resultat_annuel->moyenne_annuelle, 2) }}</td> 
                <td style="padding:8px; text-align:center; border:1px solid #1e3a8a; font-weight:bold; font-size:11px;">{{ number_format($resultat_annuel->mgp_annuel, 2) }}</td> 
                <td style="padding:8px; text-align:center; border:1px solid #1e3a8a; font-weight:bold; font-size:11px;">{{ $resultat_annuel->grade_annuel }}</td> 
                <td style="padding:8px; text-align:center; border:1px solid #1e3a8a; font-weight:bold; font-size:11px; text-transform:uppercase;">{{ $resultat_annuel->decision_jury_formatee ?? 'À valider' }}</td> 
            </tr> 
        </table>
    </div>
    @endif

    <div class="footer">
        <table class="grades-table">
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>Appréciation</th>
                    <th>Moy/20</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>A+</td><td>Excellent</td><td>18 - 20</td></tr>
                <tr><td>A</td><td>Très Bien</td><td>16 - 17.99</td></tr>
                <tr><td>B+</td><td>Bien</td><td>14 - 15.99</td></tr>
                <tr><td>B</td><td>Bien</td><td>12 - 13.99</td></tr>
                <tr><td>C+</td><td>Assez Bien</td><td>11 - 11.99</td></tr>
                <tr><td>C</td><td>Passable</td><td>10 - 10.99</td></tr>
                <tr><td>D</td><td>Insuffisant</td><td>08 - 09.99</td></tr>
                <tr><td>E</td><td>Faible</td><td>06 - 07.99</td></tr>
                <tr><td>F</td><td>Nul</td><td>00 - 05.99</td></tr>
            </tbody>
        </table>

        <div class="signatures">
            <div>Douala, le {{ $date }}</div>
            <div class="signature-box">
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: left; width: 50%;">
                            <strong>Le Directeur ISSNA</strong><br>
                            <span style="font-size: 8px;">The Manager</span>
                        </td>
                        <td style="text-align: right; width: 50%;">
                            <strong>La Direction IFPSCID</strong><br>
                            <span style="font-size: 8px;">The IFPSCID Management</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="legal-mention">
        Il n'est délivré qu'un seul exemplaire de relevé de notes, le titulaire peut en faire des copies certifiées conformes.<br>
        This transcript is delivered only once, the owner can do as many certified copies as necessary.
    </div>

</body>
</html>
