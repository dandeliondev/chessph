<?php
// config
$link_limit       = 4; // maximum number of links (a little bit inaccurate, but will be ok for now)
$start_last_pages = $paginator->lastPage() - $link_limit;
$paginator->appends($_GET);
?>

@if ($paginator->lastPage() > 1)
    <div class="ui pagination menu adjust_text20" id="main_pagination">
        <a class="item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}" href="{{ $paginator->url(1) }}">
            <<
        </a>
        @if($paginator->currentPage() > 1)
            <a class="item" href="{{ $paginator->url($paginator->currentPage() - 1) }}">
                <
            </a>
        @endif
        @if($paginator->currentPage() >= $start_last_pages)

            @for ($i = 1; $i <= $link_limit+1; $i++)
                @if($i <= $paginator->lastPage())
                    <a class="item" href="{{ $paginator->url($i) }}">
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
                <a class="item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}" href="{{ $paginator->url($i) }}">
                    {{ $i }}
                </a>
            @endif
        @endfor

        @if($paginator->currentPage() <  $start_last_pages)
            <a class="item disabled">
                ...
            </a>
            @for ($i = $start_last_pages; $i <= $paginator->lastPage(); $i++)


                <a class="item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}" href="{{ $paginator->url($i) }}">
                    {{ $i }}
                </a>

            @endfor

        @endif
        @if($paginator->currentPage() != $paginator->lastPage())
            <a class="item" href="{{ $paginator->url($paginator->currentPage() + 1) }}">
                >
            </a>
        @endif
        <a class="item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}" href="{{ $paginator->url($paginator->lastPage()) }}">
            >>
        </a>


    </div>
@endif
