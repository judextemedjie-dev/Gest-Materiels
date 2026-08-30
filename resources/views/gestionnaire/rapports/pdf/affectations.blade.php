{{-- resources/views/gestionnaire/rapports/pdf/affectations.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Affectations — GestMatériel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        .header { background: #0f766e; color: white; padding: 18px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p  { font-size: 10px; opacity: 0.8; margin-top: 3px; }
        .summary { display: flex; gap: 20px; margin: 0 24px 16px; padding: 10px 14px; background: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 6px; }
        .summary span { font-size: 11px; color: #475569; }
        .summary strong { color: #1e293b; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #0f766e; color: white; }
        thead th { padding: 9px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody td { padding: 7px 10px; font-size: 10px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 9px; font-weight: bold; }
        .badge-blue  { background: #dbeafe; color: #1e40af; }
        .badge-gray  { background: #f1f5f9; color: #475569; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 20px; padding: 0 24px; padding-top: 10px; border-top: 1px solid #e2e8f0; }
        .page-body { padding: 0 24px; }
    </style>
</head>
<body>

<div class="header">
    <h1><strong>GestMatériel</strong> — Rapport des Affectations</h1>
    <p>Historique complet des affectations et restitutions</p>
    <p>Généré le {{ now()->format('d/m/Y à H:i:s') }}</p>
</div>

<div class="summary">
    <span>Total : <strong>{{ $affectations->count() }} affectations</strong></span>
    <span>En cours : <strong style="color:#1d4ed8">{{ $affectations->where('statut','affecte')->count() }}</strong></span>
    <span>Restituées : <strong style="color:#15803d">{{ $affectations->where('statut','restitue')->count() }}</strong></span>
</div>

<div class="page-body">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Matériel</th>
                <th>Client</th>
                <th style="text-align:center">Qté</th>
                <th>Date affectation</th>
                <th>Heure</th>
                <th>Date restitution</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($affectations as $i => $aff)
            <tr>
                <td style="color:#94a3b8">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ Str::limit($aff->materiel?->designation, 22) }}</strong><br>
                    <span style="font-family:monospace;font-size:8px;color:#64748b">{{ $aff->materiel?->code_identification }}</span>
                </td>
                <td>
                    <strong>{{ $aff->client?->nom }}</strong><br>
                    <span style="color:#64748b;font-size:9px">{{ $aff->client?->contact }}</span>
                </td>
                <td style="text-align:center;font-weight:bold">{{ $aff->quantite }}</td>
                <td>{{ $aff->date_affectation?->format('d/m/Y') }}</td>
                <td style="color:#64748b">{{ $aff->heure_affectation }}</td>
                <td>{{ $aff->date_restitution?->format('d/m/Y') ?? '—' }}</td>
                <td>
                    <span class="badge {{ $aff->statut === 'affecte' ? 'badge-blue' : 'badge-gray' }}">
                        {{ $aff->statut === 'affecte' ? 'Affecté' : 'Restitué' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        GestMatériel · Materiel_Institut · Document généré automatiquement le {{ now()->format('d/m/Y') }}
    </div>
</div>

</body>
</html>
