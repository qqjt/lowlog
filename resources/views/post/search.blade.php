@extends('layouts.two')
@section('content')
    <h1 class="bd-title">{{__("Search results for \":q:\"", ['q'=>request('q')])}}</h1>
    @if(count($paginatedPosts))
        <ul class="list-unstyled mb-0">
            @foreach($paginatedPosts as $post)
                <li class="media mb-3">
                    <div class="media-body">
                        <h4 class="media-heading"><a
                                    href="{{route('post.show', ['hashid'=> $post->hashid])}}">{{$post->title}}</a>
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
                                                    class="badge badge-primary">{{$tag->display_name}}</span></a>
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
                {{$paginatedPosts->links()}}
            </nav>
        </div>
    @else
        {{__('Nothing here.')}}
    @endif
@endsection