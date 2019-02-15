<?php

use App\User;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{

    public function testLogin()
    {
        $user =  User::find(1);

        $obj = [
            'provider' => 'EMAIL',
            'payload' => [
                'login' => $user->email,
                'password' => '12345678',
            ]
        ];
        $r = $this->json('POST','/api/login',$obj);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(163,strlen($json->token));
        $this->assertEquals(120,strlen($json->refresh_token));
    }

    public function testRefreshToken(){
        $token = AuthControllerTest::getToken($this);
        $obj = [
            'refresh_token' => $token->refresh_token
        ];

        $r = $this->json('PUT','/api/refresh-token',$obj);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(163,strlen($json->token));
        $this->assertEquals(120,strlen($json->refresh_token));

        //Login again to invalidate refresh token
        $newToken = AuthControllerTest::getToken($this);
        $this->assertNotEquals($token->refresh_token,$newToken->refresh_token);

        $r = $this->json('PUT','/api/refresh-token',$obj);
        $this->assertEquals(401,$r->response->getStatusCode());

    }

    public static function getToken($controller, $user_id = null){
        $user =  User::find($user_id ? $user_id : 1);

        $obj = [
            'provider' => 'EMAIL',
            'payload' => [
                'login' => $user->email,
                'password' => '12345678',
            ]
        ];
        //Todo send request with parameter
        $r = $controller->json('POST','/api/login',$obj);
        $json = (json_decode($r->response->getContent()));
        return $json;
    }

    public function testChangePasswordWithChangeToken() {
        $changePasswordToken = date("YmdHis");
        $fullPasswordToken = base64_encode(1).'_'.base64_encode($changePasswordToken);
        $user = User::find(1);

        $user->change_password_token = $changePasswordToken;
        $user->save();

        $requestBody = [
            'token' => $fullPasswordToken,
            'password' => 123456789
        ];

        $r = $this->json('PUT', '/api/reset-password-with-token', $requestBody);

        $json = (json_decode($r->response->getContent()));
        $refreshUserData = User::find(1);

        $this->assertEquals('Password changed with sucess', $json);
        $this->assertEquals(true, Hash::check(123456789, $refreshUserData->password));
    }

    public function testChangePasswordWithExpiredChangeToken() {
        $changePasswordToken = date("YmdHis") - 2000000;
        $fullPasswordToken = base64_encode(1).'_'.base64_encode($changePasswordToken);
        $user = User::find(1);

        $user->change_password_token = $changePasswordToken;
        $user->save();

        $requestBody = [
            'token' => $fullPasswordToken,
            'password' => 123456789
        ];

        $r = $this->json('PUT', '/api/reset-password-with-token', $requestBody);

        $json = (json_decode($r->response->getContent()));

        $this->assertEquals('Password change expired or not allowed, ask to change again', $json->error);
    }
}
