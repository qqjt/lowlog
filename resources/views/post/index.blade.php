@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-body">
                        @if(count($paginatedPosts))
                            @foreach($paginatedPosts as $post)
                                <div class="media">
                                    <a class="pull-left" href="">
                                        <img class="media-object img-thumbnail img-circle"
                                             src="{{ Gravatar::src($post->author->email) }}">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading"><a
                                                    href="{{route('post.show', ['hashid'=> $post->hashid])}}">{{$post->title}}</a>
                                        </h4>
                                        <p>{{$post->excerpt}}</p>
                                        <ul class="list-inline list-unstyled">
                                            <li>
                                                <span><i class="fa fa-calendar"></i> {{(string)$post->created_at}}</span>
                                            </li>
                                            <li>
                                                @if(!$post->tags->isEmpty())
                                                    @foreach($post->tags as $tag)
                                                        <a href="{{route('home', ['tags'=>$tag->tag_value])}}"><span class="label label-primary">{{$tag->tag_value}}</span></a>
                                                    @endforeach
                                                @endif

                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
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