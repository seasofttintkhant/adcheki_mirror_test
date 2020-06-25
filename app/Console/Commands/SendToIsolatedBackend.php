<?php

namespace App\Console\Commands;

use App\Models\Device;
use Illuminate\Console\Command;

class SendToIsolatedBackend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devices:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send devices that are not downloaded by the app every 10 mintues.';

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
        $devices = Device::where('updated_at', '>=', now())
            ->where('is_checked', 1)
            ->get();
        dd($devices);
    }
}
