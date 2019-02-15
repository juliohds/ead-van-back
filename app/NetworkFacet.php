<?php

namespace App;


class NetworkFacet extends Entity
{
    protected $table = 'network_facet';
    protected $appends = ['network_title','facet_type','unrelated_options'];
    protected $hidden = ['title'];
    protected $fillable = ['suggestion_enabled','required','enabled',
        'title','ui_index','limit'];

    public function network()
    {
        return $this->belongsTo('App\Network');
    }
    public function facet()
    {
        return $this->belongsTo('App\Facet');
    }

    public function options(){
        return $this->hasMany('App\NetworkFacetOption')
            ->orderBy('ui_index','asc');
    }

    public function getNetworkTitleAttribute(){
        return $this->title != null ? $this->title : $this->facet->title;
    }

    public function getFacetTypeAttribute(){
        return $this->facet->getFacetType();
    }
    public function getUnrelatedOptionsAttribute(){
        $ids = $this->options()->get()->pluck('facet_option_id');
        $unrelateds = FacetOption::where('facet_id',$this->facet_id)
            ->whereNotIn('id',$ids)->get();
        return $unrelateds;
    }
    public function save(array $options = []){
        //Create childs relation for custom;
        if(!$this->id){
            parent::save($options);
            $this->createChildRelations();
        }else{
            parent::save($options);
        }
    }


    private function createChildRelations(){
        foreach($this->facet->options as $index => $option){
            $nfo = new NetworkFacetOption([
                'network_facet_id' => $this->id,
                'facet_option_id' => $option->id,
                'ui_index' => $index
            ]);
            $nfo->save();
        }
    }
}
