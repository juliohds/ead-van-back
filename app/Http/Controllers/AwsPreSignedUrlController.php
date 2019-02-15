<?php

namespace App\Http\Controllers;
use App\Helpers\s3Helper;

class AwsPreSignedUrlController extends Controller
{
    public function showUrlPreSigned($name, $type){

        $s3 = new s3Helper;
        return $s3->urlPreSigned($name,$type);

    }


}