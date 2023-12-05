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

    @foreach($lists as $list)
        @include('partials.rating_table',[$table_data = $list['list'],$table_header = $list['header'],$table_subheader = $list['subheader']])
    @endforeach

    <br/><br/>
    <div class="ui warning message adjust_text20" id="disclaimer">
        <i class="close icon"></i>
        <div class="header">
            Disclaimer
        </div>
        This website is not affiliated with NCFP, the ratings listed here should not be used as an official
        reference,
        you may find the NCFP
        official rating list on their <a href="https://www.facebook.com/groups/569867936728217/"
                                         target="_blank">facebook page</a> or <a href="https://ncfph.org"
                                                                                 target="_blank">ncfph.org</a> <br /> Credits is shared with sir <a href="https://www.facebook.com/groups/2273539726042561/user/100004879900128/"
        target="_blank">Edward Serrano</a> to whom without his dedication and hard work won't make this ratings compilation possible.
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
    <!-- Google tag (gtag.js) -->

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
