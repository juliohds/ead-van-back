<?php

namespace App;

class UserList extends Entity
{
    protected $table = 'user_list';
    protected $fillable =['title','description','is_public'];
    protected $hidden = array('updated_at', 'deleted_at');

    public function networkUser()
    {
        return $this->belongsTo('App\NetworkUser');
    }

    public function items()
    {
        return $this->hasMany('App\ItemList');
    }

    public function listSlug()
    {
        return $this->hasMany('App\ListSlug');
    }
    
}
