@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
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
                                        @if(!$post->categories->isEmpty())
                                            <li class="list-inline-item">
                                                <span><i class="fa fa-folder"></i>
                                                    @foreach($post->categories as $category)
                                                        <a href="{{route('post.index', ['cat'=>$category->name])}}">{{$category->name}}</a>
                                                        @if(!$loop->last),@endif
                                                    @endforeach
                                                </span>
                                            </li>
                                        @endif
                                        @if(!$post->tags->isEmpty())
                                            <li class="list-inline-item">
                                                <span><i class="fa fa-tags"></i></span>
                                                @foreach($post->tags as $tag)
                                                    <a href="{{route('post.index', ['tags'=>$tag->tag_value])}}"><span
                                                                class="badge badge-primary">{{$tag->display_name}}</span></a>
                                                @endforeach
                                            </li>
                                        @endif
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
@endsection
@section('style')
    @include('elements.analytics')
@endsection