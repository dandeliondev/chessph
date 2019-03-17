<div class="ui secondary pointing menu">
    <a class="item {{$segments ===  'rating/ncfp/'  ? 'active' : '' }}" href="{{URL::to('rating/ncfp')}}">
        Ratings
    </a>
    <div class="ui pointing dropdown link item">
        <span class="text">Top 100</span>
        <i class="dropdown icon"></i>
        <div class="menu">

            @foreach($nav['top100'] as $label=>$link)
                <a class="item {{$segments === $link . '/' ? 'active' : '' }}" href="{{URL::to($link)}}">{{$label}}</a>
            @endforeach

        </div>
    </div>
    <div class="ui pointing dropdown link item">
        <span class="text">Top 100 Men</span>
        <i class="dropdown icon"></i>
        <div class="menu">

            @foreach($nav['top100m'] as $label=>$link)


                <a class="item {{$segments === $link . '/'  ? 'active' : '' }}" href="{{URL::to($link)}}">{{$label}}</a>
            @endforeach

        </div>
    </div>
    <div class="ui pointing dropdown link item">
        <span class="text">Top 100 Women</span>
        <i class="dropdown icon"></i>
        <div class="menu">

            @foreach($nav['top100w'] as $label=>$link)
                <a class="item {{$segments === $link . '/'  ? 'active' : '' }}" href="{{URL::to($link)}}">{{$label}}</a>
            @endforeach

        </div>
    </div>


</div>