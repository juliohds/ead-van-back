<?php

namespace App\Http\Controllers;

use App\NetworkConfig;
use App\Network;
use Illuminate\Http\Response;

class NetworkConfigController extends Controller
{

    public function showByNetworkId($idNetWork) {
        $network = Network::find($idNetWork);
        $networkConfig = $network->networkConfig;

        if ($network === null || $networkConfig === null) {
            throw new \App\Exceptions\NotFoundException;
        }

        return $this->responseOK($networkConfig);
    }

    public function update($idNetWork) {
        $network = Network::find($idNetWork);

        if ($network === null) {
            throw new \App\Exceptions\NotFoundException;
        }

        $networkConfig = $network->networkConfig;
            
        $data = $this->request->all();
        
        if (empty($data)) {
            return response()->json(['error' => 'Empty body is not allowed'], Response::HTTP_BAD_REQUEST);
        }

        if ($networkConfig === null) {
            $networkConfig = new NetworkConfig();
            $networkConfig->fill($data);
            $networkConfig->save();

            $network->network_config_id = $networkConfig->id;
            $network->save();
        } else {
            $networkConfig->fill($data);
            $networkConfig->save();
        }

        return $this->responseOK($networkConfig);
    }

}
