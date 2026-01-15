<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Barryvdh\DomPDF\Facade\Pdf;

class RankingExportController extends Controller
{
    
    // Export rankings to Google Sheets
     
public function exportSheet(Request $request, $eventId)
{
    $event = Event::findOrFail($eventId);
    $selectedCategory = $request->input('category');

    // Get all scores
    $allScores = Score::where('event_id', $eventId)
        ->with(['peserta', 'group'])
        ->whereNotNull('average')
        ->get();

    // Add category to each score
    foreach ($allScores as $score) {
        $categoryData = DB::table('penyertaan')
            ->leftJoin('categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
            })
            ->leftJoin('custom_categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
            })
            ->where('penyertaan.event_id', $eventId)
            ->where('penyertaan.peserta_id', $score->peserta_id)
            ->select(DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
            ->first();
        
        $score->category = $categoryData->category ?? 'Uncategorized';
    }

    // Create spreadsheet
    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0); 

    $sheetIndex = 0;

    if (!$selectedCategory) {
        $sheet = $spreadsheet->createSheet($sheetIndex);
        $sheet->setTitle('Overall Ranking');

   
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Set headers
        $sheet->setCellValue('A1', 'Rank');
        $sheet->setCellValue('B1', 'Participant Name');
        $sheet->setCellValue('C1', 'Category');
        $sheet->setCellValue('D1', 'Group');
        $sheet->setCellValue('E1', 'Round 1');
        $sheet->setCellValue('F1', 'Round 2');
        $sheet->setCellValue('G1', 'Round 3');
        $sheet->setCellValue('H1', 'Average');

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(12);

     
        $overallScores = $allScores->sortByDesc('average')->values();
        
      
        $overallScores = $overallScores->map(function($score, $index) {
            $score->rank = $index + 1;
            return $score;
        });

  
        $row = 2;
        foreach ($overallScores as $score) {
            $sheet->setCellValue('A' . $row, $score->rank);
            $sheet->setCellValue('B' . $row, $score->peserta->nama_penuh);
            $sheet->setCellValue('C' . $row, $score->category);
            $sheet->setCellValue('D' . $row, $score->group->name);
            $sheet->setCellValue('E' . $row, number_format($score->round1, 2));
            $sheet->setCellValue('F' . $row, $score->round2 ? number_format($score->round2, 2) : '-');
            $sheet->setCellValue('G' . $row, $score->round3 ? number_format($score->round3, 2) : '-');
            $sheet->setCellValue('H' . $row, number_format($score->average, 2));

            // Highlight top 3
            if ($score->rank <= 3) {
                $bgColor = $score->rank === 1 ? 'FEF3C7' : ($score->rank === 2 ? 'E5E7EB' : 'FED7AA');
                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                ]);
            }

            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]]
            ]);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row . ':H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        $sheetIndex++;
    }

    // Group scores by category
    $scoresByCategory = $allScores->groupBy('category');

    // If specific category selected, filter
    if ($selectedCategory) {
        $scoresByCategory = $scoresByCategory->filter(function($scores, $category) use ($selectedCategory) {
            return $category === $selectedCategory;
        });
    }

 
    foreach ($scoresByCategory as $category => $categoryScores) {

        $categoryScores = $categoryScores->sortByDesc('average')->values();

        $categoryScores = $categoryScores->map(function($score, $index) {
            $score->rank = $index + 1;
            return $score;
        });

        // Create new sheet
        $sheet = $spreadsheet->createSheet($sheetIndex);
        $sheet->setTitle(substr($category, 0, 31));

        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Set headers
        $sheet->setCellValue('A1', 'Rank');
        $sheet->setCellValue('B1', 'Participant Name');
        $sheet->setCellValue('C1', 'Category');
        $sheet->setCellValue('D1', 'Group');
        $sheet->setCellValue('E1', 'Round 1');
        $sheet->setCellValue('F1', 'Round 2');
        $sheet->setCellValue('G1', 'Round 3');
        $sheet->setCellValue('H1', 'Average');

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(12);

        // Add data
        $row = 2;
        foreach ($categoryScores as $score) {
            $sheet->setCellValue('A' . $row, $score->rank);
            $sheet->setCellValue('B' . $row, $score->peserta->nama_penuh);
            $sheet->setCellValue('C' . $row, $score->category);
            $sheet->setCellValue('D' . $row, $score->group->name);
            $sheet->setCellValue('E' . $row, number_format($score->round1, 2));
            $sheet->setCellValue('F' . $row, $score->round2 ? number_format($score->round2, 2) : '-');
            $sheet->setCellValue('G' . $row, $score->round3 ? number_format($score->round3, 2) : '-');
            $sheet->setCellValue('H' . $row, number_format($score->average, 2));

            // Highlight top 3
            if ($score->rank <= 3) {
                $bgColor = $score->rank === 1 ? 'FEF3C7' : ($score->rank === 2 ? 'E5E7EB' : 'FED7AA');
                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
                ]);
            }

            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]]
            ]);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row . ':H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        $sheetIndex++;
    }

    // Set active sheet to first
    $spreadsheet->setActiveSheetIndex(0);

    // Generate filename
    $filename = str_replace(' ', '_', $event->title);
    if ($selectedCategory) {
        $filename .= '_' . str_replace(' ', '_', $selectedCategory);
    }
    $filename .= '_Rankings_' . now()->format('Y-m-d') . '.xlsx';

    // Save and download
    $writer = new Xlsx($spreadsheet);
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
}


 // Export rankings to PDF
 
public function exportPdf(Request $request, $eventId)
{
    $event = Event::findOrFail($eventId);
    $selectedCategory = $request->input('category');

    // Get all scores
    $allScores = Score::where('event_id', $eventId)
        ->with(['peserta', 'group'])
        ->whereNotNull('average')
        ->get();

    // Add category to each score
    foreach ($allScores as $score) {
        $categoryData = DB::table('penyertaan')
            ->leftJoin('categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
            })
            ->leftJoin('custom_categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
            })
            ->where('penyertaan.event_id', $eventId)
            ->where('penyertaan.peserta_id', $score->peserta_id)
            ->select(DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
            ->first();
        
        $score->category = $categoryData->category ?? 'Uncategorized';
    }

    // Overall ranking (if no specific category)
    $overallScores = null;
    if (!$selectedCategory) {
        $overallScores = $allScores->sortByDesc('average')->values()->map(function($score, $index) {
            $score->rank = $index + 1;
            return $score;
        });
    }

    // Group scores by category
    $scoresByCategory = $allScores->groupBy('category');

    // If specific category selected, filter
    if ($selectedCategory) {
        $scoresByCategory = $scoresByCategory->filter(function($scores, $category) use ($selectedCategory) {
            return $category === $selectedCategory;
        });
    }

    // Sort and rank each category
    foreach ($scoresByCategory as $category => $categoryScores) {
        $sorted = $categoryScores->sortByDesc('average')->values();
        $scoresByCategory[$category] = $sorted->map(function($score, $index) {
            $score->rank = $index + 1;
            return $score;
        });
    }

    // Generate PDF
    $pdf = PDF::loadView('pdf.rankings-export', [
        'event' => $event,
        'overallScores' => $overallScores,
        'scoresByCategory' => $scoresByCategory,
        'selectedCategory' => $selectedCategory
    ])->setPaper('a4', 'portrait');

    $filename = str_replace(' ', '_', $event->title);
    if ($selectedCategory) {
        $filename .= '_' . str_replace(' ', '_', $selectedCategory);
    }
    $filename .= '_Rankings_' . now()->format('Y-m-d') . '.pdf';

    return $pdf->download($filename);
}
}