<?php

namespace App;

class Workflow extends Entity
{
    const NEW = 1;
    const REVISION = 2;
    const PUBLISHED = 3;
    const ARCHIVED = 4;
    const BROKEN = 5;
    const DRAFT = 6;
    const SUGGESTED = 7;

    protected $table = 'workflow';

    public static function defaultValue($id){
        switch ($id) {
            case Workflow::NEW:
                return 'new';
            case Workflow::REVISION:
                return 'revision';
            case Workflow::PUBLISHED:
                return 'published';
            case Workflow::ARCHIVED:
                return 'archived';
            case Workflow::BROKEN:
                return 'broken';
            case Workflow::DRAFT:
                return 'draft';
            case Workflow::SUGGESTED:
                return 'suggested';
            default:
                return null;
        }
    }
}
