<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $env = app()->environment();
        $this->call('RoleTableSeeder');
        $this->call('ProfileTableSeeder');
        $this->call('GradeTableSeeder');
        $this->call('InterestTableSeeder');
        $this->call('StateTableSeeder');
        $this->call('CityTableSeeder');
        $this->call('TypeSchoolTableSeeder');
        $this->call('SchoolTableSeeder');
        $this->call('GenderTableSeeder');
        $this->call('FacetTypeTableSeeder');
        $this->call('WorkflowTableSeeder');
        $this->call('TypeSocialTableSeeder');
        $this->call('CycleBnccTableSeeder');
        if($env != 'staging' && $env != 'production'){
            $this->call('NetworkTableSeeder');
            $this->call('UserTableSeeder');
            $this->call('FacetTableSeeder');
            $this->call('OdaTableSeeder');
            $this->call('ClassPlanTableSeeder');
            $this->call('CourseTableSeeder');
            $this->call('NetworkObjectTableSeeder');
            $this->call('NetworkFacetTableSeeder');
            $this->call('EvaluationTableSeeder');
            $this->call('CommentTableSeeder');
            $this->call('TableUserListSeeder');
            $this->call('FavorityTargetObjectSeeder');
            $this->call('MenuTableSeeder'); 
            $this->call('ModuloTabTableSeeder');
            $this->call('TabTableSeeder');
            $this->call('CardTableSeeder'); 
                       
        }
        
    }
}
