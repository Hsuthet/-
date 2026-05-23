<?php

namespace App\Http\Controllers;

use App\Models\BusinessRequest;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TaskExportController extends Controller
{
    public function exportExcel()
    {
        $user = Auth::user();

        $tasks = BusinessRequest::with(['user', 'worker'])
            ->when($user->role === 'employee', function ($query) use ($user) {
                $query->where('worker_id', $user->id);
            })
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'A1' => '依頼番号',
            'B1' => '件名',
            'C1' => '依頼者',
            'D1' => '担当者',
            'E1' => '期限',
            'F1' => 'ステータス',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Header Style
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '1E293B'],
            ],
        ]);

        $row = 2;

      $statusMap = [
    'PENDING' => ['label' => '承認待ち', 'color' => 'F59E0B'],
    'APPROVED' => ['label' => '承認済み', 'color' => '3B82F6'],
    'WORKING' => ['label' => '作業中', 'color' => '6366F1'],
    'COMPLETED' => ['label' => '完了', 'color' => '10B981'],
    'REJECTED' => ['label' => '却下', 'color' => 'EF4444'],
];

        foreach ($tasks as $task) {

            $status = $statusMap[$task->status] ?? [
                'label' => $task->status,
                'color' => '64748B'
            ];

            $sheet->setCellValue('A'.$row, $task->request_number);
            $sheet->setCellValue('B'.$row, $task->title);
            $sheet->setCellValue('C'.$row, $task->user?->name);
            $sheet->setCellValue('D'.$row, $task->worker?->name);
            $sheet->setCellValue('E'.$row, $task->due_date);
            $sheet->setCellValue('F'.$row, $status['label']);

            // Status Color
            $sheet->getStyle('F'.$row)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => $status['color']],
                ],
            ]);

            $row++;
        }

        // Auto Size
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'tasks.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }
}