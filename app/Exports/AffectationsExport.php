<?php

namespace App\Exports;

use App\Models\Affectation;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AffectationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(private int $gestionnaireId) {}

    public function collection()
    {
        return Affectation::with(['materiel', 'client'])
            ->where('gestionnaire_id', $this->gestionnaireId)
            ->orderByDesc('date_affectation')
            ->get();
    }

    public function title(): string
    {
        return 'Affectations';
    }

    public function headings(): array
    {
        return [
            'ID', 'Matériel', 'Code', 'Client', 'Contact',
            'Quantité', 'Date Affectation', 'Heure', 'Statut', 'Date Restitution',
        ];
    }

    public function map($aff): array
    {
        return [
            $aff->id,
            $aff->materiel?->designation ?? '-',
            $aff->materiel?->code_identification ?? '-',
            $aff->client?->nom ?? '-',
            $aff->client?->contact ?? '-',
            $aff->quantite,
            $aff->date_affectation?->format('d/m/Y') ?? '-',
            $aff->heure_affectation ?? '-',
            $aff->statut_label,
            $aff->date_restitution?->format('d/m/Y') ?? '-',
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