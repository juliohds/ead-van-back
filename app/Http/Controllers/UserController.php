<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\NetworkUser;
use App\Person;
use App\Profile;
use App\Academic;
use App\Interest;
use App\Jobs\SendReport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LoginProviderHelper;

class UserController extends Controller
{
    public function showAll($idNetwork){

        $first_name = $this->input('firstname') ? $this->input('firstname') : "";
        $last_name = $this->input('lastname') ? $this->input('lastname') : "";        
        
        if($first_name == ""){
            $nu = NetworkUser::with(['role', 'user.person.profile'])->naoDeletadas($idNetwork)->paginate();
        }else{
            if($last_name == "null") {
                
                $nu = NetworkUser::with(['role', 'user.person.profile'])
                    ->join('person','network_user.user_id','=','person.id')                                        
                    ->where([
                        ['network_user.network_id','=',$idNetwork],
                        ['person.deleted_at','=',null],                        
                        ['person.first_name','ilike','%'.$first_name.'%']
                    ])
                    ->orWhere([
                        ['network_user.network_id','=',$idNetwork],
                        ['person.deleted_at','=',null],                        
                        ['person.last_name','ilike','%'.$first_name.'%']
                    ])
                    ->select('network_user.*')
                    ->paginate();                    
            }else{
                $nu = NetworkUser::with(['role', 'user.person.profile'])
                    ->join('person','network_user.user_id','=','person.id')                                        
                    ->where([
                        ['network_user.network_id','=',$idNetwork],
                        ['person.deleted_at','=',null],                        
                        ['person.first_name','ilike','%'.$first_name.'%'],
                        ['person.last_name','ilike','%'.$last_name.'%']
                    ])
                    ->orWhere([
                        ['network_user.network_id','=',$idNetwork],
                        ['person.deleted_at','=',null],                        
                        ['person.last_name','ilike','%'.$first_name.'%'],
                        ['person.last_name','ilike','%'.$last_name.'%']
                    ])
                    ->select('network_user.*')
                    ->paginate();
            }            
        }
        return response()->json($nu);
    }

    public function exportUsersJob(){

        //$user = User::where('id','=',$this->currentUserID());
        //var_dump($this->currentNetworkID());
        //var_dump($this->currentUserID());
        $reportParams = [
            'email' => 'ronifersilva@hotmail.com',
            'method' => 'UsersNetwork',
            'networkID' => $this->currentNetworkID()
        ];
        $job = new SendReport($reportParams);
        dispatch($job);

        return $this->responseOK(['message' => 'Seu relatório está sendo feito']);
    }


    public function showAllNetworks(){
        $nu = NetworkUser::whereNull('deleted_at')->with(['role','user.person.profile'])->get();
        return response()->json($nu);

    }

    public function delete($idNetwork, $id){

        $nu_id = NetworkUser::where('user_id', $id)->pluck('id')->toArray();
        foreach($nu_id as $nid){

            $nu = NetworkUser::find($nid);
            $nu->delete();

            $user = User::find($nid);
            $user->delete();

        }

        return response()->json("deletado com sucesso!");
    }

    public function ativarDesativarUser($idNetwork, $id){
        $user = User::find($id);
        if($user->enabled){
            $user->enabled = false;
        }else{
            $user->enabled = true;
        }
        $user->update();
        return response()->json($user);
    }

    public function userInfo()
    {
        $user = User::where('id','=',$this->currentUserID())
            ->with(['person.academic.school.city','person.city',
            'person.academic.interests','person.academic.grades','person'])->first();
        $user->role = $user->networkUser($this->currentNetworkID())->role->tag;

        return $this->responseOK($user);

    }

    public function userInfoById($id)
    {
        $user = User::where('id','=',$id)
            ->with(['person.academic.school.city','person.city',
            'person.academic.interests','person.academic.grades','person'])->first();
        $user->role = $user->networkUser($this->currentNetworkID())->role->tag;

        return $this->responseOK($user);

    }

    //API do facebook na versão 3.1
    public function registerWithFacebook() {
        $lp = new LoginProviderHelper;

        $registerResult = $lp->registerWithFacebook(
            $this->input('token'),
            Profile::OTHER,
            $this->currentNetworkID()
        );

        if ($registerResult['sucess']) {
            return $this->responseCreated($registerResult['data']);
        }

        return response()->json(['error' => $registerResult['data']], Response::HTTP_BAD_REQUEST);
    }

    public function registerWithGoogleAccount() {
        $lp = new LoginProviderHelper;

        $registerResult = $lp->registerWithGoogleAccount(
            $this->input('token'),
            Profile::OTHER,
            $this->currentNetworkID()
        );

        if ($registerResult['sucess']) {
            return $this->responseCreated($registerResult['data']);
        }

        return response()->json(['error' => $registerResult['data']], Response::HTTP_BAD_REQUEST);
    }

    public function register(Request $request){
        $this->validate($request, [
            'full_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'profile_id' => 'required'
        ]);

        try {
            $data = $request->all();

            $fl = split_name($data["full_name"]);
            $person = new Person;
            $person->first_name = $fl[0];
            $person->last_name = $fl[1];
            $person->profile_id = $data["profile_id"];
            $person->save();

            $user = new User;
            $user->login = $data["email"];
            $user->email = $data["email"];
            if (isset($data['picture'])) {
                $user->picture = $data['picture'];
            }
            $user->password = Hash::make($data["password"]);
            $user->person()->associate($person);
            $user->save();

            $user->networkUser($this->currentNetworkID());

            return $this->responseCreated(['user' => $user,'token' => $user->newToken()]);
        }catch (\Exception $e) {
            if($e->getCode() == 23505) {
                return response()->json(["message" =>"User already exists"]);
            }else {
                throw $e;
            }
        }
    }

    public function update($id){

        $user = User::find($id);
        if($user == null) {
            throw new \App\Exceptions\NotFoundException;
        }

        $data = $this->request->all();

        $person = $user->person;

        if($data["person"] && $data["person"]["academic"]){

            $academic = $person->academic;

            if ($academic == null) {
                $academic = new Academic;
                $academic->save();
            }

            $academic->fill($data["person"]["academic"]);

            if(array_key_exists('school_id',$data["person"]["academic"])){
                $academic->setSchool($data["person"]["academic"]["school_id"]);
            }

            $ids = $data["person"]["academic"]["interest_ids"];
            $academic->interests()->sync($ids);


            $ids = $data["person"]["academic"]["grade_ids"];
            $academic->grades()->sync($ids);

            $person->academic()->associate($academic);

            $academic->save();



            $person->academic()->associate($academic);
        }

        $person->fill($data["person"]);
        $person->save();

        $user->fill($data);
        $user->save();
        return $this->responseOK($user);
    }

    public function qtdUser($idNetwork){
        $nu = NetworkUser::where('network_id', $idNetwork)->count();
        return response()->json($nu);
    }

    public function filterByProfile($idNetwork) {
        $profile = $this->input('profile') ? $this->input('profile') : "";
        $first_name = $this->input('firstname') ? $this->input('firstname') : "";
        $last_name = $this->input('lastname') ? $this->input('lastname') : "";
        
        if(!$profile) {
            return response('Error',400);            
        }

        if($first_name == "") {        
            $filteredUsers = NetworkUser::with(['role', 'user.person.profile'])->where('role_id', $profile)->naoDeletadas($idNetwork)->paginate();
            return response()->json($filteredUsers);
        }else{
            if($last_name == "null") {
                
                $nu = NetworkUser::with(['role', 'user.person.profile'])
                    ->join('person','network_user.user_id','=','person.id')                                        
                    ->where([
                        ['network_user.network_id','=',$idNetwork],
                        ['person.deleted_at','=',null],                        
                        ['person.first_name','ilike','%'.$first_name.'%'],
                        ['role_id', '=' ,$profile]
                    ])
                    ->orWhere([
                        ['network_user.network_id','=',$idNetwork],
                        ['person.deleted_at','=',null],                        
                        ['person.last_name','ilike','%'.$first_name.'%'],
                        ['role_id', '=' ,$profile]
                    ])
                    ->select('network_user.*')
                    ->paginate();                    
            }else{
                $nu = NetworkUser::with(['role', 'user.person.profile'])
                    ->join('person','network_user.user_id','=','person.id')                                        
                    ->where([
                        ['network_user.network_id','=',$idNetwork],
                        ['person.deleted_at','=',null],                        
                        ['person.first_name','ilike','%'.$first_name.'%'],
                        ['person.last_name','ilike','%'.$last_name.'%'],
                        ['role_id', '=' ,$profile]
                    ])
                    ->orWhere([
                        ['network_user.network_id','=',$idNetwork],
                        ['person.deleted_at','=',null],                        
                        ['person.last_name','ilike','%'.$first_name.'%'],
                        ['person.last_name','ilike','%'.$last_name.'%'],
                        ['role_id', '=' ,$profile]
                    ])
                    ->select('network_user.*')
                    ->paginate();
            }
            return response()->json($nu);
        }
        
                
    }

    public function searchByEmail($idNetwork) {
        $email = $this->input('email') ? $this->input('email') : "";

        if($email == "") {
            return response('Email not found',400);    
        }else{
            $nu = NetworkUser::with(['role', 'user.person.profile'])
                    ->join('appuser','network_user.user_id','=','appuser.id')                                        
                    ->where([
                        ['network_user.network_id','=',$idNetwork],
                        ['appuser.deleted_at','=',null],                        
                        ['appuser.email','=',$email]
                    ])
                    ->select('network_user.*')
                    ->paginate();
            
            return response()->json($nu);
        }

    }
}
