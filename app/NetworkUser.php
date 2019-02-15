<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class NetworkUser extends Entity
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'network_user';
    protected $hidden = array('created_at', 'updated_at');

    public function network()
    {
        return $this->belongsTo('App\Network');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function scopeNaoDeletadas($query, $id) {
        return $query->whereNull('deleted_at')->where('network_id',$id);
    }
}
