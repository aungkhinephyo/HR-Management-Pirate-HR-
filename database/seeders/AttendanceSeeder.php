<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use App\Models\CheckinCheckout;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            $periods = new CarbonPeriod('2022-7-01', '2022-10-31');
            foreach ($periods as $period) {
                if ($period->format('D') !== 'Sat' && $period->format('D') !== 'Sun') {
                    CheckinCheckout::create([
                        'user_id' => $user->id,
                        'date' => $period->format('Y-m-d'),
                        'checkin_time' => Carbon::parse($period->format('Y-m-d') . ' ' . '09:10:00')->subMinutes(rand(1, 55)),
                        'checkout_time' => Carbon::parse($period->format('Y-m-d') . ' ' . '17:57:00')->addMinutes(rand(1, 55)),
                    ]);
                }
            }
        }
    }
}
