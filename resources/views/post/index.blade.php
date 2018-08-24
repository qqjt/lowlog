@extends('layouts.two')
@section('sidebar')
    <div class="profile-sidebar text-center d-none d-md-block">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-avatar">
            <img src="{{url('avatar.jpg')}}" class="rounded-circle" alt="">
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
            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-twitter"></i></button>
            <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-envelope"></i></button>
        </div>
        <!-- END SIDEBAR BUTTONS -->
        <!-- SIDEBAR MENU -->
        <nav class="profile-menu py-2 mx-3">
            <div class="list-group list-group-flush">
                <hr>
                <a href="{{route('post.index')}}" class="list-group-item list-group-item-action">
                    <i class="fa fa-home"></i>&nbsp;{{__("Home")}}
                </a>
                <a href="https://github.com/qqjt" target="_blank" class="list-group-item list-group-item-action">
                    <i class="fa fa-github"></i>&nbsp;qqjt <i class="fa fa-external-link"></i>
                </a>
                <hr>
            </div>
        </nav>
        <!-- END MENU -->
    </div>
@endsection
@section('content')

    @if(count($paginatedPosts))
        <ul class="list-unstyled mb-0">
            @foreach($paginatedPosts as $post)
                <li class="media pt-3">
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
@endsection
@section('style')
    @include('elements.analytics')
    <style>
        .sidebar {
            border-right: 1px solid rgba(0, 0, 0, .1);
        }

        .profile-avatar img {
            float: none;
            margin: 0 auto;
            width: 50%;
            height: 50%;
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;
        }
    </style>
@endsection