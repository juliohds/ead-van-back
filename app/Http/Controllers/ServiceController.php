<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\s3Helper;

class ServiceController extends Controller
{
    public function uploadImage() {
        if ($this->request->hasFile('image')) {
            $s3 = new s3Helper;

            $this->validate($this->request, [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg'
            ]);

            $image = $this->request->file('image');
            return $this->responseOK($s3->storageLocalImgTempAndSendAsPublic($image));
        } else {
            return response()->json(['error' => 'Send an image, the name of field is "image"'], Response::HTTP_BAD_REQUEST);
        }
    }
}
