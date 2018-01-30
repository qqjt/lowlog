@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header">
                        <h1>
                            <a href="{{route('post.show', ['hashid'=>$post->hashid])}}">{{$post->title}}</a>
                        </h1>
                        <ul class="list-inline list-unstyled">
                            <li><span><i class="fa fa-calendar"></i>{{(string)$post->created_at}} </span></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        {!! $post->html !!}
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
                <!-- Comments list ajax -->
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="total">{{__("Comments: ")}}<b id="comments-count">{{$post->comments_count}}</b></div>
                    </div>
                    <div id="comments" class="card-body">
                        @include('comment.load')
                    </div>
                </div>

                <!--Comment Form-->
                <div class="mt-3">
                    <form role="form">
                        {!! csrf_field() !!}
                        <div class="form-group">
                                <textarea title="{{__("Comment")}}" placeholder="{{__("Add a comment")}}" id="comment"
                                          class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-raised save-comment"><i
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
        $(document).ready(function () {
            //Markdown editor
            var simplemde = new SimpleMDE({
                element: document.getElementById("comment"),
                spellChecker: false,
                tabSize: 4,
                status: false
            });
            //Ajax load comments
            $('body').on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                getComments(url);
            });
            function getComments(url) {
                $.ajax({
                    url : url
                }).done(function (data) {
                    $('#comments').html(data);
                }).fail(function () {
                    alert("{{__("Comments could not be loaded.")}}");
                });
            }
            //Add comment action
            $(document).on('click', '.save-comment', function () {
                console.log(1111);
                $('input[name="content"]').val(simplemde.value());
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
                            var errors = data.responseJSON;
                            for (var o in errors) {
                                swal({
                                    title: errors[o][0],
                                    type: "error"
                                });
                                break;
                            }
                        } else {
                            swal({
                                title: "回复失败！",
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