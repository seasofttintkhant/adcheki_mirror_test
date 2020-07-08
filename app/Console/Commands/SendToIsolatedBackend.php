<?php

namespace App\Console\Commands;

use Carbon\Carbon;
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
        $dueTime = Carbon::now()->subDay();
        Device::where('updated_at', '<=', $dueTime)
            ->with(['contacts', 'emails'])
            ->where('is_checked', 1)
            ->chunk(100, function ($dueDevices) {
                foreach ($dueDevices as $device) {
                    if ($device !== null) {
                        $header = [
                            'Accept: application/json',
                            'Content-Type: application/json'
                        ];

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, env('ISOLATED_BACKEND_URL'));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                            'device' => $device,
                            'secret_key' => env('ISOLATED_BACKEND_SECRET_KEY')
                        ]));
                        $result = curl_exec($ch);
                        $result = json_decode($result, true);
                        curl_close($ch);
                        if ($result['status'] === 'success') {
                            $device->delete();
                        }
                    }
                }
            });
    }
}
