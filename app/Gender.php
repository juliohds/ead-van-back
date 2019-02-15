<?php

namespace App;


class Gender extends Entity
{
    protected $table = 'gender';
    protected $hidden = array('created_at', 'updated_at');
}
