<?php

namespace App\Exports\Sheets;

use App\Models\RankingReport;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class IndividuRankingSheet implements FromArray, WithHeadings, WithTitle, WithEvents
{
    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function title(): string
    {
        return 'Individu';
    }

    public function headings(): array
    {
        return ['Ranking', 'Nama Peserta', 'Kategori'];
    }

    public function array(): array
    {
        return RankingReport::with('penyertaan.peserta')
            ->where('event_id', $this->event->id)
            ->whereHas('penyertaan', fn($q) => $q->where('kategori', 'like', 'I%'))
            ->orderBy('ranking')
            ->get()
            ->map(function ($r) {
                return [
                    $r->ranking,
                    $r->penyertaan->peserta->nama_penuh,
                    $r->penyertaan->kategori,
                ];
            })
            ->toArray();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Wrap text untuk column Nama Peserta
                $sheet->getStyle("B2:B{$highestRow}")->getAlignment()->setWrapText(true);

                // Auto size untuk semua column
                foreach (range('A', 'C') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
