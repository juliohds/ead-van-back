<?php

namespace App\Helpers;

use App\Person;
use App\User;

class LoginProviderHelper {
    private $login = "abcd-12dcdfa-4fasdrfaaf-fadsfascafer";
    private $externalApiResult = null;
    private $facebookEmail = '';
    private $facebookName = '';
    private $facebookGender = '';
    private $facebookPicture = null;
    private $googleEmail = '';
    private $googleName = '';
    private $googleId = '';
    private $googleLink = '';
    private $googleGender = '';
    private $googleVerifiedEmail = false;
    private $googlePicture = null;

    public function registerWithFacebook($token, $profileId, $networkId) {
        $haveFacebookLogin = self::facebookLogin($token);

        if ($haveFacebookLogin) {
            return self::registerWithAPI(
                $this->facebookName,
                $this->facebookEmail,
                $profileId,
                $networkId,
                $this->facebookPicture
            );
        }
        return ['sucess' => false, 'data' => 'Facebook account not found'];
    }

    public function registerWithGoogleAccount($token, $profileId, $networkId) {
        $haveGoogleLogin = self::googleLogin($token);

        if ($haveGoogleLogin) {
            return self::registerWithAPI(
                $this->googleName,
                $this->googleEmail,
                $profileId,
                $networkId,
                $this->googlePicture
            );
        }
        return ['sucess' => false, 'data' => 'Google account not found'];
    }

    public function registerWithAPI($fullName, $email, $profileId, $networkId, $picture = '') {
        try {
            $fl = split_name($fullName);
            $person = new Person;
            $person->first_name = $fl[0];
            $person->last_name = $fl[1];
            $person->profile_id = $profileId;
            $person->save();

            $user = new User;
            $user->login = $email;
            $user->email = $email;
            if ($picture != '') {
                $user->picture = $picture;
            }
            $user->person()->associate($person);
            $user->save();

            $user->networkUser($networkId);

            return ['sucess' => true, 'data' => ['user' => $user,'token' => $user->newToken()]];
        } catch (\Exception $e) {
            if ($e->getCode() == 23505) {
                return ['sucess' => false, 'data' => 'User already exists'];
            } else {
                throw $e;
            }
        }
    }

    public function facebookLogin($token) {
        try {
            $url = "https://graph.facebook.com/me?fields=email,name,gender,picture&access_token=";
            $response = file_get_contents($url.$token);
            $obj = json_decode($response);
            if (!isset($obj->error)) {
                $this->login = $obj->email ? $obj->email : $obj->id;
                $this->facebookEmail = $obj->email;
                $this->facebookName = $obj->name;
                // $this->facebookGender = $obj->gender;
                $this->facebookPicture = $obj->picture->data->url;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            if ($e->getTrace()[0]['args'][4]['http_response_header'][0] == 'HTTP/1.1 400 Bad Request') {
                return false;
            }
            throw $e;
        }
    }

    public function googleLogin($token) {
        try {
            $url = "https://www.googleapis.com/oauth2/v2/userinfo?access_token=";
            $response = file_get_contents($url.$token);
            $obj = json_decode($response);
            if (!isset($obj->error)) {
                $this->login = $obj->email ? $obj->email : $obj->id;
                $this->googleEmail = $obj->email;
                $this->googleName = $obj->name;
                $this->googleId = $obj->id;
                $this->googlePicture = $obj->picture;
                //$this->googleLink = $obj->link;
                // $this->googleGender  = $obj->gender;
                // $this->googleVerifiedEmail  = $obj->verified_email;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            if ($e->getTrace()[0]['args'][4]['http_response_header'][0] == 'HTTP/1.0 401 Unauthorized') {
                return false;
            }
            throw $e;
        }
    }

    //Facebook
    public function getFacebookEmail() {
        return $this->facebookEmail;
    }
    public function getFacebookName() {
        return $this->facebookName;
    }
    public function getFacebookGender() {
        return $this->facebookGender;
    }
    public function getFacebookPicture() {
        return $this->facebookPicture;
    }

    //Google
    public function getGoogleEmail() {
        return $this->googleEmail;
    }
    public function getGoogleName() {
        return $this->googleName;
    }
    public function getGoogleId() {
        return $this->googleId;
    }
    public function getGooglePicture() {
        return $this->googlePicture;
    }
    public function getGoogleGender() {
        return $this->googleGender;
    }
    public function getGoogleVerifiedEmail() {
        return $this->googleVerifiedEmail;
    }
    public function getGoogleLink() {
        return $this->googleLink;
    }

    public function getLogin() {
        return $this->login;
    }

}
