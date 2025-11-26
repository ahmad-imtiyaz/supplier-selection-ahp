<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RankingExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rankings;
    protected $criterias;

    public function __construct($rankings, $criterias)
    {
        $this->rankings = $rankings;
        $this->criterias = $criterias;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->rankings as $ranking) {
            $row = [
                $ranking['rank'],
                $ranking['supplier']->code,
                $ranking['supplier']->name,
            ];

            // Add criteria scores
            foreach ($this->criterias as $criteria) {
                $criteriaScore = $ranking['criteria_scores'][$criteria->id] ?? null;
                $row[] = $criteriaScore ? number_format($criteriaScore['raw_score'], 2) : '-';
                $row[] = $criteriaScore ? number_format($criteriaScore['weighted_score'], 4) : '-';
            }

            // Add total
            $row[] = number_format($ranking['total_score'], 4);
            $row[] = number_format($ranking['percentage'], 2) . '%';

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $headings = ['Rank', 'Kode', 'Nama Supplier'];

        foreach ($this->criterias as $criteria) {
            $headings[] = $criteria->code . ' (Raw)';
            $headings[] = $criteria->code . ' (Weighted)';
        }

        $headings[] = 'Total Score';
        $headings[] = 'Percentage';

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 8,  // Rank
            'B' => 12, // Kode
            'C' => 25, // Nama
        ];

        $col = 'D';
        foreach ($this->criterias as $criteria) {
            $widths[$col++] = 12; // Raw
            $widths[$col++] = 12; // Weighted
        }

        $widths[$col++] = 15; // Total
        $widths[$col] = 12;   // Percentage

        return $widths;
    }
}
