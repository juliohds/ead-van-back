<?php

namespace App\Http\Controllers;

use App\Comment;
use App\User;
use App\NetworkUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CommentController extends Controller
{

    public function showAll($idNetwork){

        $nu_id = NetworkUser::where('network_id', $idNetwork)->pluck('id')->toArray();
        $comments = Comment::whereIn('network_user_id', $nu_id)->with('networkUser.user.person')->paginate();
        
        return response()->json($comments);
    }
    public function showComments($idNetwork, $idTargetObject)
    {
        $nu_id = NetworkUser::where('network_id', $idNetwork)
                                ->pluck('id')->toArray();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $comments = Comment::whereIn('network_user_id', $nu_id)
                            ->where('target_object_id', $idTargetObject)->with('networkUser.user.person')->get();

        if($comments == null){
            throw new \App\Exceptions\NotFoundException;
        }

        return response()->json($comments);
    }

    public function registerComment(Request $request, $idNetwork, $idTargetObject){

        if($idNetwork == null || $idTargetObject == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $nu_id = NetworkUser::where('user_id', $request->auth->id)->where('network_id', $idNetwork)
                                ->pluck('id')->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $comment = new Comment;
        $comment->setAttribute('text', $request->text);
        $comment->setAttribute('network_user_id', $nu_id);
        $comment->setAttribute('target_object_id', $idTargetObject);

        $comment->save();
        
        $comment->first();
        return response()->json($comment);
    }

    public function update(Request $request, $idNetwork, $id){
        
        $comment = Comment::find($id);
        
        if($comment == null || $request->text == ""){
            throw new \App\Exceptions\NotFoundException;
        }

        $comment->fill($this->input());
        $comment->update();

        return response()->json($comment);
    }

    public function qtdComment($idNetwork){

        $nu_id = NetworkUser::where('network_id', $idNetwork)
                                ->pluck('id')->toArray();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $comment = Comment::whereIn('network_user_id', $nu_id)
                            ->count();

        return response()->json($comment);
    }

    public function delete($idNetwork, $id){

        Comment::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);

    }

    public function changeStatus(Request $request, $idNetwork, $id){
        $comment = Comment::find($id);
        
        if($comment == null || $request->enabled == ""){
            throw new \App\Exceptions\NotFoundException;
        }

        $comment->fill($this->input());
        $comment->update();

        return response()->json($comment);
    }

}
