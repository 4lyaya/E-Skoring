<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ArcheryScoreController extends Controller
{
    private $distances = [
        '30m' => '30 Meter',
        '50m' => '50 Meter',
        '70m' => '70 Meter'
    ];

    public function index()
    {
        return view('archery.setup', [
            'distances' => $this->distances
        ]);
    }

    public function setup(Request $request)
    {
        $request->validate([
            'num_players' => 'required|integer|min:1|max:10',
            'num_ends' => 'required|integer|min:1|max:12',
            'arrows_per_end' => 'required|integer|min:1|max:6',
            'distance' => 'required|in:30m,50m,70m'
        ]);

        session([
            'tournament_setup' => [
                'num_players' => $request->num_players,
                'num_ends' => $request->num_ends,
                'arrows_per_end' => $request->arrows_per_end,
                'distance' => $request->distance
            ]
        ]);

        return view('archery.input', [
            'numPlayers' => $request->num_players,
            'numEnds' => $request->num_ends,
            'arrowsPerEnd' => $request->arrows_per_end,
            'distance' => $this->distances[$request->distance],
            'players' => range(1, $request->num_players),
            'ends' => range(1, $request->num_ends)
        ]);
    }

    public function calculate(Request $request)
    {
        $setup = session('tournament_setup');

        if (!$setup) {
            return redirect()->route('archery.index');
        }

        $request->validate([
            'player_names' => 'required|array|min:1',
            'player_names.*' => 'required|string|max:100',
            'scores' => 'required|array',
            'scores.*' => 'required|array',
            'scores.*.*' => 'required|array|min:' . $setup['arrows_per_end'],
            'scores.*.*.*' => 'required|integer|min:0|max:10'
        ]);

        $playerNames = $request->player_names;
        $scores = $request->scores;
        $results = [];

        // Hitung skor untuk setiap pemain
        foreach ($playerNames as $playerIndex => $playerName) {
            $playerScores = [];
            $totalScore = 0;

            foreach (range(1, $setup['num_ends']) as $end) {
                $endScores = $scores[$playerIndex][$end] ?? [];
                $endTotal = array_sum($endScores);
                $totalScore += $endTotal;

                $playerScores[$end] = [
                    'arrows' => $endScores,
                    'total' => $endTotal
                ];
            }

            $results[] = [
                'name' => $playerName,
                'scores' => $playerScores,
                'total' => $totalScore
            ];
        }

        // Urutkan berdasarkan total skor (descending)
        usort($results, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        // Generate Excel file
        $filename = $this->generateExcelFile($results, $setup);

        return view('archery.result', [
            'results' => $results,
            'setup' => $setup,
            'distance' => $this->distances[$setup['distance']],
            'filename' => $filename,
            'ends' => range(1, $setup['num_ends'])
        ]);
    }

    private function generateExcelFile($results, $setup)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul
        $sheet->setCellValue('A1', 'TOURNAMENT SCORE SHEET PANAHAN');
        $sheet->mergeCells('A1:' . $this->getColumnName($setup['num_ends'] + 2) . '1');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Informasi turnamen
        $sheet->setCellValue('A3', 'Jarak:');
        $sheet->setCellValue('B3', $this->distances[$setup['distance']]);
        $sheet->setCellValue('A4', 'Jumlah End:');
        $sheet->setCellValue('B4', $setup['num_ends']);
        $sheet->setCellValue('A5', 'Panah per End:');
        $sheet->setCellValue('B5', $setup['arrows_per_end']);
        $sheet->setCellValue('A6', 'Jumlah Pemain:');
        $sheet->setCellValue('B6', count($results));

        // Header tabel
        $headers = ['Peringkat', 'Nama Pemain'];

        // Header untuk setiap end
        foreach (range(1, $setup['num_ends']) as $end) {
            $headers[] = "End $end";
        }

        $headers[] = 'Total Skor';

        // Tulis header
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '8', $header);
            $col++;
        }

        // Data pemain
        $row = 9;
        foreach ($results as $index => $player) {
            $sheet->setCellValue('A' . $row, $index + 1); // Peringkat
            $sheet->setCellValue('B' . $row, $player['name']);

            $col = 'C';
            foreach (range(1, $setup['num_ends']) as $end) {
                $sheet->setCellValue($col . $row, $player['scores'][$end]['total'] ?? 0);
                $col++;
            }

            $sheet->setCellValue($col . $row, $player['total']);
            $row++;
        }

        // Detail skor per panah (sheet kedua)
        $this->createDetailedSheet($spreadsheet, $results, $setup);

        // Styling sheet utama
        $this->applyMainSheetStyles($sheet, $row, count($headers));

        // Save file
        $filename = 'tournament_scores_' . time() . '.xlsx';
        $filepath = storage_path('app/public/' . $filename);

        $writer = new Xlsx($spreadsheet);
        $writer->save($filepath);

        return $filename;
    }

    private function createDetailedSheet($spreadsheet, $results, $setup)
    {
        $detailedSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Detail Skor');
        $spreadsheet->addSheet($detailedSheet, 1);

        // Header untuk detail
        $detailedSheet->setCellValue('A1', 'DETAIL SKOR PER PANAH');
        $detailedSheet->mergeCells('A1:' . $this->getColumnName($setup['arrows_per_end'] + 2) . '1');
        $detailedSheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
        $detailedSheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 3;
        foreach ($results as $index => $player) {
            $detailedSheet->setCellValue('A' . $row, 'Pemain: ' . $player['name']);
            $detailedSheet->mergeCells('A' . $row . ':' . $this->getColumnName($setup['arrows_per_end'] + 1) . $row);
            $detailedSheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;

            // Header detail
            $headers = ['End'];
            for ($i = 1; $i <= $setup['arrows_per_end']; $i++) {
                $headers[] = "Panah $i";
            }
            $headers[] = 'Total';

            $col = 'A';
            foreach ($headers as $header) {
                $detailedSheet->setCellValue($col . $row, $header);
                $col++;
            }
            $row++;

            // Data detail
            foreach (range(1, $setup['num_ends']) as $end) {
                $detailedSheet->setCellValue('A' . $row, $end);
                $col = 'B';
                foreach ($player['scores'][$end]['arrows'] as $arrowScore) {
                    $detailedSheet->setCellValue($col . $row, $arrowScore);
                    $col++;
                }
                $detailedSheet->setCellValue($col . $row, $player['scores'][$end]['total']);
                $row++;
            }
            $row += 2; // Spasi antar pemain
        }

        // Styling detail sheet
        $this->applyDetailedSheetStyles($detailedSheet, $setup);
    }

    private function applyMainSheetStyles($sheet, $lastRow, $numHeaders)
    {
        // Border untuk seluruh tabel
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('A8:' . $this->getColumnName($numHeaders) . $lastRow)->applyFromArray($styleArray);

        // Header style
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE6E6FA']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A8:' . $this->getColumnName($numHeaders) . '8')->applyFromArray($headerStyle);

        // Alignment
        $sheet->getStyle('A8:' . $this->getColumnName($numHeaders) . $lastRow)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Auto size columns
        foreach (range('A', $this->getColumnName($numHeaders)) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Warna untuk peringkat
        for ($i = 9; $i <= $lastRow; $i++) {
            $fillColor = 'FFFFFFFF'; // Putih default

            if ($i == 9)
                $fillColor = 'FFFFFF00'; // Kuning untuk juara 1
            elseif ($i == 10)
                $fillColor = 'FFC0C0C0'; // Silver untuk juara 2
            elseif ($i == 11)
                $fillColor = 'FFCD7F32'; // Perunggu untuk juara 3

            $sheet->getStyle('A' . $i . ':' . $this->getColumnName($numHeaders) . $i)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor(['argb' => $fillColor]);
        }
    }

    private function applyDetailedSheetStyles($sheet, $setup)
    {
        // Auto size columns
        foreach (range('A', $this->getColumnName($setup['arrows_per_end'] + 2)) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    private function getColumnName($index)
    {
        $letters = '';
        while ($index > 0) {
            $index--;
            $letters = chr(65 + ($index % 26)) . $letters;
            $index = intval($index / 26);
        }
        return $letters;
    }

    public function download($filename)
    {
        $filepath = storage_path('app/public/' . $filename);

        if (!file_exists($filepath)) {
            abort(404);
        }

        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}