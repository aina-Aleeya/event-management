<?php

namespace App\Exports\Sheets;

use App\Models\RankingReport;
use App\Models\Group;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GroupRankingSheet implements FromArray, WithHeadings, WithTitle, WithEvents
{
    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function title(): string
    {
        return 'Berkumpulan';
    }

    public function headings(): array
    {
        return ['Ranking', 'Nama Group', 'Ahli Kumpulan', 'Kategori'];
    }

    public function array(): array
    {
        $rankingReports = RankingReport::with('penyertaan')
            ->where('event_id', $this->event->id)
            ->whereHas('penyertaan', fn($q) => $q->where('kategori', 'like', 'G%'))
            ->orderBy('ranking')
            ->get();

        $data = [];

        foreach ($rankingReports as $r) {
            $gp = \DB::table('group_peserta')
                ->where('event_id', $this->event->id)
                ->where('peserta_id', $r->penyertaan->peserta_id)
                ->first();

            if (!$gp) continue;

            $group = Group::with('pesertas')->find($gp->group_id);
            if (!$group) continue;

            $ahliArray = $group->pesertas->pluck('nama_penuh')->toArray();

            foreach ($ahliArray as $index => $ahli) {
                $data[] = [
                    $index === 0 ? $r->ranking : '', // Ranking hanya di first row
                    $index === 0 ? $group->name : '', // Nama group merge vertical
                    $ahli,
                    $index === 0 ? 'Berkumpulan' : '', // Kategori merge vertical
                ];
            }
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Merge cells untuk group & kategori
                $rowStart = 2; // row pertama selepas heading
                while ($rowStart <= $highestRow) {
                    $groupName = $sheet->getCell("B$rowStart")->getValue();
                    if ($groupName) {
                        $mergeEnd = $rowStart;
                        while ($mergeEnd + 1 <= $highestRow && $sheet->getCell("B" . ($mergeEnd + 1))->getValue() === '') {
                            $mergeEnd++;
                        }

                        if ($mergeEnd > $rowStart) {
                            $sheet->mergeCells("B{$rowStart}:B{$mergeEnd}");
                            $sheet->mergeCells("D{$rowStart}:D{$mergeEnd}");
                            // Kalau nak merge Ranking juga, uncomment:
                            // $sheet->mergeCells("A{$rowStart}:A{$mergeEnd}");
                        }

                        $rowStart = $mergeEnd + 1;
                    } else {
                        $rowStart++;
                    }
                }

                // Wrap text untuk column C (Ahli)
                $sheet->getStyle("C2:C{$highestRow}")->getAlignment()->setWrapText(true);

                // Auto size untuk semua column
                foreach (range('A', 'D') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
