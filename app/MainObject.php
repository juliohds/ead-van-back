<?php

namespace App;

use App\TargetObject;

class MainObject extends EntityIndexable implements Indexable,Versionable
{

    protected $table = 'main_object';
    protected $fillable = [
        'title','description','tags','picture','bncc_tags','bncc_ok','produced_by'
    ];

    protected $appends = ['facet_option_ids','slug'];

    private $option_ids = null;

    public function setFacetOptionIds($ids){
        $this->option_ids = $ids;
    }

    public static $searchFields = [
        'title' => 'wildcard',
        'description' => 'wildcard',
        'tags' => 'match',
        'produced_by' => 'wildcard',
    ];


    public function target()
    {
        return $this->belongsTo('App\TargetObject','target_object_id');
    }
    public function oda()
    {
        return $this->morphTo();
    }

    public function facetOptions()
    {
        return $this->belongsToMany('App\FacetOption', 'object_facet_option', 'main_object_id', 'facet_option_id');
    }
    public function slugs(){
        return $this->hasMany("App\ObjectSlug");
    }
    
    public function getFacetOptionIdsAttribute(){
        if($this->option_ids){
            return $this->option_ids;
        }
        try{
            return $this->facetOptions()->get()->pluck('id')->toArray();
        }catch(\Exception $e){
            return [];
        }
    }
    public function getSlugAttribute(){
        foreach($this->slugs as $slug){
            return $slug->slug;
        }
    }

    public function getIndexable(){
        $indexable = clone $this;
        $indexable->oda;
        return $indexable->toJson();
    }

    public function getMapping() {
        return array_merge(
            parent::getMapping(),
            [
                'title' => [
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ],
                'oda' => [
                    'properties' => [
                        'created_at' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd HH:mm:ss'
                        ],
                        'updated_at' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd HH:mm:ss'
                        ]
                    ]
                ]
            ]
        );
    }

    public function comparableFields(){
        return [
            'title','description','tags','picture','bncc_tags','oda','facet_option_ids','produced_by'
        ];
    }

    public static $validate = [
        'title' => 'required',
        'description' => 'required',
        'oda_type' => 'required',
        'oda.url' => 'required_if:oda_type,oda|required_if:oda_type,course|required_if:oda_type,development_resource',
        'oda.plan_process' => 'required_if:oda_type,class_plan',
        'oda.goal' => 'required_if:oda_type,class_plan',
        'oda.duration' => 'required_if:oda_type,class_plan',
        'oda.required_supplies' => 'required_if:oda_type,class_plan',
        'oda.evaluation' => 'required_if:oda_type,class_plan',
        'oda.total_hours' => 'required_if:oda_type,course',
        'oda.goal' => 'required_if:oda_type,course',

    ];

    public function save(array $options = []){
        $option_ids = $this->option_ids;
        parent::save($options);
        if($option_ids){
            $this->facetOptions()->sync($option_ids);
            $this->option_ids = null;
            $this->save();
        }
        $this->slugly();

    }

    private function slugly(){
        $slg = str_slug($this->title)."-".$this->id;
        $old = ObjectSlug::where('slug',$slg)->First();
        if(!$old){
            $os = new ObjectSlug;
            $os->main_object_id = $this->id;
            $os->slug = $slg;
            $os->save();
        }
    }

}
