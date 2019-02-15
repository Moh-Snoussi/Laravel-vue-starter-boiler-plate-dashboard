<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{


  protected $primaryKey = 'account_id';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['user_id', 'amount', 'cardNumber', 'pin', 'Active'];
    //


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $hidden = ['cardNumber', 'pin', 'Active'];
}
