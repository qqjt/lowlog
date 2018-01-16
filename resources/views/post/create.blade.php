@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{__('New Post')}}</div>
                    <div class="panel-body">
                        <form id="new-post-form" method="post" action="{{route('post.store')}}">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title">{{__('Title')}}</label>
                                <input type="text" class="form-control" id="title" name="title"
                                       placeholder="{{__("What's up?")}}">
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                <label for="content">{{__('Content')}}</label>
                                <textarea id="content" class="form-control" rows="3"
                                          placeholder="{{__("Blabla")}}"></textarea>
                                <input type="hidden" name="content">
                                @if ($errors->has('content'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('content') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                                <label for="tags">{{__('Tags')}}</label>
                                <select class="form-control" name="tags[]" id="tags" multiple placeholder="{{__("Type and hit 'Enter'")}}"></select>
                            </div>
                            <button id="save-post-btn" type="button" class="btn btn-primary">{{__('Submit')}}</button>
                            {!! csrf_field() !!}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('vendor/simplemde/simplemde.min.js')}}"></script>
    <script src="{{asset('vendor/simplemde/simplemde.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            var simplemde = new SimpleMDE({
                autoDownloadFontAwesome: true,
                element: document.getElementById("content"),
                spellChecker: false,
                tabSize: 4,
                toolbar: [
                    "bold", "italic", "heading", "|", "quote", "code", "table",
                    "horizontal-rule", "unordered-list", "ordered-list", "|",
                    "link", "image", "|", "preview", "side-by-side", 'fullscreen'
                ]
            });

            $('#tags').tagsinput();

            $(document).on('click', '#save-post-btn', function () {
                $('input[name="content"]').val(simplemde.value());
                $.ajax({
                    type: 'post',
                    url: '{{route('post.store')}}',
                    data: $('#new-post-form').serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $('#save-post-btn').prop('disabled', true);
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            swal({
                                title: res.message
                            }, function () {
                                if (res.data)
                                    location.href = res.data;
                            });
                        } else {
                            swal({
                                title: res.message,
                                type: "error"
                            });
                        }
                    },
                    error: function (data) {
                        if (data.status == 422) {
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
                                title: "{{__('Post creating failed!')}}",
                                type: "error"
                            });
                        }
                    },
                    complete: function () {
                        $('#save-post-btn').prop('disabled', false);
                    }
                })
            })
        })
    </script>
@endsection
@section('style')
    <link href="{{asset('vendor/simplemde/simplemde.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet">
@endsection