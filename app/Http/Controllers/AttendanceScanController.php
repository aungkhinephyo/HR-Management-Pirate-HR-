<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckinCheckout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AttendanceScanController extends Controller
{
    public function scan()
    {
        return view('app.attendance_scan');
    }

    public function scanStore(Request $request)
    {
        if (now()->format('D') == 'Sat' || now()->format('D') == 'Sun') {
            return [
                'status' => 'fail',
                'message' => 'Today is off day.',
            ];
        }

        if (!Hash::check(date('Y-m-d'), $request->hash_value)) {
            return [
                'status' => 'fail',
                'message' => 'QR code is invalid.',
            ];
        }

        $user = Auth::user();

        $checkin_checkout_data = CheckinCheckout::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => now()->format('Y-m-d')
            ]
        );

        if (!is_null($checkin_checkout_data->checkin_time) && !is_null($checkin_checkout_data->checkout_time)) {
            return [
                'status' => 'fail',
                'message' => 'Already check in and check out today.',
            ];
        }

        if (is_null($checkin_checkout_data->checkin_time)) {
            $checkin_checkout_data->checkin_time = now();
            $message = 'Successfully checked in at ' . now() . '.';
        } else {
            if (is_null($checkin_checkout_data->checkout_time)) {
                $checkin_checkout_data->checkout_time = now();
                $message = 'Successfully checked out at ' . now() . '.';
            }
        }

        $checkin_checkout_data->update();

        return [
            'status' => 'success',
            'message' => $message,
        ];
    }
}
