<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Person extends Entity
{

    protected $table = 'person';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name','birth_date','instituition','gender_id','cpf',
        'profile_id','city_id','gender_id','phone','wish_sms'
    ];


    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
    public function academic()
    {
        return $this->belongsTo('App\Academic');
    }
    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function fullName(){
        return "".$this->first_name." ".$this->last_name;
    }

}
