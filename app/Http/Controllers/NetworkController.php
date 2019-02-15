<?php

namespace App\Http\Controllers;


use App\Network;
use App\Menu;
use App\Comment;
use App\Evaluation;
use App\UserList;
use App\NetworkUser;
use App\NetworkConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\s3Helper;

class NetworkController extends Controller
{
    public function show() {
        $url = $this->request->input('url');

        if ($this->request->input('url') == "") {
           return $this->showAll();
        } else {
            $network = Network::where('url', '=', $url)
                ->orWhere('alternate_url','=',$url)
                ->orWhere('internal_url','=',$url)
                ->with('networkConfig.socials')
                ->with('networkConfig.institutionMenus')
                ->with('networkConfig.footerMenus')
                ->first();
            

            if($network == null) {
                throw new \App\Exceptions\NotFoundException;
            }
            $menu = Menu::where('network_config_id', $network->network_config_id)->get();

            $network['menu'] = $menu;

            return response()->json($network);
        }
    }

    public function showAllAmount($idNetwork){
        
        $nu_id = NetworkUser::where('network_id', $idNetwork)->pluck('id')->toArray();

        $nu = NetworkUser::where('network_id', $idNetwork)->count();
        if($nu == null){ $nu == 0;}
        
        $c = Comment::whereIn('network_user_id', $nu_id)->count();
        if($c == null){ $c == 0;}

        $e = Evaluation::whereIn('network_user_id', $nu_id)->count();
        if($e == null){ $e == 0;}
        
        $ul = UserList::whereIn('network_user_id', $nu_id)->count();

        $dados = [];
        $dados['network_users'] = $nu;
        $dados['comments'] = $c;
        $dados['evaluations'] = $e;
        $dados['user_lists'] = $ul;

        return response()->json($dados);

    }

    public function showAll() {
        $network = Network::all();

        if($network == null) {
            throw new \App\Exceptions\NotFoundException;
        }

        return response()->json($network);
    }

    public function update($idNetWork) {
        $network = Network::find($idNetWork);

        if ($network === null) {
            throw new \App\Exceptions\NotFoundException;
        }

        $data = $this->request->all();
        $networkConfigData = $this->input('network_config');

        if (empty($data)) {
            return response()->json(['error' => 'Empty body is not allowed'], Response::HTTP_BAD_REQUEST);
        }

        if ($network->network_config_id === null && !empty($networkConfigData)) {
            $networkConfig = new NetworkConfig();
            $networkConfig->fill($networkConfigData);
            $networkConfig->save();
            $network->networkConfig()->associate($networkConfig);
            $network->network_config_id = $networkConfig->id;
            $network->save();
        } elseif (!empty($networkConfigData)) {
            $networkConfig = $network->networkConfig;
            $networkConfig->fill($networkConfigData);
            $networkConfig->save();
        }

        $network->fill($data);
        $network->save();

        return $this->responseOK($network);
    }

    /*
    * Método criado para fazer a atualização dos dados da network com upload
    * de imagens, o suporte a arquivos só é reconhecido pelo método post,
    * portanto foi criado este método com a configuração da rota com o tipo da
    * requisição como post
    */
    public function updateWithImage($idNetWork) {
        $network = Network::find($idNetWork);

        if ($network === null) {
            throw new \App\Exceptions\NotFoundException;
        }

        $hasFileLogo = $this->request->hasFile('file_logo');
        $hasFileImagemPrincipal = $this->request->hasFile('file_imagem_principal');
        $data = $this->request->all();
        $networkConfigData = $this->input('network_config');

        if ($hasFileLogo || $hasFileImagemPrincipal) {
            $s3 = new s3Helper;
            // $destinationPath = public_path('/images');

            $this->validate($this->request, [
                'file_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'file_imagem_principal' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($hasFileLogo) {
                $image = $this->request->file('file_logo');
                $networkConfigData['url_logo'] =
                    $s3->storageLocalImgTempAndSendAsPublic($image);
            }
            if ($hasFileImagemPrincipal) {
                $image = $this->request->file('file_imagem_principal');
                $networkConfigData['url_imagem_principal'] =
                    $s3->storageLocalImgTempAndSendAsPublic($image);
            }
        } elseif (empty($data)) {
            return response()->json(['error' => 'Empty body is not allowed'], Response::HTTP_BAD_REQUEST);
        }

        if ($network->network_config_id === null && !empty($networkConfigData)) {
            $networkConfig = new NetworkConfig();
            $networkConfig->fill($networkConfigData);
            $networkConfig->save();
            $network->networkConfig()->associate($networkConfig);
            $network->network_config_id = $networkConfig->id;
            $network->save();
        } elseif (!empty($networkConfigData)) {
            $networkConfig = $network->networkConfig;
            $networkConfig->fill($networkConfigData);
            $networkConfig->save();
        }

        $network->fill($data);
        $network->save();

        return $this->responseOK($network);
    }

    public function delete($idNetWork){
        $network = Network::find($idNetWork);
        if($network->delete()){
            return response()->json("deletado usando softdelete");
        }
    }
}
