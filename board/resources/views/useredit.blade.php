@extends('layout.layout')

@section('title', 'Login')

@section('contents')
    <h3>회원 정보 수정페이지</h3>
    @include('layout.errorsvalidate')
    {{-- {{var_dump($data->id)}} --}}
    <form action="{{route('users.editpost',['id' => $data->id])}}" method="post">
        @csrf
        <label for="email">이메일 : </label>
        <input type="text" id="email" name="email" value="{{$data->email}}" readonly>
        <br>
        <label for="bpassword">기존 비밀번호 : </label>
        <input type="password" id="bpassword" name="bpassword">
        <br>
        <label for="password">비밀번호 : </label>
        <input type="password" id="password" name="password">
        <br>
        <label for="passwordChk">비밀번호 확인 : </label>
        <input type="password" name="passwordChk" id="passwordChk">
        <br>
        <label for="name">이름 : </label>
        <input type="name" id="name" name="name" value="{{$data->name}}">
        <br>
        <button type="submit">수정하기</button>
        <a href="{{route('users.withdraw')}}">회원탈퇴</a>
        <button type="button" onclick="location.href='{{route('boards.index')}}'">취소</button>
    </form>
@endsection
