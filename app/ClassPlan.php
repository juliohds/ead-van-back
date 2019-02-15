<?php

namespace App;


class ClassPlan extends DigitalResource implements Versionable
{
    protected $table = 'class_plan';
    protected $appends = ['odas','urls'];
    protected $fillable = [
        'plan_process','goal','duration','required_supplies',
        'evaluation'
    ];

    private $urls;
    private $oda_ids;

    protected $hidden = array('created_at', 'updated_at');
    
    public function odas()
    {
        return $this->belongsToMany('App\MainObject', 'class_plan_oda', 'class_plan_id', 'main_object_id');
    }

    public function classPlanUrls(){
        return $this->hasMany('App\ClassPlanUrl', 'class_plan_id', 'id');
    }

    public function allUrls() {
        $aux = [];

        foreach($this->classPlanUrls as $cpu){
            array_push($aux,$cpu->url);
        }
        return $aux;
    }

    public function save(array $options = []){
        $oda_ids = $this->oda_ids;
        $urls = $this->urls;
        parent::save($options);

        foreach($this->classPlanUrls as $cpu){
            $cpu->delete();
        }
        if($urls){
            foreach($urls as $url){
                $cpu = new ClassPlanUrl([
                    'class_plan_id' => $this->id,
                    'url' => $url,
                ]);
                $this->urls = null;
                $cpu->save();
            }
        }

        if($oda_ids){
            $this->odas()->sync($oda_ids);
            $this->oda_ids = null;
            $this->save();
        }
        
    }



    public function fill(array $options = []){
        parent::fill($options);
        if(array_key_exists('urls',$options)){
            $this->urls = $options['urls'];
        }
        if(array_key_exists('oda_ids',$options)){
            $this->oda_ids = $options['oda_ids'];
        }
    }

    public function comparableFields(){
        return [
        'plan_process','goal','duration','required_supplies',
        'evaluation'
        ];
    }

    public function getUrlsAttribute(){
        return $this->allUrls();
    }
 
    public function getOdasAttribute(){
        $odas = $this->odas()->get();
        $result = [];
        foreach($odas as $oda){
            array_push($result,['id'=>$oda->id, 'title' => $oda->title ]);
        }
        return $result;
    }
 

}
