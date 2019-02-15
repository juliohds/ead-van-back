<?php

namespace App;
use DB;
class CustomPage extends Entity
{
    protected $table = 'custom_page';
    protected $hidden = array('created_at', 'updated_at');
    protected $fillable = ['title','image','body'];
    // protected $appends = ['slug'];
    
  


    

}
