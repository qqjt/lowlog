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
    @include('elements.analytics')
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
        <li class="list-inline-item"><span><i class="fa fa-calendar"></i>&nbsp;{{(string)$post->posted_at}} </span></li>
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
                <div class="total">{{__("Comments:")}}<b>&nbsp;{{$post->comments_count}}</b></div>
            </div>
            <div id="comments" class="card-body">

            </div>
        </div>
    @endif
    <!--Comment Form-->
    <div class="mt-3">
        <form role="form" id="comment-form">
            @if(Auth::guest())
                <div class="form-group">
                    <label for="author_name">{{__("Name:")}}</label>
                    <input type="text" class="form-control" name="author_name" id="author_name"
                           placeholder="{{__("Name")}}">
                </div>
                <div class="form-group">
                    <label for="email">{{__("Email Address:")}}</label>
                    <input type="text" class="form-control" name="email" id="email"
                           placeholder="{{__("Email address")}}">
                </div>
                <div class="form-group">
                    <label for="url">{{__("Website:")}}</label>
                    <input type="text" class="form-control" name="url" id="url"
                           placeholder="{{__("http(s)://")}}">
                </div>
            @endif
            <div id="reply-comment-hint" class="alert alert-info fade show d-none" role="alert">
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p class="mb-0"></p>
            </div>
            <div class="form-group">
                            <textarea title="{{__("Comment")}}" placeholder="{{__("Add a comment")}}" id="comment"
                                      class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-raised save-comment" data-editor="comment"><i
                        class="fa fa-reply"></i>&nbsp;{{__("Comment")}}
            </button>
            <input type="hidden" name="content">
            <input type="hidden" name="parent_hashid" value="">
            {!! csrf_field() !!}
        </form>
    </div>
@endsection
@section('script')
    <script src="{{cdn(mix('/vendor/simplemde/simplemde.min.js'))}}"></script>
    <script src="{{cdn(mix("/vendor/prism/prism.js"))}}"></script>
    <script>
        var editors = [];
        $(document).ready(function () {
            //Markdown editor
            editors['comment'] = new SimpleMDE({
                element: document.getElementById("comment"),
                spellChecker: false,
                tabSize: 4,
                status: false,
                autoDownloadFontAwesome: false,
            });
            //Ajax load comments
            $('body').on('click', '.pagination a', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                getComments(url);
            });

            function getComments(url) {
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {"_token": "{{csrf_token()}}"}
                }).done(function (data) {
                    $('#comments').html(data);
                }).fail(function () {
                    alert("{{__("Comments could not be loaded.")}}");
                });
            }

            getComments("{{route('comment.load', ['post'=>$post->hashid])}}");

            //Reply button click
            $(document).on('click', '.reply-comment', function () {
                $('#reply-comment-hint').removeClass('d-none');
                $('#reply-comment-hint p').html('{{__("Replies to")}} ' + $(this).data('author-name'));
                $('#comment-form input[name="parent_hashid"]').val($(this).data('hashid'));
            });

            //Dismiss reply to someone
            $(document).on('click', '#reply-comment-hint .close', function () {
                $('#reply-comment-hint').addClass('d-none');
                $('#comment-form input[name="parent_hashid"]').val('');
            });

            //Add comment action
            $(document).on('click', '.save-comment', function () {
                var editor_name = $(this).data('editor');
                $(this).closest('form').find('input[name="content"]').val(editors[editor_name].value());
                var _self = $(this);
                $.ajax({
                    type: 'post',
                    url: '{{route('comment.store', ['post'=>$post])}}',
                    data: $(this).closest('form').serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        _self.prop('disabled', true);
                    },
                    success: function (res) {
                        if (res.code === 0) {
                            swal({
                                title: res.message
                            }).then((value) => {
                                location.reload();
                            });
                        } else {
                            swal({
                                title: res.message,
                                type: "error"
                            });
                        }
                    },
                    error: function (data) {
                        if (data.status === 422) {
                            var res = data.responseJSON;
                            for (var o in res.errors) {
                                swal({
                                    title: res.errors[o],
                                    type: "error"
                                });
                                break;
                            }
                        } else {
                            swal({
                                title: "{{__("Comment failed.")}}",
                                type: "error"
                            });
                        }
                    },
                    complete: function () {
                        _self.prop('disabled', false);
                    }
                })
            });
        });
    </script>
@endsection