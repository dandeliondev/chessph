@extends('layouts.app')
@section('title', $title)
@section('meta_description', $meta_description)
@section('meta_keywords',  $meta_keywords)

@section('content')
    @include('partials.nav_ncfp_rating')


    <h2 class="ui header adjust_text24">
        {{$header}}
        <div class="sub header adjust_text20">{{$subheader}}</div>
    </h2>
    @if($filter == true)
        @include('partials.rating_search_filter')
    @endif


    <table class="ui celled table adjust_text20" id="table_rating">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Rating Standard</th>
            <th>Rating Rapid</th>
            <th>Rating Blitz</th>
            <th>Gender</th>
            <th>Age</th>
            <th>NCFP ID</th>
            <th>Fide ID</th>

        </tr>
        </thead>
        <tbody>
        @php
            $ctr = ($page - 1) * 100;
        @endphp
        @foreach($list as $row)
            @php

                $ctr++;
                $title ='';

                if(trim($row->title) != ''){
                    if(isset($title_colors[strtolower($row->title)]))
                        $color = $title_colors[strtolower($row->title)];
                    else
                        $color = '';

                    $title = '<div class="ui mini '.$color.' horizontal label player_title">'.strtoupper($row->title).'</div>';
                }
                $standard_diff_disp = '';
                $rapid_diff_disp = '';
                $blitz_diff_disp = '';

                $standard_diff = $row->standard - $row->standard_prev;
                $rapid_diff = $row->rapid - $row->rapid_prev;
                $blitz_diff = $row->blitz - $row->blitz_prev;

                if($standard_diff <> 0){
                    if($standard_diff > 0){
                        $standard_diff_disp = '<div class="ui horizontal label" title="increase from previous rating">(+'.$standard_diff.')</div>';
                    }else{
                        $standard_diff_disp = '<div class="ui horizontal label" title="decrease from previous rating">('.$standard_diff.')</div>';
                    }
                }

                if($rapid_diff <> 0){
                    if($rapid_diff > 0){
                        $rapid_diff_disp = '<div class="ui horizontal label" title="increase from previous rating">(+'.$rapid_diff.')</div>';
                    }else{
                        $rapid_diff_disp = '<div class="ui horizontal label" title="decrease from previous rating">('.$rapid_diff.')</div>';
                    }
                }

                if($blitz_diff <> 0){
                    if($blitz_diff > 0){
                        $blitz_diff_disp = '<div class="ui horizontal label" title="increase from previous rating">(+'.$blitz_diff.')</div>';
                    }else{
                        $blitz_diff_disp = '<div class="ui horizontal label" title="decrease from previous rating">('.$blitz_diff.')</div>';
                    }
                }



            @endphp
            <tr>
                <td data-label="num">{{$ctr}}</td>
                <td data-label="Name"><span
                            style="font-weight: bold">{!!$title !!} {{strtoupper($row->lastname)}}</span>, {{ucwords(strtolower($row->firstname))}}
                </td>
                <td data-label="Rating Standard">{{$row->standard}} {!!$standard_diff_disp!!}</td>
                <td data-label="Rating Rapid">{{$row->rapid}}</td>
                <td data-label="Rating Blitz">{{$row->blitz}}</td>
                <td data-label="Gender">{{strtolower($row->gender)}}</td>
                <td data-label="Age">{{$row->age}}</td>
                <td data-label="NCFP ID">{{$row->ncfp_id}}</td>
                <td data-label="FIDE ID">{{$row->fide_id}}</td>

            </tr>
        @endforeach

        </tbody>
    </table>
    @if($paginate)
        @include('partials.pagination', ['paginator' => $list])
    @endif

    <br/><br/>
    <div class="ui warning message adjust_text20" id="disclaimer">
        <i class="close icon"></i>
        <div class="header">
            Disclaimer
        </div>
        This website is not affiliated with NCFP, the ratings listed here should not be used as an official reference,
        you may find the NCFP
        official rating list on their <a href="https://www.facebook.com/groups/569867936728217/"
                                         target="_blank">facebook page</a>
    </div>



@endsection
@section('footer-assets')
    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "WebSite",
        "url": "{{ URL::to('/') }}/",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ URL::to('/ncfp/rating?search=') }}{search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }








    </script>
    <script>

        $(document).ready(function () {
            $('.ui.dropdown')
                .dropdown();

            screen_adjust();

            $('.sortby')
                .on('click', function () {
                    // $('.callback .checkbox').checkbox( $(this).data('method') );
                    var val = $('input[name=sort_by]:checked').val();
                    if (val != 'name') {
                        $("input[name=order][value=desc]").attr('checked', 'checked');

                    } else {
                        $("input[name=order][value=asc]").attr('checked', 'checked');
                    }
                })
            ;


            $('#customSettings').on('click touchstart', function () {
                $('#frmSettings').slideToggle(100);
            });

            $(window).resize(function () {
                screen_adjust();

            });

        });

        function screen_adjust() {
            if ($(window).width() < 450) {
                $('.player_title').removeClass('mini');
                $('.player_title').addClass('large');

                $('.social_icons').removeClass('large');
                $('.social_icons').addClass('huge');
            }
            else {
                $('.player_title').removeClass('large');
                $('.player_title').addClass('mini');

                $('.social_icons').removeClass('huge');
                $('.social_icons').addClass('large');

            }
        }

    </script>
@endsection