<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Firebase\JWT\JWT;

class User extends Entity implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'appuser';
    protected $fillable = [
        'picture',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function person()
    {
        return $this->belongsTo('App\Person');
    }
    public function city()
    {
        return $this->belongsTo('App\City');
    }
    public function gender()
    {
        return $this->belongsTo('App\Gender');
    }

    public function networkUser($networkId){
        $nu = NetworkUser::where('user_id',$this->id)->where('network_id',$networkId)->first();
        if(!$nu){
            //Check if user is super admin
            $nu = NetworkUser::where('user_id',$this->id)->where('role_id',Role::ADMIN)->first();
            $role_id = $nu ? Role::ADMIN : Role::USER;
            $nu = new NetworkUser([
                'user_id' => $this->id,
                'network_id' => $networkId,
                'role_id' => $role_id
            ]);
            $nu->save();
        }
        return $nu;
    }
        /**
     * Create a new token.
     * 
     * @return string
     */
    public function newToken() {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $this->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    public function newRefreshToken(){
        $payload = [
            'sub' => $this->id,
            'a' => uniqid(),
        ];
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        $refresh =  JWT::encode($payload, env('JWT_SECRET'));
        if($this->id){
            User::where('id',$this->id)->update(['refresh_token'=>$refresh]);
        }
        return $refresh;
    }

}
