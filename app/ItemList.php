<?php

namespace App;

class ItemList extends Entity
{
    protected $table = 'item_list';
    protected $hidden = array('created_at', 'updated_at');
    
    public function list()
    {
        return $this->belongsTo('App\UserList');
    }

    public function target()
    {
        return $this->belongsTo('App\TargetObject');
    }
}
