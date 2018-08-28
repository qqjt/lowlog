<div class="profile-sidebar text-center d-none d-md-block">
    <!-- SIDEBAR USERPIC -->
    <div class="profile-avatar"
         style="float: none;
            margin: 0 auto;
            width: 50%;
            height: 50%;
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;">
        <img src="{{cdn('avatar.jpg')}}" class="rounded-circle" alt="">
    </div>
    <!-- END SIDEBAR USERPIC -->
    <!-- SIDEBAR USER TITLE -->
    <div class="profile-title py-1">
        <div class="profile-title-name">
            {{config('blog.blogger.name')}}
        </div>
        <div class="profile-title-job">
            {{config('blog.blogger.desc')}}
        </div>
    </div>
    <!-- END SIDEBAR USER TITLE -->
    <!-- SIDEBAR BUTTONS -->
    <div class="profile-buttons">
        <a href="https://twitter.com/{{config('blog.blogger.twitter')}}" title="twitter/{{config('blog.blogger.twitter')}}"><button type="button" class="btn btn-primary btn-sm"><i class="fa fa-twitter"></i></button></a>
        <a href="https://github.com/{{config('blog.blogger.github')}}" title="github/{{config('blog.blogger.github')}}"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-envelope"></i></button></a>
    </div>
    <!-- END SIDEBAR BUTTONS -->
    <!-- SIDEBAR MENU -->
    {{--<nav class="profile-menu py-2 mx-3">--}}
        {{--<div class="list-group list-group-flush">--}}
            {{--<hr>--}}
            {{--<a href="{{route('post.index')}}" class="list-group-item list-group-item-action">--}}
                {{--<i class="fa fa-home"></i>&nbsp;{{__("Home")}}--}}
            {{--</a>--}}
            {{--<a href="https://github.com/qqjt" target="_blank" class="list-group-item list-group-item-action">--}}
                {{--<i class="fa fa-github"></i>&nbsp;qqjt <i class="fa fa-external-link"></i>--}}
            {{--</a>--}}
            {{--<hr>--}}
        {{--</div>--}}
    {{--</nav>--}}
    <!-- END MENU -->
</div>