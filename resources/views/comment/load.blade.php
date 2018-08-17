<ul id="comments-list" class="list-unstyled">
    @foreach($comments as $comment)
        <li class="media @if(!$loop->first) mt-3 @endif">
            <a class="mr-3" href="{{$comment->url}}">
                <img class="media-object img-thumbnail rounded-circle"
                     alt="{{$comment->author_name}}"
                     src="{{ proxy_gravatar(Gravatar::src($comment->email)) }}">
            </a>
            <div class="media-body">
                <div class="media-heading">
                    <a href="{{$comment->url}}"
                       title="{{$comment->author_name}}">
                        {{$comment->author_name}}
                    </a>
                    @if($comment->parent)
                        {{__("Replies to")}}&nbsp;
                        <a href="{{$comment->parent->url}}"
                           title="{{$comment->parent->author_name}}">
                            {{$comment->parent->author_name}}
                        </a>
                    @endif
                    <!-- TODO floor, nice time -->
                    <span class="pull-right">{{(string)$comment->created_at}}</span>
                </div>
                {!! $comment->html !!}
                <ul class="list-inline pull-right">
                    <li class="list-inline-item">
                        <a href="javascript:;" class="reply-comment" data-hashid="{{$comment->hashid}}" data-author-name="{{$comment->author_name}}">
                            <span class="badge badge-secondary"><i class="fa fa-reply"></i>&nbsp;{{__("Reply")}}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    @endforeach
</ul>
<nav>
    {{$comments->links()}}
</nav>