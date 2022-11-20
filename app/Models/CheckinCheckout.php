<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckinCheckout extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'checkin_time', 'checkout_time'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
