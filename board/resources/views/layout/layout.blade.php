<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('Css/common.css')}}">
    <title>@yield('title','Kwon Home')</title>
</head>
<body>
    @include('layout.header')
    @yield('contents')
    @include('layout.footer')
</body>
</html>