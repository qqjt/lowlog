@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header">{{__('New Post')}}</div>
                    <div class="card-body">
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
    <script src="{{asset('vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('vendor/inline-attachment/inline-attachment.js')}}"></script>
    <script src="{{asset('vendor/inline-attachment/codemirror-4.inline-attachment.js')}}"></script>
    <script>
        $(document).ready(function () {
            var simplemde = new SimpleMDE({
                element: document.getElementById("content"),
                spellChecker: false,
                tabSize: 4
            });
            inlineAttachment.editors.codemirror4.attach(simplemde.codemirror, {
                uploadUrl: "{{route('image.upload')}}",
                extraParams: {
                    "_token": "{{csrf_token()}}"
                },
                onFileUploadResponse: function(xhr) {
                    var result = JSON.parse(xhr.responseText),
                        filename = result[this.settings.jsonFieldName];

                    if (result && filename) {
                        var newValue;
                        if (typeof this.settings.urlText === 'function') {
                            newValue = this.settings.urlText.call(this, filename, result);
                        } else {
                            newValue = this.settings.urlText.replace(this.filenameTag, filename);
                        }
                        var text = this.editor.getValue().replace(this.lastValue, newValue);
                        this.editor.setValue(text);
                        this.settings.onFileUploaded.call(this, filename);
                    }
                    return false;
                }
            });

            $('#tags').tagsinput({
                tagClass: 'badge badge-primary'
            });

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
                        if (res.code === 0) {
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