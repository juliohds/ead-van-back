<?php

namespace App\Helpers;
use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;

class s3Helper {

    public function urlPreSigned($name, $type){

        //s3
        $s3 = S3Client::factory([
            'credentials' => array(
                'key' => getenv('KEY_AWS'),
                'secret'  => 'yZXK9JhWiqevZO3DYnUUNSshn4myt7aC8U4bIz8J',
            ),
            'region' => 'us-east-1',
            'version' => 'latest'
        ]);

        //Creating a preSigned URL
        $cmd = $s3->getCommand('putObject', [
            'Bucket' => 'julio-br',
            'Key'    => $name,
            'ContentType' => $type,
            'ACL' => 'public-read'
        ]);

        $request = $s3->createPresignedRequest($cmd, '+2 minutes');

        // Get the actual presigned-url
        $presignedUrl = (string) $request->getUri();

        //echo getenv('DB_CONNECTION');

        $return = array();
        $return["url"] = $presignedUrl;

        return response()->json($return);

    }

    /*
    *   Envia para o armazenamento da AWS
    *   o arquivo com o path indicado nos parâmetros
    *   Retorna o URL da imagem com acesso público a ele
    */
    public function sendFileAsPublic(
        $fileLocalPath,
        $filePathToSave = null,
        $ext = 'jpg'
    ) {
        $filePathToSave = (!is_null($filePathToSave)) ? $filePathToSave : date("YmdHis");

        //s3
        $s3 = S3Client::factory([
            'credentials' => array(
                'key' => getenv('AWS_KEY'),
                'secret'  => getenv('AWS_SECRET'),
            ),
            'region' => getenv('AWS_REGION'),
            'version' => 'latest'
        ]);

        $newFileKey = getenv('AWS_UPLOADS_PATH').'/'.$filePathToSave.'.'.$ext;

        $result = $s3->putObject([
            'Bucket'        => getenv('AWS_DEFAULT_BUCKET'),
            'Key'           => $newFileKey,
            'SourceFile'    => $fileLocalPath,
            'ACL'           => 'public-read'
        ]);

        return $s3->getObjectUrl(getenv('AWS_DEFAULT_BUCKET'), $newFileKey);
    }

    /*
    *   Armazena a imagem no sistema de arquivos local
    *   Envia para o armazenamento da AWS
    *   E exclui o arquivo local
    *   Retorna o URL da imagem com acesso público a ela
    */
    public function storageLocalImgTempAndSendAsPublic(UploadedFile $image, $imageName  = null) {
        $imageName = (!is_null($imageName)) ? $imageName : date("YmdHis");
        $imageExt = $image->getClientOriginalExtension();
        $fullImageName = $imageName.'.'.$imageExt;
        $destinationPath = getcwd() .'/'. getenv('UPLOADS_TEMP_PATH');
        $fullPatch = $destinationPath.'/'.$fullImageName;

        $image->move($destinationPath, $fullImageName);
        $urlImg = self::sendFileAsPublic($fullPatch, $imageName, $imageExt);
        unlink($fullPatch);

        return $urlImg;
    }

}
