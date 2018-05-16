@extends('layouts.two')

@section('content')
    @foreach($archive as $year => $months)
        <h3 class="al_year">{{$year}} <em>({{__(":count posts", ['count'=>$months['count']])}})</em></h3>
        <?php unset($months['count']); ?>
        <ul class="al_mon_list">
            @foreach($months as $month =>$days)
                <span class="al_mon">{{$month}} <em>({{__(":count posts", ['count'=>$days['count']])}})</em></span>
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
@endsection
