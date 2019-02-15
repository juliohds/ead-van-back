<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\NetworkUser;
use App\Person;
use App\Academic;
use App\School;
use App\Grade;
use App\Interest;
use App\City;
use App\Role;
use App\Profile;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $this->saveAdmin();
        $this->saveCurator();
    }

    private function saveAdmin(){
        $academic = new Academic;
        $academic->school_id = 1;
        $academic->city_id = 1;
        $academic->save();
    
        $academic->interests()->sync([1,2,3]);
        $academic->grades()->sync([1]);
        
        

        $p = new Person;
        $p->first_name = "Sebastião";
        $p->profile_id = Profile::MANAGER;
        $p->academic_id = $academic->id;
        $p->city_id = 2;
        $p->save();

        $user = new User;
        $user->email = 'email123abc456789xkU@email.com';
        $user->login = 'email123abc456789xkU@email.com';
        $user->password = Hash::make('12345678');
        $user->person_id = $p->id;
        $user->save();

        $nu = new NetworkUser([
            'network_id' => 1,
            'user_id' => $user->id,
            'role_id' => Role::ADMIN
        ]);
        $nu->save();

        $nu = new NetworkUser([
            'network_id' => 2,
            'user_id' => $user->id,
            'role_id' => Role::NET_ADMIN
        ]);
        $nu->save();
        
    }

    private function saveCurator(){
        $academic = new Academic([
                'school_id' => 1,
                'city_id' => 1,
        ]);
        $academic->save();
    
        $academic->interests()->sync([1,2,3]);
        $academic->grades()->sync([1]);
        
        

        $p = new Person([
            'first_name' => 'Niva',
            'profile_id' => Profile::TEACHER,
            'academic_id' => $academic->id,
            'city_id' => 2
        ]);
        $p->save();

        $user = new User;
        $user->email = 'emailniva123abc456789xkU@email.com';
        $user->login = 'emailniva123abc456789xkU@email.com';
        $user->password = Hash::make('12345678');
        $user->person_id = $p->id;
        $user->save();

        $nu = new NetworkUser([
            'network_id' => 1,
            'user_id' => $user->id,
            'role_id' => Role::CURATOR
        ]);
        $nu->save();

        
    }
}
