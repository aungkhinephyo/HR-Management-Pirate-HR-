<?php

namespace App\Models;

use App\Models\Salary;
use App\Models\Department;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laragear\WebAuthn\WebAuthnAuthentication;
use Laragear\WebAuthn\Contracts\WebAuthnAuthenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements WebAuthnAuthenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use WebAuthnAuthentication;

    /* Spatie Laravel Permission */
    protected $guard_name = 'sanctum';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'employee_id',
        'department_id',
        'name', 'email',
        'phone', 'password',
        'nrc_number',
        'birthday',
        'gender', 'address',
        'date_of_join',
        'is_present',
        'image',
        'pin_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class, 'user_id', 'id');
    }

    public function img_path()
    {
        if ($this->image) {
            return asset('storage/employee/' . $this->image);
        } else {
            return asset('images/default_profile.jpg');
        }
    }
}
