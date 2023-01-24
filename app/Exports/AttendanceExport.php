<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $start_date;
    protected $end_date;
    private $row = 0;

    function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function model(array $row)
    {
        ++$this->row;
    }

    public function collection()
    {

        $data = Attendance::with('user.employee')
            ->whereBetween('created_at', [$this->start_date, $this->end_date])
            ->get();

        return $data;
    }

    /**
     * @var Attendance $attendance
     */
    public function map($attendance): array
    {
        return [
            ++$this->row,
            $attendance->user->employee->name,
            $attendance->type(),
            $attendance->time,
            $attendance->status()
        ];
    }

    public function headings(): array
    {
        return [
            'NO.',
            'NAMA',
            'JENIS ABSEN',
            'WAKTU ABSEN',
            'STATUS ABSEN'
        ];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D9D9D9']
                    ]
                ];


                $event->sheet->getStyle('A2:E2')->applyFromArray($styleArray);
            },
        ];
    }
}
