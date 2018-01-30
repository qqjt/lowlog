<ul id="comments-list" class="list-unstyled">
    @foreach($comments as $comment)
        <li class="media @if(!$loop->first) mt-3 @endif">
            <a class="mr-3" href="">
                <img class="media-object img-thumbnail rounded-circle"
                     alt="{{$comment->author_name}}"
                     src="{{ Gravatar::src($comment->email) }}">
            </a>
            <div class="media-body">
                <div class="media-heading">
                    <a href=""
                       title="{{$comment->author_name}}">
                        {{$comment->author_name}}
                    </a>
                    <!-- TODO floor, nice time -->
                    <span class="pull-right">{{(string)$comment->created_at}}</span>
                    {{--<div class="meta">
                        <span class="" title=""></span>
                    </div>--}}
                </div>
                {!! $comment->html !!}
            </div>
        </li>
    @endforeach
</ul>
<nav>
    {{$comments->links()}}
</nav>