<?php

namespace KgBot\RackbeatDashboard\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use KgBot\RackbeatDashboard\Classes\DashboardJobExport;
use KgBot\RackbeatDashboard\Models\Job;
use Maatwebsite\Excel\Excel;

class ClearOldJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rb-integration-dashboard:clear-old-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export old jobs and delete them from database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Backup old jobs
        $file = fopen(storage_path('jobs-' . Carbon::now()->toDateString() . '.csv'), 'w');
        
        // Headers
        fputcsv($file, [
            'id', 
            'title',
            'queue',
            'payload',
            'delay',
            'report',
            'state',
            'progress',
            'command',
            'attempts',
            'created_by',
            'created_at',
            'finished_at',
            'args',
        ]);
        
        Job::whereState('success')->whereDate('created_at', '<', Carbon::now()->subDays(7))->each(function ($job) use($file) {
            fputcsv($file, $job->toArray());
        });
        
        fclose($file);
        
        // Delete after backing up
        Job::whereState('success')->whereDate('created_at', '<', Carbon::now()->subDays(7))->delete();
        
        return true;
    }
}
