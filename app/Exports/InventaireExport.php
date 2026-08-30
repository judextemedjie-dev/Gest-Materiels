<?php

namespace App\Exports;

use App\Models\Materiel;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventaireExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return Materiel::with('categorie')->orderBy('designation')->get();
    }

    public function title(): string
    {
        return 'Inventaire';
    }

    public function headings(): array
    {
        return [
            'ID', 'Désignation', 'Code Identification', 'Catégorie',
            'Quantité Stock', 'Statut', 'Localisation', 'Date Ajout',
        ];
    }

    public function map($materiel): array
    {
        return [
            $materiel->id,
            $materiel->designation,
            $materiel->code_identification,
            $materiel->categorie?->nom ?? '-',
            $materiel->quantite_stock,
            $materiel->statut_label,
            $materiel->localisation ?? '-',
            $materiel->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                  'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1E3A5F']]],
        ];
    }
}