@extends('layouts.two')
@section('content')
    @if(count($myPosts))
        <ul class="list-unstyled mb-0">
            @foreach($myPosts as $post)
                <li class="media mb-3">
                    <div class="media-body">
                        <h4 class="media-heading">
                            @if($post->is_draft)<a href="{{route('post.edit', [$post])}}"><span
                                        class="badge badge-success"><i class="fa fa-edit"></i></span></a>&nbsp;@endif
                            <a href="{{route('post.show', ['hashid'=> $post->hashid])}}">{{$post->title}}</a>
                        </h4>
                        <p>{{$post->excerpt}}</p>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <span><i class="fa fa-calendar"></i> {{(string)$post->posted_at}}</span>
                            </li>
                            <li class="list-inline-item">
                                @if(!$post->tags->isEmpty())
                                    @foreach($post->tags as $tag)
                                        <a href="{{route('post.index', ['tags'=>$tag->tag_value])}}"><span
                                                    class="badge badge-primary">{{$tag->tag_value}}</span></a>
                                    @endforeach
                                @endif
                            </li>
                        </ul>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="text-center">
            <nav>
                {{$myPosts->links()}}
            </nav>
        </div>
    @else
        {{__('Nothing here.')}}
    @endif
@endsection
