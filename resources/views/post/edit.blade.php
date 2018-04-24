@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{__('Edit: :post', ['post'=>$post->title])}}</div>
                    <div class="card-body">
                        <form id="edit-post-form" method="post" action="{{route('post.update', [$post])}}">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title">{{__('Title')}}</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{$post->title}}"
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
                                          placeholder="{{__('Blabla')}}">{{$post->content}}</textarea>
                                <input type="hidden" name="content">
                                @if ($errors->has('content'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('content') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('posted_at') ? ' has-error' : '' }}">
                                <label for="posted_at">{{__('Posted at')}}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="posted_at" name="posted_at"
                                           placeholder="{{__("Post datetime")}}" value="{{$post->posted_at}}">
                                    <span class="input-group-append input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </span>
                                </div>
                                @if ($errors->has('posted_at'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('posted_at') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                                <label for="tags">{{__('Tags')}}</label>
                                <select class="form-control" name="tags[]" id="tags" multiple
                                        placeholder="{{__("Type and hit 'Enter'")}}">
                                    @if($post->tags)
                                        @foreach($post->tags as $tag)
                                            <option value="{{$tag->tag_value}}">{{$tag->tag_value}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group{{ $errors->has('is_draft') ? ' has-error' : '' }}">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_draft"  value="1" id="is_draft" @if($post->is_draft) checked @endif>
                                    <label class="form-check-label" for="is_draft">
                                        {{__("Draft?")}}
                                    </label>
                                </div>
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
@section('style')
    <link href="{{cdn(mix('vendor/simplemde/simplemde.min.css'))}}" rel="stylesheet">
    <link href="{{cdn(mix('vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.css'))}}" rel="stylesheet">
    <link href="{{cdn(mix('vendor/pc-bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css'))}}" rel="stylesheet">
@endsection
@section('script')
    <script src="{{cdn(mix('vendor/simplemde/simplemde.min.js'))}}"></script>
    <script src="{{cdn(mix('vendor/simplemde/simplemde.min.js'))}}"></script>
    <script src="{{cdn(mix('vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.js'))}}"></script>
    <script src="{{cdn(mix('vendor/inline-attachment/inline-attachment.min.js'))}}"></script>
    <script src="{{cdn(mix('vendor/inline-attachment/codemirror-4.inline-attachment.min.js'))}}"></script>
    <script src="{{cdn(mix('vendor/moment/moment-with-locales.min.js'))}}"></script>
    <script src="{{cdn(mix('vendor/pc-bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js'))}}"></script>
    <script>
        $(document).ready(function () {
            var simplemde = new SimpleMDE({
                autoDownloadFontAwesome: false,
                element: document.getElementById("content"),
                spellChecker: false,
                tabSize: 4,
                toolbar: [
                    "bold", "italic", "heading", "|", "quote", "code", "table",
                    "horizontal-rule", "unordered-list", "ordered-list", "|",
                    "link", "image", "|", "preview", "side-by-side", 'fullscreen'
                ],
                autosave:{
                    enabled: true,
                    delay: 3,
                    uniqueId: 'edit_{{$post->hashid}}'
                }
            });
            inlineAttachment.editors.codemirror4.attach(simplemde.codemirror, {
                uploadUrl: "{{route('image.upload')}}",
                extraParams: {
                    "_token": "{{csrf_token()}}"
                },
                onFileUploadResponse: function (xhr) {
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

            $('#posted_at').datetimepicker({
                locale: 'zh-cn',
                format: 'YYYY-MM-DD HH:mm:ss'
            });

            $('#tags').tagsinput({tagClass: 'badge badge-primary'});

            $(document).on('click', '#save-post-btn', function () {
                $('input[name="content"]').val(simplemde.value());
                $.ajax({
                    type: 'post',
                    url: '{{route('post.update', [$post])}}',
                    data: $('#edit-post-form').serialize(),
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
                                title: "{{__('Post updating failed!')}}",
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