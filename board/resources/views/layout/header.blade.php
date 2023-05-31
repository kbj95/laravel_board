<h2>HEADER</h2>

{{-- 로그인 상태(인증된 상태) --}}
@auth
    <div>{{Auth::user()->name}}님 환영합니다.</div>
    <div>
        <a href="{{route('users.logout')}}">로그아웃</a>
        <a href="{{route('users.edit')}}">회원정보 수정</a>
    </div>
@endauth

{{-- 비로그인 상태(미인증 상태) --}}
@guest
    <div><a href="{{route('users.login')}}">로그인</a></div>
@endguest
<hr>