@extends('layouts.app')
@section('content')
    <div class="container">
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
            <div class="form-group{{ $errors->has('posted_at') ? ' has-error' : '' }}">
                <label for="posted_at">{{__('Posted at')}}</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="posted_at" name="posted_at"
                           placeholder="{{__("Post datetime")}}">
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
            <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                <label>{{__('Category')}}</label>
                <div id="categories">
                    @foreach($categories as $category)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="{{$category->id}}"
                                   id="category-{{$category->id}}" name="categories[]">
                            <label class="form-check-label" for="category-{{$category->id}}">
                                {{$category->name}}
                            </label>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#new-category-modal"><i class="fa fa-plus"></i></button>
                @if ($errors->has('posted_at'))
                    <span class="help-block">
                        <strong>{{ $errors->first('posted_at') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                <label for="tags">{{__('Tags')}}</label>
                <select class="form-control" name="tags[]" id="tags" multiple
                        placeholder="{{__("Type and hit 'Enter'")}}"></select>
            </div>
            <div class="form-group{{ $errors->has('is_draft') ? ' has-error' : '' }}">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_draft" value="1" id="is_draft">
                    <label class="form-check-label" for="is_draft">
                        {{__("Draft?")}}
                    </label>
                </div>
            </div>
            <button id="save-post-btn" type="button" class="btn btn-primary">{{__('Submit')}}</button>
            {!! csrf_field() !!}
        </form>
    </div>
    <!--add category modal-->
    <div class="modal fade" id="new-category-modal" tabindex="-1" role="dialog"
         aria-labelledby="new-category-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="new-category-modal-title">{{__("New Category")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="new-category-form">
                        @csrf
                        <div class="form-group">
                            <label for="category-name" class="col-form-label"></label>
                            <input type="text" class="form-control" id="category-name" name="name"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{__("Close")}}</button>
                    <button type="button" class="btn btn-primary"
                            id="save-category-btn">{{__("Save")}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{cdn(mix('/vendor/simplemde/simplemde.min.js'))}}"></script>
    <script src="{{cdn(mix('/vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.js'))}}"></script>
    <script src="{{cdn(mix('/vendor/inline-attachment/inline-attachment.min.js'))}}"></script>
    <script src="{{cdn(mix('/vendor/inline-attachment/codemirror-4.inline-attachment.min.js'))}}"></script>
    <script src="{{cdn(mix('/vendor/moment/moment-with-locales.min.js'))}}"></script>
    <script src="{{cdn(mix('/vendor/pc-bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js'))}}"></script>
    <script>
        $(document).ready(function () {
            // markdown editor & file upload
            var simplemde = new SimpleMDE({
                autoDownloadFontAwesome: false,
                element: document.getElementById("content"),
                spellChecker: false,
                tabSize: 4,
                autosave: {
                    enabled: true,
                    delay: 3,
                    uniqueId: 'create_post'
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
            // datetimepicker & tagsinput
            $('#posted_at').datetimepicker({
                locale: 'zh-cn',
                format: 'YYYY-MM-DD HH:mm:ss'
            });
            $('#tags').tagsinput({
                tagClass: 'badge badge-primary'
            });
            //add category
            $(document).on('click', '#save-category-btn', function () {
                $.ajax({
                    type: 'post',
                    url: '{{route('category.store')}}',
                    data: $('#new-category-form').serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        $('#save-category-btn').prop('disabled', true);
                    },
                    success: function (res) {
                        if (res.code === 0) {
                            $('#new-category-modal').modal('hide');
                            $('#categories').append('<div class="form-check form-check-inline">\n' +
                                '<input class="form-check-input" type="checkbox" value="'+ res.data.id +'" id="category'+ res.data.id +'" checked>\n' +
                                '<label class="form-check-label" for="category'+ res.data.id +'">\n' + res.data.name +
                                '</label>\n' +
                                '</div>');
                            swal({
                                title: res.message
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
                                title: "{{__('Category creating failed!')}}",
                                type: "error"
                            });
                        }
                    },
                    complete: function () {
                        $('#save-category-btn').prop('disabled', false);
                    }
                });
            });
            // save post
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
                            }).then((value) => {
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
    <link href="{{cdn(mix('/vendor/simplemde/simplemde.min.css'))}}" rel="stylesheet">
    <link href="{{cdn(mix('/vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.css'))}}" rel="stylesheet">
    <link href="{{cdn(mix('/vendor/pc-bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css'))}}"
          rel="stylesheet">
@endsection