<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\ClassPlan;
use App\NetworkObject;
use App\Libs\Elasticsearch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Relation::morphMap([
            'oda' => 'App\Oda',
            'class_plan' => 'App\ClassPlan',
            'course' => 'App\Course',
            'development_resource' => 'App\DevelopmentResource',
        ]);       
    }

    public function boot()
    {
        ClassPlan::deleting(function ($item) {
            $item->odas()->sync([]);
            $item->save();
            foreach($item->classPlanUrls()->get() as $cpu){
                $cpu->delete();
            }        
        });
        NetworkObject::deleting(function ($item) {
            $es = Elasticsearch::Instance();
            $es->deleteItem($item,['network_id',$item->network_id]);
        });
    }

}
