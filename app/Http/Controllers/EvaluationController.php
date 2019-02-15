<?php

namespace App\Http\Controllers;

use App\Evaluation;
use App\User;
use App\NetworkUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EvaluationController extends Controller
{
    public function showAll($idNetwork){

        $ot = $this->input('oda_type') ? $this->input('oda_type') : "";

        $nu_id = NetworkUser::where('network_id', $idNetwork)
                                ->pluck('id')->toArray();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        if($ot == ""){
            $evaluation = Evaluation::whereIn('network_user_id', $nu_id)->with('networkUser.user.person')
                                ->join('main_object', 'evaluation.target_object_id', '=', 'main_object.target_object_id')
                                ->select('evaluation.*', 'main_object.oda_type', 'main_object.title', 'main_object.description')->paginate();
        
        }else{
            $evaluation = Evaluation::whereIn('network_user_id', $nu_id)->with('networkUser.user.person')
                                ->join('main_object', 'evaluation.target_object_id', '=', 'main_object.target_object_id')->where('main_object.oda_type', $ot)
                                ->select('evaluation.*', 'main_object.oda_type', 'main_object.title', 'main_object.description')->paginate();
        }
        if($evaluation == null){
            throw new \App\Exceptions\NotFoundException;
        }

        return response()->json($evaluation);
    }

    public function qtdEvaluation($idNetwork){

        $nu_id = NetworkUser::where('network_id', $idNetwork)
                                ->pluck('id')->toArray();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $Evaluation = Evaluation::whereIn('network_user_id', $nu_id)
                            ->count();

        return response()->json($Evaluation);
    }


    public function showEvaluation($idNetwork, $idTargetObject)
    {
        $nu_id = NetworkUser::where('network_id', $idNetwork)
                                ->pluck('id')->toArray();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $evaluation = Evaluation::whereIn('network_user_id', $nu_id)
                                  ->where('target_object_id', $idTargetObject)->get();

        if($evaluation == null){
            throw new \App\Exceptions\NotFoundException;
        }

        return response()->json($evaluation);
    }

    public function registerEvaluation(Request $request, $idNetwork, $idTargetObject)
    {
        $this->validate($request, [
            'pedagogical' => 'required',
            'content' => 'required',
            'technical' => 'required'
        ]);

        $nu_id = NetworkUser::where('user_id', $request->auth->id)->where('network_id', $idNetwork)
                                ->pluck('id')->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $evaluation = Evaluation::where([
            ['network_user_id','=', $nu_id],
            ['target_object_id','=', $idTargetObject]
        ])->get();

        if(count($evaluation) != 0) {
            return $this->responseOK("Já existe Registro deste Usuario");
        }

        try {

            $evaluation = new Evaluation;

            $data = $request->all();

            $evaluation->pedagogical = $data["pedagogical"];
            $evaluation->content = $data["content"];
            $evaluation->technical = $data["technical"];
            $evaluation->network_user_id = $nu_id;
            $evaluation->target_object_id = $idTargetObject;
            $evaluation->save();

            return  response()->json($evaluation);

        }catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateEvaluation(Request $request, $idNetwork, $idTargetObject)
    {
        $this->validate($request, [
            'pedagogical' => 'required',
            'content' => 'required',
            'technical' => 'required'
        ]);
        
        $nu_id = NetworkUser::where('user_id', $request->auth->id)->where('network_id', $idNetwork)
                                ->pluck('id')->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $evaluation = Evaluation::where([
            ['network_user_id','=', $nu_id],
            ['target_object_id','=', $idTargetObject]
        ])->get();

        if(count($evaluation) == 0) {
            throw new \App\Exceptions\NotFoundException;
        }

        $data = $this->request->all();

        $evaluation[0]->setAttribute('pedagogical', $request->pedagogical);
        $evaluation[0]->setAttribute('content', $request->content);
        $evaluation[0]->setAttribute('technical', $request->technical);

        $evaluation[0]->save();

        return $this->responseOK($evaluation);
    }

    public function filterByRating($idNetwork) {
        $ot = $this->input('oda_type') ? $this->input('oda_type') : "";
        $total = $this->input('total') ? $this->input('total') : "";

        $nu_id = NetworkUser::where('network_id', $idNetwork)
                                ->pluck('id')->toArray();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        if($total == "") {
            return response('Rating not found',400);
        }
        
        // $pedagogical = Evaluation::avg('pedagogical');
        // $content = Evaluation::avg('content');
        // $technical = Evaluation::avg('technical');

        // $media = $pedagogical + $content + $technical / 3;
        // $e = Evaluation::all();
        
        // return response()->json($pedagogical);
        
        $evaluation = Evaluation::whereIn('network_user_id', $nu_id)->with('networkUser.user.person')
                                ->join('main_object', 'evaluation.target_object_id', '=', 'main_object.target_object_id')->where('main_object.oda_type', $ot)
                                ->select('evaluation.*', 'main_object.oda_type', 'main_object.title', 'main_object.description')->paginate();
        
        foreach($evaluation as $key=>$value) {
            $avg = round(($value->technical + $value->pedagogical + $value->content) / 3);
            if($avg != $total) {
                unset($evaluation[$key]);
            }
        }

        return response()->json($evaluation);
    }

}
