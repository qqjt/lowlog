@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="card-header"><h2>{{$post->title}}</h2></div>
                    <div class="card-block">
                        {{$post->content}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection