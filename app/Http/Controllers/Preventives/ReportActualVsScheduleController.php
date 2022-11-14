<?php

namespace App\Http\Controllers\Preventives;

use App\Exports\ReportActualVsScheduleExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Preventives\ReportActualVsScheduleService;
use Maatwebsite\Excel\Facades\Excel;

class ReportActualVsScheduleController extends Controller
{
    private $service;

    public function __construct(ReportActualVsScheduleService $service)
    {
        $this->service = $service;
    }
    
    public function index() {
        return view("preventives.report_actual_vs_schedules.index");
    }

    public function tableIndex(Request $request) {
        $data = $this->service->generateData($request);
        $html = $this->service->convertToHtmlTable($data['data'], $data['months']);

        $result = [
            "html" => $html,
            "total_days" => $data['total_days'],
            "descs" => $data['descs'],
        ];

        return response()->json($result, 200);
    }

    public function exportExcel(Request $request) {
        $request->date = base64_decode($request->date);
        $data = $this->service->generateData($request);
        $html = $this->service->convertToHtmlTable($data['data'], $data['months']);

        $result = [
            "html" => $html,
            "total_days" => $data['total_days'],
            "descs" => $data['descs'],
        ];
        return Excel::download(new ReportActualVsScheduleExport($result), 'report-actual-vs-schedule-preventive.xlsx');
    }
}
