<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Profile', 'email', 'email');
    }

    public function student()
    {
        return $this->belongsTo('App\Student', 'id_profile', 'id_profile');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher', 'id_profile', 'id_profile');
    }

}
