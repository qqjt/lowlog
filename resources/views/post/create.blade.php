@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="card-header">{{__('New Post')}}</div>
                    <div class="card-block">
                        <form method="post" action="{{route('post.store')}}">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title">{{__('Title')}}</label>
                                <input type="text" class="form-control" id="title" name="title"
                                       placeholder="{{__('what\'s up?')}}">
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                <label for="content">{{__('Content')}}</label>
                                <textarea id="content" name="content" class="form-control" rows="3" placeholder="{{__('blabla...')}}"></textarea>
                                @if ($errors->has('content'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('content') }}</strong>
                                </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                            {!! csrf_field() !!}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection