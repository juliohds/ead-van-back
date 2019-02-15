<?php

namespace App\Jobs;

use App\Jobs\SendEmail;
use App\User;
use App\NetworkUser;
use App\NetworkObject;
use Maatwebsite\Excel;
use Illuminate\Support\Facades\DB;

class SendReport extends Job
{
    private $reportParams;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $reportParams=array())
    {
        $this->reportParams = $reportParams;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $method = $this->reportParams['method'];
        $data = $this->$method();
        $date = date('m/d/Y h:i:s a', time());
        $filename = '/tmp/'.str_slug("report-".$date, '-').'.csv';
        $fp = fopen($filename, 'w');
        fwrite($fp, "\xEF\xBB\xBF".$this->array_to_csv($data));
        fclose($fp);
        $email = [
            'from' => getenv('MAIL_FROM_ADDRESS'),
            'to' => $this->reportParams['email'],
            'attachment' => $filename,
            'subject' => $this->reportParams['email_title'] != null ? $this->reportParams['email_title'] : 'Relatórios – Escola Digital',
            'body' => 'Sua solicitação de relatório feita pelo painel administrativo chegou :)'
        ];
            
        $job = (new SendEmail($email));
        dispatch($job);
    }
 
    public function UsersNetwork()
    {
        return NetworkUser::join('appuser', 'network_user.user_id', '=', 'appuser.id')
        ->join('person', 'appuser.person_id', '=', 'person.id')
        ->join('approle', 'network_user.role_id', '=', 'approle.id')
        ->join('profile', 'person.profile_id', '=', 'profile.id')                                     
        ->where('network_user.network_id','=',$this->reportParams['networkID'])
        ->select('approle.title AS PERFIL ADMINISTRATIVO', 'profile.title AS PERFIL', DB::raw("concat_ws(' ', person.first_name, person.last_name) AS NOME"), 'appuser.email AS EMAIL')->get()->toArray();
    }

    public function CommmentsNetwork()
    {
        return NetworkUser::join('appuser', 'network_user.user_id', '=', 'appuser.id')
        ->join('person', 'appuser.person_id', '=', 'person.id')
        ->join('comment', 'network_user.id', '=', 'comment.network_user_id')                                    
        ->where('network_user.network_id','=',$this->reportParams['networkID'])
        ->select(DB::raw("concat_ws(' ', person.first_name, person.last_name) AS USUARIO"), 'comment.text AS COMENTARIO', DB::raw("to_char(comment.created_at, 'DD/MM/YYYY') AS QUANDO"))->get()->toArray();
    }
    public function ListsNetwork()
    {
        return NetworkUser::join('appuser', 'network_user.user_id', '=', 'appuser.id')
        ->join('person', 'appuser.person_id', '=', 'person.id')
        ->join('user_list', 'network_user.id', '=', 'user_list.network_user_id')                                    
        ->where('network_user.network_id','=',$this->reportParams['networkID'])
        ->select('user_list.title AS TITULO','user_list.description AS DESCRICAO' ,DB::raw("(SELECT COUNT(*) FROM item_list WHERE user_list_id = user_list.id) AS ITENS"), DB::raw("concat_ws(' ', person.first_name, person.last_name) AS USUARIO"), DB::raw("to_char(user_list.created_at, 'DD/MM/YYYY') AS QUANDO"))->get()->toArray();
    }
    public function EvaluationsNetwork()
    {
        return NetworkObject::join('main_object', 'network_object.main_object_id', '=', 'main_object.id')
        ->join('target_object', 'main_object.target_object_id', '=', 'target_object.id')
        ->join('evaluation', 'evaluation.target_object_id', '=', 'target_object.id')                                    
        ->join('network_user', 'network_user.id', '=', 'evaluation.network_user_id')                                  
        ->join('appuser', 'network_user.user_id', '=', 'appuser.id')                                 
        ->join('person', 'appuser.person_id', '=', 'person.id')                                 
        ->where('network_object.network_id','=',$this->reportParams['networkID'])
        ->select('main_object.title AS TITULO','main_object.description AS DESCRICAO','main_object.oda_type','evaluation.pedagogical AS PEDAGOGICO','evaluation.content AS CONTEUDO','evaluation.technical AS TECNICO', DB::raw("concat_ws(' ', person.first_name, person.last_name) AS USUARIO"), DB::raw("to_char(evaluation.created_at, 'DD/MM/YYYY') AS QUANDO"))->get()->toArray();
    }


    public function array_to_csv($array, $header_row = true, $col_sep = ",", $row_sep = "\n", $qut = '"')
    {
        if (!is_array($array) or !is_array($array[0])) return false;
        $output = "";
        //Header row.
        if ($header_row)
            {
                foreach ($array[0] as $key => $val)
                {
                    //Escaping quotes.
                    $key = str_replace($qut, "$qut$qut", $key);
                    $output .= "$col_sep$qut$key$qut";
                }
                
                $output = substr($output, 1)."\n";
            }
        //Data rows.
        foreach ($array as $key => $val)
            {
                $tmp = '';
                foreach ($val as $cell_key => $cell_val)
                {
                    //Escaping quotes.
                    $cell_val = str_replace($qut, "$qut$qut", $cell_val);
                    $tmp .= "$col_sep$qut$cell_val$qut";
                }
                $output .= substr($tmp, 1).$row_sep;
            }
    
        return $output;
    }
    
    



}
