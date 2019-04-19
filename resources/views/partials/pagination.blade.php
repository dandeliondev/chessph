<?php
// config
$link_limit       = 4; // maximum number of links (a little bit inaccurate, but will be ok for now)
$start_last_pages = $paginator->lastPage() - $link_limit;
$paginator->appends($_GET);
$current_page = (int) $paginator->currentPage();
$next_page = $current_page + 1;
$prev_page = $current_page - 1;
$inputs = Request::input();
unset($inputs['page']);
$inputs = count($inputs) ?  '&'.http_build_query($inputs) : '';

?>

@if ($paginator->lastPage() > 1)
    <div class="ui pagination menu adjust_text20" id="main_pagination">
        <a class="item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}" href="{{URL::to('/ncfp/rating?page=1' . $inputs)}}">
            <<
        </a>
        @if($paginator->currentPage() > 1)
            <a class="item" href="{{ URL::to('/ncfp/rating?page='.$prev_page. $inputs) }}">
                <
            </a>
        @endif
        @if($paginator->currentPage() >= $start_last_pages)

            @for ($i = 1; $i <= $link_limit+1; $i++)
                @if($i <= $paginator->lastPage())
                    <a class="item {{ ($current_page == $i) ? ' active' : '' }}" href="{{URL::to('/ncfp/rating?page='.$i. $inputs)}}">
                        {{ $i }}
                    </a>
                @endif
            @endfor
            <a class="item disabled">
                ...
            </a>
        @endif

        @for ($i = $paginator->currentPage(); $i <= $paginator->currentPage() + $link_limit; $i++)
            @if($i <= $paginator->lastPage() && $paginator->lastPage() > 5)
                <a class="item {{ ($current_page == $i) ? ' active' : '' }}" href="{{URL::to('/ncfp/rating?page='.$i. $inputs)}}">
                    {{ $i }}
                </a>
            @endif
        @endfor

        @if($paginator->currentPage() <  $start_last_pages)
            <a class="item disabled">
                ...
            </a>
            @for ($i = $start_last_pages; $i <= $paginator->lastPage(); $i++)


                <a class="item {{ ($current_page == $i) ? ' active' : '' }}" href="{{URL::to('/ncfp/rating?page='.$i. $inputs)}}">
                    {{ $i }}
                </a>

            @endfor

        @endif
        @if($paginator->currentPage() != $paginator->lastPage())
            <a class="item" href="{{ URL::to('/ncfp/rating?page='.$next_page. $inputs) }}">
               >
            </a>
        @endif
        <a class="item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}" href="{{ URL::to('/ncfp/rating?page='.$paginator->lastPage() . $inputs) }}">
            >>
        </a>


    </div>
@endif
