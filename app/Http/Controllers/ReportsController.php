<?php

namespace App\Http\Controllers;

use App\User;
use App\Jobs\SendReport;
use Illuminate\Http\Request;


class ReportsController extends Controller 
{

    public function NetworkComments($idNetwork) {
        return $this->reportParms('CommmentsNetwork', 'SOLICITAÇÃO DE RELATÓRIO - COMENTARIOS DA REDE');
    }

    public function NetworkLists($idNetwork) {        
        return $this->reportParms('ListsNetwork', 'SOLICITAÇÃO DE RELATÓRIO - LISTAS DA REDE');
    }

    public function NetworkEvaluations($idNetwork) {        
        return $this->reportParms('EvaluationsNetwork', 'SOLICITAÇÃO DE RELATÓRIO - AVALIAÇÕES DA REDE');
    }

    public function NetworkUsers($idNetwork) {
        return $this->reportParms('UsersNetwork', 'SOLICITAÇÃO DE RELATÓRIO - USUARIOS DA REDE');
    }

    public function reportParms($method, $emailTitle)
    {
        $currentuser = User::find($this->currentUserID());
        $reportParams = [
            'email' => $currentuser->email,
            'method' => $method,
            'email_title' => isset($emailTitle) ? $emailTitle : null,
            'networkID' => $this->currentNetworkID()
        ];
        $job = new SendReport($reportParams);
        dispatch($job);

        return $this->responseOK(['message' => 'Seu relatório está sendo feito']);
    }

}