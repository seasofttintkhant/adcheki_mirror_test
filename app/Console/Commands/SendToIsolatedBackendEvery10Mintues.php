<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\Email;
use Illuminate\Console\Command;

class SendToIsolatedBackendEvery10Mintues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devices:sendeverytenminutes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send device data every 10 mintues to the isolated server.';

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
        Email::whereHas("device", function($q){
                $q->where("is_checked",1);
            })->groupBy('email')
            ->chunk(100, function ($emails) {
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
                    'emails' => $emails,
                    'secret_key' => env('ISOLATED_BACKEND_SECRET_KEY')
                ]));
                $result = curl_exec($ch);
                $result = json_decode($result, true);
                curl_close($ch);
            });
    }
}
