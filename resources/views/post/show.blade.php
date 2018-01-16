@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>
                            <a href="{{route('post.show', ['hashid'=>$post->hashid])}}">{{$post->title}}</a>
                        </h1>
                        <ul class="list-inline list-unstyled">
                            <li><span><i class="fa fa-calendar"></i>{{(string)$post->created_at}} </span></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        {!! $post->html !!}
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection