<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

class College extends Model
{
    use Uuids;
	
	use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $table = "colleges";

    protected $fillable = ['name', 'status'];

    
    
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

    /*
     * Get referenced record.
     */
    public function district()
    {
        return $this->belongsTo(District::Class, 'district_id')->withDefault();
    }
    
     /*
     * Get referenced record of school.
     */
    public function city()
    {
        return $this->belongsTo(City::Class, 'city_id')->withDefault();
    }


}
