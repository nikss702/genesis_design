<?php

namespace App\Exports;

use App\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Carbon;

class ProjectExportExcel implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $startDate = request()->input('from_date') ;
        $endDate   = request()->input('to_date');
        $status = request()->input('status');
        //return Project::whereBetween('created_at', [ $startDate, $endDate ] )->get();
        if($startDate == "" && $endDate == "" && $status == ""){
            return Project::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
        }elseif($startDate == "" || $endDate == ""){
            return Project::where('project_status', $status)->get();
        }elseif($status == ""){
            return Project::whereBetween('created_at', [ $startDate, $endDate ])->get();
        }else{
            return Project::where('project_status', $status)
                            ->whereBetween('created_at', [ $startDate, $endDate ])->get();
        }
    }
    public function headings(): array{
        return [
            'id',
            'Name',
            'Description',
            'Street_1',
            'Street_2',
            'City',
            'State',
            'Zip',
            'Country',
            'Latitude',
            'Longitude',
            'Status',
            'Project_Status',
            'Customer_ID',
            'Engineer_ID',
            'Company_ID',
            'Project_ID',
            'Assigned_Date',
            'Created_at',
            'Updated_at',
        ];
    }
    public function styles(Worksheet $sheet){
        return [
            1 => ['font' => ['bold' => true],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
