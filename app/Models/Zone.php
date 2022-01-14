<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

class Zone extends Model
{
    use Uuids;


    use SoftDeletes;

    protected $dates = ['deleted_at'];    
   
    
    /*
     * Get referenced record of state.
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id', 'id');
    }

   


}
