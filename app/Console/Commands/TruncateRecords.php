<?php

namespace App\Console\Commands;

use App\Models\Audit;
use App\Models\Contact;
use App\Models\Device;
use App\Models\Email;
use App\Models\Job;
use App\Models\ProcessingIp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        Audit::truncate();
        Contact::truncate();
        Device::truncate();
        Email::truncate();
        DB::table("failed_jobs")->truncate();
        Job::truncate();
        ProcessingIp::truncate();
        DB::table("used_ips")->truncate();
    }
}
