<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'company_name' => 'Pirate Company',
            'company_email' => 'piratecompany@gmail.com',
            'company_phone' => '09980302061',
            'company_address' => 'No.(22) 5th Floor, Pirate Condo, allblue Township',
            'office_start_time' => '09:00:00',
            'office_end_time' => '18:00:00',
            'break_start_time' => '12:00:00',
            'break_end_time' => '13:00:00',
        ];
        CompanySetting::create($settings);
    }
}
