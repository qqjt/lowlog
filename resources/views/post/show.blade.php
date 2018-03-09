@extends('layouts.app')
@section('title'){{$post->title}} - {{config('app.name')}}@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>
                            <a href="{{route('post.show', ['hashid'=>$post->hashid])}}">{{$post->title}}</a>
                        </h1>
                        <ul class="list-inline list-unstyled">
                            <li><span><i class="fa fa-calendar"></i>&nbsp;{{(string)$post->posted_at}} </span></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        {!! $post->html !!}
                    </div>
                    <div class="card-footer">
                        <div class="pull-right">
                            <a href="{{route('post.edit', [$post])}}">
                                <button type="button" class="btn btn-primary"><i
                                            class="fa fa-edit"></i>&nbsp;{{__("Edit")}}</button>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Comments list ajax -->
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="total">{{__("Comments: ")}}<b>{{$post->comments_count}}</b></div>
                    </div>
                    <div id="comments" class="card-body">
                        @include('comment.load')
                    </div>
                </div>

                <!--Comment Form-->
                <div class="mt-3">
                    <form role="form">
                        {!! csrf_field() !!}
                        @if(Auth::guest())
                            <div class="form-group">
                                <label for="author_name">{{__("Name: ")}}</label>
                                <input type="text" class="form-control" name="author_name" id="author_name" placeholder="{{__("Name")}}">
                            </div>
                            <div class="form-group">
                                <label for="email">{{__("Email Address: ")}}</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="{{__("Email address")}}">
                            </div>
                            <div class="form-group">
                                <label for="url">{{__("Website: ")}}</label>
                                <input type="text" class="form-control" name="url" id="url" placeholder="{{__("http(s)://")}}">
                            </div>
                        @endif
                        <div class="form-group">
                            <textarea title="{{__("Comment")}}" placeholder="{{__("Add a comment")}}" id="comment"
                                      class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-raised save-comment" data-editor="comment"><i
                                    class="fa fa-reply"></i>&nbsp;{{__("Comment")}}
                        </button>
                        <input type="hidden" name="content">
                        <input type="hidden" name="post_hashid" value="{{$post->hashid}}">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <link href="{{asset('vendor/simplemde/simplemde.min.css')}}" rel="stylesheet">
    <style>
        .CodeMirror, .CodeMirror-scroll {
            min-height: 6em;
        }
    </style>
@endsection

@section('script')
    <script src="{{asset('vendor/simplemde/simplemde.min.js')}}"></script>
    <script>
        var editors = [];
        $(document).ready(function () {
            //Markdown editor
            editors['comment'] = new SimpleMDE({
                element: document.getElementById("comment"),
                spellChecker: false,
                tabSize: 4,
                status: false,
                autoDownloadFontAwesome: false
            });
            //Ajax load comments
            $('body').on('click', '.pagination a', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                getComments(url);
            });

            function getComments(url) {
                $.ajax({
                    url: url
                }).done(function (data) {
                    $('#comments').html(data);
                }).fail(function () {
                    alert("{{__("Comments could not be loaded.")}}");
                });
            }

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
                            }, function () {
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