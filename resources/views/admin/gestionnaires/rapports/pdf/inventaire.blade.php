{{-- resources/views/gestionnaire/rapports/pdf/inventaire.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inventaire — GestMatériel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        .header { background: #1e3a5f; color: white; padding: 18px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 10px; opacity: 0.8; margin-top: 3px; }
        .header .date { font-size: 10px; opacity: 0.7; margin-top: 2px; }
        .summary { display: flex; gap: 20px; margin: 0 24px 16px; padding: 10px 14px; background: #f1f5f9; border-radius: 6px; }
        .summary span { font-size: 11px; color: #475569; }
        .summary strong { color: #1e293b; }
        table { width: 100%; border-collapse: collapse; margin: 0 0 20px; }
        thead { background: #1e3a5f; color: white; }
        thead th { padding: 9px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody td { padding: 7px 10px; font-size: 10px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 9px; font-weight: bold; }
        .badge-green  { background: #dcfce7; color: #166534; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-orange { background: #ffedd5; color: #9a3412; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .stock-low { color: #dc2626; font-weight: bold; }
        .footer { text-align: center; font-size: 9px; color: #94a3b8; margin-top: 10px; padding-top: 10px; border-top: 1px solid #e2e8f0; }
        .page-body { padding: 0 24px; }
    </style>
</head>
<body>

<div class="header">
    <h1><strong>GestMatériel</strong> — Rapport d'Inventaire</h1>
    <p>Inventaire complet du parc matériel institutionnel</p>
    <p class="date">Généré le {{ now()->format('d/m/Y à H:i:s') }}</p>
</div>

<div class="summary">
    <span>Total articles : <strong>{{ $materiels->count() }}</strong></span>
    <span>Quantité totale : <strong>{{ $materiels->sum('quantite_stock') }} unités</strong></span>
    <span>En service : <strong>{{ $materiels->where('statut','en_service')->count() }}</strong></span>
    <span>En panne : <strong>{{ $materiels->where('statut','en_panne')->count() }}</strong></span>
</div>

<div class="page-body">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Désignation</th>
                <th>Code ID</th>
                <th>Catégorie</th>
                <th style="text-align:center">Stock</th>
                <th>Statut</th>
                <th>Localisation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materiels as $i => $m)
            <tr>
                <td style="color:#94a3b8">{{ $i + 1 }}</td>
                <td><strong>{{ $m->designation }}</strong></td>
                <td style="font-family:monospace;font-size:9px;color:#64748b">{{ $m->code_identification }}</td>
                <td>{{ $m->categorie?->nom ?? '—' }}</td>
                <td style="text-align:center" class="{{ $m->quantite_stock <= 2 ? 'stock-low' : '' }}">{{ $m->quantite_stock }}</td>
                <td>
                    @php
                        $cls = match($m->statut) {
                            'en_service'    => 'badge-green',
                            'en_panne'      => 'badge-red',
                            'en_reparation' => 'badge-orange',
                            default         => 'badge-gray',
                        };
                        $lbl = match($m->statut) {
                            'en_service'    => 'En service',
                            'en_panne'      => 'En panne',
                            'en_reparation' => 'En réparation',
                            default         => 'Archivé',
                        };
                    @endphp
                    <span class="badge {{ $cls }}">{{ $lbl }}</span>
                </td>
                <td>{{ $m->localisation ?? '—' }}</td>
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