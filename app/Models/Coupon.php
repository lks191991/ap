<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

class Coupon extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public $table = 'coupons';
   
	public function getExpiredAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

    public function getCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }


 

}
