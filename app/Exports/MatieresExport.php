<?php

namespace App\Exports;

use App\Models\Matiere;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MatieresExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected ?Builder $query;

    public function __construct(?Builder $query = null)
    {
        $this->query = $query;
    }

    public function collection(): Collection
    {
        $base = $this->query
            ? clone $this->query
            : Matiere::query()->orderBy('sous_famille_id');

        $matieres = $base->with([
            'unite',
            'sousFamille',
            'material',
            'standardVersion.standard',
            'fournisseurs',
        ])->get();

        return $matieres->map(fn($m) => [
            'ref_interne'         => $m->ref_interne,
            'designation'         => $m->designation,
            'unite'               => $m->unite->short ?? '',
            'sous_famille'        => $m->sousFamille->nom ?? '',
            'dn'                  => $m->dn ?? '',
            'epaisseur'           => $m->epaisseur ?? '',
            'longueur'            => $m->longueur ?? '',
            'standard'            => $m->standardVersion->standard->nom ?? '',
            'ref_valeur_unitaire' => $m->ref_valeur_unitaire ?? '',
            'materiau'            => $m->material->nom ?? '',
            'prix'                => $m->prix ?? '',
            'fournisseur'         => $m->fournisseurs->first()?->raison_sociale ?? '',
        ]);
    }

    public function headings(): array
    {
        return [
            'ref_interne',
            'designation',
            'unite',
            'sous_famille',
            'dn',
            'epaisseur',
            'longueur',
            'standard',
            'ref_valeur_unitaire',
            'materiau',
            'prix',
            'fournisseur',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FF1F3864']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD9E1F2'],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // ref_interne
            'B' => 42,  // designation
            'C' => 10,  // unite
            'D' => 26,  // sous_famille
            'E' => 8,   // dn
            'F' => 11,  // epaisseur
            'G' => 12,  // longueur
            'H' => 22,  // standard
            'I' => 20,  // ref_valeur_unitaire
            'J' => 16,  // materiau
            'K' => 12,  // prix
            'L' => 28,  // fournisseur
        ];
    }

    public function title(): string
    {
        return 'Matières';
    }
}
