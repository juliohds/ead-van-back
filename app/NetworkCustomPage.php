<?php

namespace App;
use DB;
class NetworkCustomPage extends Entity
{
    protected $table = 'network_custom_page';
    //protected $hidden = array('created_at', 'updated_at');
    protected $fillable = ['title','image','body', 'published'];
    // protected $appends = ['slug'];
    
  


    

}
