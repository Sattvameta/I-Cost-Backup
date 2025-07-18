<?php

namespace App\Exports;

use Log;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Modules\ProjectManager\Entities\Project;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\TimesheetManager\Entities\LabourTimesheet;
class ExportLabourTimesheet implements FromView, WithEvents
{
    use Exportable;

    private $projectId;

    /** New class instance
    * @param int $projectId
    */
    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:L1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '007bff']
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => '007bff'],
                        ]
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A3:L50')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'd4d4d4']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
    
                $event->sheet->getDelegate()->getDefaultRowDimension()->setRowHeight(40);
                $event->sheet->getDelegate()->getDefaultColumnDimension()->setWidth(15);
                
            },
        ];
    }

    /** Process collection
    * @return View
    */
    public function view(): View
    {
        $project = Project::find($this->projectId);
		if($project){
        $labourTimesheets = $project->labourTimesheets()
                        //->whereRaw('id = (select max(id) from labour_timesheets)')
                        ->groupBy('activity_id')
                        ->orderBy('id', 'DESC')
                        ->get();
						
						//print_r($labourTimesheets);
						//exit();
        return view('reportmanager::labour_timesheet_report_list_excel', [
            'project' => $project,
            'labourTimesheets'=> $labourTimesheets
        ]);
    }
    }

}
