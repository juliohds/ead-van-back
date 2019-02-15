<?php

namespace App;

class ListSlug extends Entity
{
    protected $table = 'list_slug';
    protected $hidden = array('created_at', 'updated_at');
    
    public function userList(){
        return $this->belongsTo('App\UserList');
    }
}