@extends('layouts.two')
@section('title'){{$post->title}} - {{config('app.name')}}@endsection
@section('meta')
    <link rel="canonical" href="{{route('post.show', ['post'=>$post->hashid])}}"/>
    <meta name="description" content="{{$post->excerpt}}">
@endsection
@section('style')
    <link href="{{cdn(mix('/vendor/simplemde/simplemde.min.css'))}}" rel="stylesheet">
    <link href="{{cdn(mix("/vendor/prism/prism.css"))}}" rel="stylesheet">
    <style>
        .CodeMirror, .CodeMirror-scroll {
            min-height: 6em;
        }
    </style>
    {{--<script type="application/ld+json">
        {
            "@context": "https://ziyuan.baidu.com/contexts/cambrian.jsonld",
            "@id": "{{route('post.show', ['post'=>$post->hashid])}}",
            "appid": "1586661500046202",
            "title": "{{$post->title}}",
            "images": [
            ],
            "pubDate": "{{$post->posted_at->format("Y-m-d\TH:i:s")}}"
        }
    </script>--}}
@endsection
@section('sidebar')
    <nav class="collapse bd-links">
        {!! $post->toc !!}
    </nav>
@endsection
@section('content')
    <h1 class="bd-title">
        <a href="{{route('post.show', ['hashid'=>$post->hashid])}}">{{$post->title}}</a>
    </h1>
    <ul class="list-inline list-unstyled">
        <li><span><i class="fa fa-calendar"></i>&nbsp;{{(string)$post->posted_at}} </span></li>
    </ul>
    {!! $post->html !!}
    <div class="clearfix">
        <div class="float-right">
            @can('update', $post)
                <a href="{{route('post.edit', [$post])}}">
                    <button type="button" class="btn btn-primary"><i
                                class="fa fa-edit"></i>&nbsp;{{__("Edit")}}</button>
                </a>
            @endcan
        </div>
    </div>
    <!-- Comments list ajax -->
    @if($post->comments_count)
        <div class="card mt-3">
            <div class="card-header">
                <div class="total">{{__("Comments: ")}}<b>{{$post->comments_count}}</b></div>
            </div>
            <div id="comments" class="card-body">
                @include('comment.load')
            </div>
        </div>
    @endif
@endsection
@section('script')
    <script src="{{cdn(mix('/vendor/simplemde/simplemde.min.js'))}}"></script>
    <script src="{{cdn(mix("/vendor/prism/prism.js"))}}"></script>
@endsection