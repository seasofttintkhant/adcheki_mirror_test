<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Setting::create([
            "key" => "srv1_first_ip_id_to_use"
        ]);
        Setting::create([
            "key" => "srv2_first_ip_id_to_use"
        ]);
    }
}
