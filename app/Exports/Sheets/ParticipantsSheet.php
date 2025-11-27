<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Event;

class ParticipantsSheet implements FromArray, WithHeadings, WithTitle, WithEvents
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function title(): string
    {
        return 'Participants';
    }

    public function headings(): array
    {
        return ['Participant', 'Category', 'Unique ID', 'Payment', 'Date'];
    }

    public function array(): array
    {
        $participants = $this->event->pesertas()->get();

        $data = [];

        foreach ($participants as $p) {
            $payment = $p->pivot->status_bayaran === 'complete' ? 'Completed' : 'Pending';
            $date = $p->pivot->created_at ? $p->pivot->created_at->format('d/m/Y') : '-';

            $data[] = [
                $p->nama_penuh,
                $p->pivot->kategori_nama,
                $p->pivot->unique_id,
                $payment,
                $date,
            ];
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Wrap text untuk nama peserta
                $sheet->getStyle("A2:A{$highestRow}")->getAlignment()->setWrapText(true);

                // Auto size semua column
                foreach (range('A', 'E') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
