@extends('layout.layout')

@section('title', 'Registration')

@section('contents')
    <h3>Registration</h3>
    @include('layout.errorsvalidate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="name">name : </label>
        <input type="text" name="name" id="name" value="{{old('name')}}">
        <br>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email" value="{{old('email')}}">
        <br>
        <label for="password">password : </label>
        <input type="password" name="password" id="password">
        <br>
        <label for="passwordChk">password check : </label>
        <input type="password" name="passwordChk" id="passwordChk">
        <br>
        <button type="submit">registration</button>
        <button type="button" onclick="location.href='{{route('users.login')}}'">CANCEL</button>
    </form>
@endsection