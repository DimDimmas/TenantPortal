<?php

namespace App\Jobs;

use App\Services\Preventives\PreventiveRefreshChecklistService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProccessRefreshCheckListPreventiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $service;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->service = new PreventiveRefreshChecklistService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('max_execution_time', '9999999999999999999999');
        $this->service->proccess();
    }
}
