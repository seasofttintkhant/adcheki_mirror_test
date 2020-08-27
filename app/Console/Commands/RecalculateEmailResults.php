<?php

namespace App\Console\Commands;

use App\Models\Email;
use Illuminate\Console\Command;

class RecalculateEmailResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:result';

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
        Email::chunk(100, function($emails){
            foreach($emails as $email){
                $email->ok = $this->calcResult($email->is_valid, $email->status)[0];
                $email->ng = $this->calcResult($email->is_valid, $email->status)[1];
                $email->unknown = $this->calcResult($email->is_valid, $email->status)[2];
                $email->update();
            }
        });
    }

    public function calcResult($is_valid, $is_exist){
        $ok = 0;
        $ng = 0;
        $unknown = 0;
        if($is_valid == 0 || $is_exist == 1){
            $ng = 1;
        }else if($is_valid == 1 || $is_exist == 2){
            $ok = 1;
        }else if($is_valid == 1 || $is_exist == 1){
            $ng = 1;
        }else if($is_valid == 1 || $is_exist == 0){
            $unknown = 1;
        }
        return [$ok, $ng, $unknown];
    }
}
