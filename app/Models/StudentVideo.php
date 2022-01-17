<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentVideo extends Model
{
    //
	
	public function user()
    {
        return $this->hasOne(Student::Class, 'user_id', 'student_id')->withDefault();
    }

	
	public function video()
    {
        return $this->hasOne(Video::Class, 'id', 'video_id')->withDefault();
    }
}
