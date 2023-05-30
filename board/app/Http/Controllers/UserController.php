<?php
/**********************************************
 * 프로젝트명   : laravel_board
 * 디렉토리     : Controllers
 * 파일명       : UserController.php
 * 이력         : v001 0530 BJ.Kwon new
 **********************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    function login(){
        return view('login');
    }

    function loginpost(Request $req){
        // 유효성 체크
        $req->validate([
            'email'    => 'required|email|max:100'
            ,'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 유저정보 습득
        $user = User::where('email',$req->email)->first();
        if(!$user || !(Hash::check($req->password,$user->password))){
            $errors[] = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('errors', collect($errors));
        }

        // 유저 인증작업
        Auth::login($user);
        // Auth:check() : 인증작업 성공여부를 boolean으로 반환
        if(Auth::check()){
            session([$user->only('id')]); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index'));
        }else{
            $errors[] = '인증작업 에러';
            return redirect()->back()->with('errors', collect($errors));
        }
    }

    function registration(){
        return view('registration');
    }

    function registrationpost(Request $req){
        // 유효성 체크
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            // required_with ~ | same ~ : 값이 같은지 판별해줌
            ,'password' => 'required_with:passwordChk|same:passwordChk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password); // Hash::make : 암호화

        $user = User::create($data); // insert, 결과가 $user에 담김
        if(!$user){
            $errors[] = '시스템 에러가 발생하여, 회원가입에 실패했습니다.';
            $errors[] = '잠시 후에 회원가입을 다시 시도해주세요.';
            return redirect()->route('users.registration')->with('errors', collect($errors));
        }

        // 회원가입 완료 후 로그인 페이지로 이동
        return redirect()->route('users.login')->with('success','회원가입을 완료 했습니다.<br>');
    }
}
