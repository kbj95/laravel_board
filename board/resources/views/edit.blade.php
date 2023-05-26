<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>수정페이지</title>
</head>
<body>
    <form action="{{route('boards.update',['board' => $data->id])}}" method="POST">
        @csrf
        @method('put')
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title" value="{{$data->title}}">
        <br>
        <label for="content">내용 : </label>
        <textarea name="content" id="content">{{$data->content}}</textarea>
        <button type="submit">수정</button>
        <button type="button" onclick="location.href='{{route('boards.show',['board' => $data->id])}}'">취소</button>
    </form>
</body>
</html>