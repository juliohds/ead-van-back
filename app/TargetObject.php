<?php

namespace App;

class TargetObject extends Entity
{
    protected $table = 'target_object';
    protected $hidden = array('created_at', 'updated_at','user');
    protected $appends = ['user_info'];
    public function evaluationAverage() {
        $eval = Evaluation::where('target_object_id',$this->id)->groupBy('target_object_id')
             ->selectRaw('avg(content) as avg_con,avg(pedagogical) as avg_ped,
             avg(technical) as avg_tec, target_object_id')->first();
        if($eval) {
            return round(($eval->avg_con + $eval->avg_tec + $eval->avg_ped) / 3);
        }else{
            return 0;
        }
    }

    public function versions(){
        return $this->hasMany('App\MainObject');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function getUserInfoAttribute(){
        $user = $this->user()->first();
        $result = [];
        $result['picture'] = $user->picture;
        $result['full_name'] = $user->person->fullName();
        return $result;
    }


}
