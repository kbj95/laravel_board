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
use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\DB; // DB객체 사용 (쿼리빌더)
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    function login(){
        
        // 로그 남기기
        // $arr['key'] = 'test';
        // $arr['kim'] = 'park';
        // Log::emergency('emergency', $arr);
        // Log::alert('aler', $arr);
        // Log::critical('critical', $arr);
        // Log::error('error', $arr);
        // Log::warning('warning', $arr);
        // Log::notice('notice', $arr);
        // Log::info('info', $arr);
        // Log::debug('debug', $arr);

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
            $error = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('error', $error);
        }

        // 유저 인증작업
        Auth::login($user);
        // Auth:check() : 인증작업 성공여부를 boolean으로 반환
        if(Auth::check()){
            session($user->only(['id','name'])); // 세션에 인증된 회원 pk 등록 | name출력위해 name도 세션에 저장 v002 udt
            return redirect()->intended(route('boards.index'));
        }else{
            $error = '인증작업 에러';
            return redirect()->back()->with('error', $error);
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
        // $user = false; // 에러확인용
        if(!$user){
            $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다. 잠시 후에 회원가입을 다시 시도해주세요.';
            return redirect()->route('users.registration')->with('error', $error);
        }

        // 회원가입 완료 후 로그인 페이지로 이동
        return redirect()->route('users.login')->with('success','회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.');
    }

    function logout(){
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃 처리
        return redirect()->route('users.login');
    }

    function withdraw(){
        $id = session('id');
        // return var_dump(session()->all(),'id');

        // $result로 원래는 에러처리해줘야한다.
        $result = User::destroy($id);
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃 처리
        return redirect()->route('users.login');
    }

    function edit(){
        if(auth()->guest()){
            return redirect()->route('users.login');
        }

        $users = User::find(session('id'));

        return view('useredit')->with('data',$users);
    }

    function editpost(Request $request){
        // return var_dump($request->id); // 확인용

        // 유효성 체크
        // $request->validate([
        //     'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
        //     ,'password' => 'required_with:passwordChk|same:passwordChk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        // ]);

        // $users = User::find($request->id);
        // if($request->password){
        //     $users->password = Hash::make($request->password);
        // }
        // if($request->name){
        //     $users->name = $request->name;
        // }

        // $users->save();

        //--------------------------------------------------↓쓴생님 버전-------------------------------------------------------------
        // 수정할 항목을 배열에 담는 변수
        $arrKey = [];

        // 기존 데이터 획득
        $baseuser = User::find(Auth::user()->id);

        // 기존 패스워드 체크
        if(!Hash::check($request->bpassword, Auth::user()->password)) {
            return redirect()->back()->with('error', '현재 비밀번호가 일치하지 않습니다.');
        }

        if(Hash::check($request->password, Auth::user()->password)) {
            return redirect()->back()->with('error', '사용중인 비밀번호와 같습니다.');
        }

        // 수정할 항목을 배열에 담는 처리
        if($request->name !== $baseuser->name) {
            $arrKey[] = 'name';
        }

        if(isset($request->password)) {
            $arrKey[] = 'password';
        }

        // 유효성 체크를 하는 모든 항목 리스트
        $chkList = [
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'password' => 'required_with:passwordchk|same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
            ,'bpassword' => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ];
        $arrchk = [];

        // 유효성 체크할 항목 세팅
        $arrchk['bpassword'] = $chkList['bpassword'];
        foreach($arrKey as $val) {
            $arrchk[$val] = $chkList[$val];
        }

        // 유효셩 체크
        $request->validate($arrchk);

        // 수정할 데이터 셋팅
        foreach($arrKey as $val) {
            if($val === 'password') {
                $baseuser->$val = Hash::make($request->password);
                continue; // 반복문이 돌때 continue를 만나면, 그 다음 반복으로 넘어감($val === 'password'일 경우, 바로밑의 $baseuser->$val = $req->$val;는 실행안됨)
            }
            $baseuser->$val = $request->$val;
        }
        $baseuser->save(); // update

        //--------------------------------------------------쓴생님 버전끝-------------------------------------------------------------
        return redirect()->route('boards.index');
    }
}
