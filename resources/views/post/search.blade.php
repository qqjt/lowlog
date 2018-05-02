@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    @if(request()->has('q'))
                    <div class="card-header">
                        {{__("Search results for :q:", ['q'=>request('q')])}}
                    </div>
                    @endif
                    <div class="card-body">
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
                                                        <a href="{{route('post.index', ['tags'=>$tag->tag_value])}}"><span class="badge badge-primary">{{$tag->tag_value}}</span></a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection