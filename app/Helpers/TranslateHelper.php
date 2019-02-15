<?php

function dictionary(){
    return [
        'new' => 'Novo',
        'revision' => 'Revisão',
        'published' => 'Publicado',
        'archived' => 'Arquivado',
        'broken' => 'Link Quebrado',
        'draft' => 'Rascunho',
        'suggested' => 'Sugerido',
        'oda' => 'Oda',
        'course' => 'Curso',
    ];
}

function translate($s){
    if(array_key_exists($s,dictionary())){
        return dictionary()[$s];
    }
    return $s;
}

