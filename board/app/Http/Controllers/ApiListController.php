<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class ApiListController extends Controller
{
    public function getlist($id){
        $user = Boards::find($id);
        return response()->json($user, 200);
    }

    public function postlist(Request $req){
        // 유효성 체크
        $req->validate([
            'title' => 'required|between:3,30'
            ,'content' => 'required|max:2000'
        ]);

        $boards = new Boards([
            'title' => $req->title
            ,'content' => $req->content
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id','title','content');

        // return response()->json($boards, 200);
        return $arr;
    }

    public function putlist(Request $req, $id){
        $arrData = [
            'code' => '0'
            ,'msg' => ''
            ,'errmsg' => []
        ];

        $data = $req->only('title','content');
        $data['id'] = $id;

        // 유효성 체크
        $validator = Validator::make(
            $data
            ,[
                'id'        => 'required|integer|exists:boards' // exists : DB에 질의하기 때문에 주의해서 사용
                ,'title'    => 'required|between:3,30'
                ,'content'  => 'required|max:2000'
            ]
        );

        if($validator->fails()){
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validator Error';
            $arrData['errmsg'] = $validator->errors()->all();
        }
        // if($validator->fails()){
        //     return response()
        //         ->json(['errors' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        // }
        else{
            $boards = Boards::find($id);
            $boards->title = $req->title;
            $boards->content = $req->content;
            $boards->save();

            $arrData['code'] = '0';
            $arrData['msg'] = 'success';
            $arrData['data'] = $boards->only('id','title','content');
        }


        return $arrData;
    }

    public function deletelist($id){

        $arrData = [
            'code' => '0'
            ,'msg' => ''
            ,'errmsg' => []
        ];
        $data['id'] = $id;

        $validator = Validator::make($data, [
            'id' => 'required|integer|exists:boards'
        ]);

        if($validator->fails()){
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validator Error';
            $arrData['errmsg'] = $validator->errors()->all();
        }
        else{
            $boards = Boards::find($id);
            if($boards){
                $boards->delete();            
                $arrData['code'] = '0';
                $arrData['msg'] = 'success';
            }
            else{
                $arrData['code'] = 'E02';
                $arrData['msg'] = 'already deleted';
            }
        }
        return $arrData;
    }
}
