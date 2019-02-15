<?php

function countTotalPages($perPage,$total){
    if($total <= 0 || $perPage <= 0){
        return 0;
    }
    $pages = floor($total/$perPage);
    $pages = $pages + (($total % $perPage) > 0 ? 1 : 0);
    return $pages;
}

