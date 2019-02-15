<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmail;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }


    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate(Request $request) {
        $this->validate($request, [
            'provider' => 'required',
            'payload' => 'required|array'
        ]);
        $provider = $request->all();

        $lp = new LoginProvider($provider);

        // Find the user by email
        $user = User::where('email', $lp->login)->orWhere('login',$lp->login)->first();
        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the
            // below respose for now.
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        if ($user->enabled == false) {

            return response()->json([
                'error' => 'Usuario desativado pelo admin'
            ], 400);
        }

        // Verify the password and generate the token
        if ($lp->ignorePassword || Hash::check($lp->password, $user->password)) {
            return response()->json([
                'token' => $user->newToken(),
                'refresh_token' => $user->newRefreshToken()
            ], 200);
        }

        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    public function refreshToken(){
        $request = $this->request;
        $this->validate($request, [
            'refresh_token' => 'required',
        ]);

        $refresh_token = $request->input('refresh_token');

        if(!$refresh_token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Refresh Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($refresh_token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        $user = User::find($credentials->sub);

        if($user->refresh_token != $refresh_token){
            return response()->json([
                'error' => 'Provided refresh token is not valid.'
            ], 401);

        }
        return response()->json([
            'token' => $user->newToken(),
            'refresh_token' => $user->newRefreshToken()
        ], 200);
    }

    public function resetPasswordWithToken() {
        $idAndToken = explode('_', $this->input('token'));
        $id = base64_decode($idAndToken[0]);
        $changePasswordToken = base64_decode($idAndToken[1]);
        $user = User::find($id);

        //1000000 = 1 dia - o token está com 1 dia de validade
        $expirationDate = $user->change_password_token + 1000000;

        if ($user === null) {
            throw new \App\Exceptions\NotFoundException;
        }

        // $expirationDate < date("YmdHis") --- Se a data de expiração for
        // menor que hoje a alteração expirou
        if ($user->change_password_token === null || $expirationDate < date("YmdHis")) {
            return response()->json([
                'error' => 'Password change expired or not allowed, ask to change again',
                Response::HTTP_BAD_REQUEST
            ]);
        }

        $user->password = Hash::make($this->input('password'));
        $user->change_password_token = null;
        $user->save();

        return $this->responseOK('Password changed with sucess');
    }

    public function forgotPassword(){
        //Todo following line is not a real app implementation.
        //throw new \App\Exceptions\TodoException;

        $this->validate($this->request, [
            'email' => 'required|email',
        ]);

        $user = User::where('email' ,'=',$this->request->input('email'))->first();

        if($user == null) {
            throw new \App\Exceptions\NotFoundException;
        }

        //O token será o timestamp
        $changePasswordToken = date("YmdHis");
        // O token que irá para a url será o base64 do id com _ mais o base64
        // do token que vai para a base de dados
        $codedPasswordToken = base64_encode($user->id).'_'.base64_encode($changePasswordToken);

        $user->change_password_token = $changePasswordToken;
        $email = [
            'from' => getenv('MAIL_FROM_ADDRESS'),
            'to' => $user->email,
            'subject' => 'Instruções de troca de senha – Escola Digital',
            // 'body' => $this->messageForgot($user->person->first_name, $this->currentNetworkID()->internal_url, $this->request->getHost(), $codedPasswordToken)
            'body' => $this->messageForgot($user->person->first_name, $this->currentNetwork()->internal_url,$codedPasswordToken)
        ];
        $job = (new SendEmail($email));
        dispatch($job);
        $user->save();
        return $this->responseOK("Email sent");
    }

    private function messageForgot($name,$networkInternalURL,$token){
        return "
            <html>
                <body>
                    Ol&aacute; <strong>$name</strong><br>
                    Algu&eacute;m, provavelmente voc&ecirc;, pediu para redefinir a senha desta conta na plataforma Escola Digital. Voc&ecirc; pode faz&ecirc;-lo acessando o link abaixo:<br>
                    <a href='http://$networkInternalURL?forgot-password=$token'>Trocar de Senha</a><br>
                    Se n&atilde;o foi voc&ecirc; quem pediu, ignore este e-mail.<br>
                    Sua senha n&atilde;o mudara caso voc&ecirc; n&atilde;o acesse o link acima e crie uma nova.<br>
                </body>
            </html>
        ";
    }

}

class LoginProvider {
    public $ignorePassword = false;
    public $login = "abcd-12dcdfa-4fasdrfaaf-fadsfascafer";
    public $password = "";

    public function __construct($provider) {
        switch ($provider["provider"]) {
            case "EMAIL":
                $this->setEmail($provider);
                break;
            case "FACEBOOK":
                $this->setFacebook($provider);
                break;
            case "GMAIL":
                $this->setGmail($provider);
                break;
        }
    }

    private function setEmail($provider){
        try{
        $this->ignorePassword = false;
        $this->login = $provider["payload"]["login"];
        $this->password = $provider["payload"]["password"];
        }catch(\Exception $e){}
    }

    private function setFacebook($provider) {

        try {
            $token = $provider["payload"]["token"];
            $url = "https://graph.facebook.com/me?fields=email,name,gender,picture&access_token=";
            $response = file_get_contents($url.$token);
            $obj = json_decode($response);
            $this->login = $obj->email ? $obj->email : $obj->id;
            $this->ignorePassword = true;

        } catch (\Exception $e) {
        }

    }

    private function setGmail($provider) {

        try {
            $token = $provider["payload"]["token"];
            $url = "https://www.googleapis.com/oauth2/v2/userinfo?access_token=";
            $response = file_get_contents($url.$token);
            $obj = json_decode($response);
            $this->login = $obj->email ? $obj->email : $obj->id;
            $this->ignorePassword = true;

        } catch (\Exception $e) {
        }

    }

}
