<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    {{-- BoardsController에 store메소드안에 validate --}}
    @include('layout.errorsvalidate')
    <form action="{{route('boards.store')}}" method="POST">
        @csrf
        <label for="title">제목 : </label>
        {{-- old()메소드 : 유효성 검사 error 발생시 기존값 유지 --}}
        <input type="text" name="title" id="title" value="{{old('title')}}">
        <br>
        <label for="content">내용 : </label>
        <textarea name="content" id="content">{{old('content')}}</textarea>
        <button type="submit">작성</button>
    </form>
</body>
</html>