<?php
/**********************************************
 * 프로젝트명   : laravel_board
 * 디렉토리     : Controllers
 * 파일명       : BoardController.php
 * 이력         : v001 0526 BJ.Kwon new
 *              v002 0530 BJ.Kwon 유효성 체크 추가
 **********************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB객체 사용 (쿼리빌더)
use Illuminate\Support\Facades\Validator; // v002 add
use App\Models\Boards; // Model객체 사용 (ORM)


class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 로그인 체크 
        // if(auth()->guest()){
        //     return redirect()->route('users.login');
        // }

        $test = DB::select('
            SELECT id,email
            FROM users
        ');
        $result = Boards::select(['id','title','hits','created_at','updated_at'])->orderBy('hits','desc')->get();
        // return view('list')->with('data', $result);
        return view('list')->with('test', $test)->with('data', $result);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // v002 add start
        $req->validate([
            'title' => 'required|between:3,30'
            ,'content' => 'required|max:1000'
        ]);
        // v002 add end

        // 새로 생성해야 하는 데이터(insert는 db에 데이터가 존재하지x)이기 때문에 새로운 엘로퀀트 객체를 생성함
        $boards = new Boards([
            'title' => $req->input('title')
            ,'content' => $req->input('content')
            // ,'hits' => 0 // v002 del
        ]);
        // insert
        $boards->save();
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id);
        $boards->hits++; // 조회수 올려주기
        $boards->timestamps=false; // v002 add 조회수가 올라가도 수정일자가 변경되지않도록 설정
        $boards->save();

        return view('detail')->with('data',Boards::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // find() : 예외 발생시 false만 리턴, 프로그램이 계속 실행됨
        // findOrFail() : 예외 발생시 에러처리(404 error)
        $boards = Boards::find($id);
        return view('edit')->with('data', $boards);
    }

    /**
     * Update the specified resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 1. DB ver
        // DB::update('
        //     UPDATE boards
        //     SET title = :title
        //         ,content = :content
        //     WHERE id = :id
        // ',[
        //     'title' => $request->title
        //     ,'content' => $request->content
        //     ,'id' => $id
        // ]);

        // 2. ORM ver1
        // v002 add start--------------------

        // id를 리퀘스트 객체에 합치는 방법1
        $request->request->add(["id" => $id]);
        // id를 리퀘스트 객체에 합치는 방법2
        // $arr = ['id' => $id];
        // $reqest->merge($arr);

        // 유효성 검사 방법 1
        // $request->validate([
        //     'id' => 'required|interger'
        //     ,'title' => 'required|between:3,30'
        //     ,'content' => 'required|max:2000'
        // ]);

        // 유효성 검사 방법 2
        $validator = Validator::make(
            $request->only('id','title','content')
            ,[
                'id' => 'required|integer'
                ,'title' => 'required|between:3,30'
                ,'content' => 'required|max:2000'
            ]
        );

        // redirect()->back() : 이전에 요청이 왔던 페이지로 돌아감
        // withInput() : 사용자가 적은값을 session에 저장..?
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->only('title','content'));
        }

        // v002 add end-----------------------

        $boards = Boards::find($id);
        $boards->title = $request->title;
        $boards->content = $request->content;
        $boards->save();

        // 3. ORM ver2 체이닝

        // Boards::where('id', $id)->update([
        //     'title'     => $request->title
        //     ,'content'  => $request->content
        // ]);

        // 둘중 하나 사용
        // return redirect('/boards'.'/'.$id);
        return redirect()->route('boards.show',['board' => $id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    // 1.DB ver
    //     DB::update('
    //     UPDATE boards
    //     SET deleted_at = now()
    //     WHERE id = :id
    // ',[
    //     'id' => $id
    // ]);

    // 2. ORM ver
    // $boards = Boards::find($id);
    // $boards->deleted_at = now();
    // $boards->save();

    // 3. softDelte ver
    // $boards = Boards::find($id);
    // $boards->delete();

    // Boards::find($id)->delete(); // 이렇게 적어도된다.

    // 4. ver 4
    Boards::destroy($id);

    return redirect('/boards');

    }

}
