<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class EntityIndexable extends Entity
{

    abstract public function getIndexable();

    public function getMapping() {
        return [
            'created_at' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss'
            ],
            'updated_at' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss'
            ]
        ];
    }
}
