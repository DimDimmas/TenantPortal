<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ReportActualVsScheduleExport implements FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;   
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('preventives.report_actual_vs_schedules.excel', [
            'data' => $this->data
        ]);
    }
}
