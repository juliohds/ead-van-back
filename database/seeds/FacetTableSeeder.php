<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Facet;
use App\FacetType;
use App\FacetOption;
class FacetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $entities = $this->readFromFile();

        foreach($entities as $e){
            $e['facet']->save();
            foreach($e['options'] as $option){
                $op = $option['option'];
                $op->facet_id = $e['facet']->id;
                $op->save();
                appendFile('/tmp/file.csv',$op->id.';'.$option['ids'].PHP_EOL);
            }

        }
    }
    private function readFromFile(){
        warningTODO("Remove deep nested ifs");
        $entities = [];
        try{
            $file = fopen('./database/facets.csv','r');
            $row = 0;
            $obj = null;
            while (($data = fgetcsv($file, 0, ";")) !== FALSE) {
                if($row !=0){
                    if($data[1] != '' ) {
                        if($obj != null){
                            array_push($entities,$obj);
                        }
                        $obj = [];
                        $facetType = $data[6] ? $data[6]: 'oda';
                        $obj['facet'] = new Facet([
                            'type' => $data[0] == '' ? null : $data[0],
                            'title' => $data[1],
                            'synonymous' => $data[2],
                            'facet_type_id' => $facetType == 'oda' ? FacetType::ODA : FacetType::COURSE
                        ]);
                    }else{
                        if(!$obj == null || array_key_exists('facet',$obj)){
                            if(!array_key_exists('options',$obj)){
                                $obj['options'] = [];
                            }
                            $option = new FacetOption([
                                'title' => $data[3],
                                'synonymous' => $data[4],
                            ]);
                            array_push($obj['options'],['option'=>$option,'ids'=>$data[5]]);   
                        }else{
                            new \Exception("Invalid file format! facet.csv");
                        }
                    }
                }
                $row++;
            } 
            if($obj != null){
                array_push($entities,$obj);
            }           
            fclose($file);
            return $entities;
        }catch(\Exception $e){
            throw $e;
        }
        return null;
    }
}
