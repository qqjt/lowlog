@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{__("Archive")}}</div>
                    <div class="card-body">
                        @foreach($archive as $year => $months)
                            <h3 class="al_year">{{$year}}({{$months['count']}})</h3>
                            <?php unset($months['count']); ?>
                            <ul class="al_mon_list">
                                @foreach($months as $month =>$days)
                                    <span class="al_mon">{{$month}}<em>({{$days['count']}})</em></span>
                                    <?php unset($days['count']); ?>
                                    <ul class="al_post_list">
                                        @foreach($days as $day =>$posts)
                                            @foreach($posts['posts'] as $post)
                                                <li><a href="{{route('post.show', [$post])}}">{{$post->title}}</a></li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection