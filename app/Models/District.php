<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

class District extends Model
{

    use Uuids;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /*
     * Get referenced record of school.
     */
    public function state()
    {
        return $this->belongsTo(State::Class, 'state_id')->withDefault();
    }
    /*
     * Get referenced record of school.
     */
    public function zone()
    {
        return $this->belongsTo(Zone::Class, 'zone_id')->withDefault();
    }
    
    
}
