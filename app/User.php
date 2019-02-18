<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    protected $dates = ['deleted_at'];
    public  $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','familyName', 'givenName', 'password','provider','providerId','avatar', 'lastLoginDate','rememberToken','refreshToken','ipAddressOnRegistration','ipAddressOnLastLogin','languages','deviceOnRegistration','deviceOnLastLogin','comingFromBeforeRegistering','comingFromBeforeLastLogin', 'active', 'activationToken','lastLoginDate','apiToken','emailVerifiedAt'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','providerId','cardNumber','pin','lastLoginDate', 'rememberToken' ,'refreshToken', 'ipAddressOnRegistration','ipAddressOnLastLogin','languages','deviceOnRegistration','deviceOnLastLogin','comingFromBeforeRegistering','comingFromBeforeLastLogin', 'activationToken','apiToken','emailVerifiedAt'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
