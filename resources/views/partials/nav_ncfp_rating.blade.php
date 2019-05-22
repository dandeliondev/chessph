<div class="ui menu adjust_text20" id="main_nav">
    <a class="item {{$segments ===  ''  ? 'active' : '' }}" href="{{URL::to('')}}">
        Home
    </a>
    <a class="item {{$segments ===  'ncfp/rating/'  ? 'active' : '' }}" href="{{URL::to('ncfp/rating')}}">
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