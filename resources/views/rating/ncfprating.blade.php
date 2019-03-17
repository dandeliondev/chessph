@extends('layouts.app')
@section('title', $title)
@section('meta_description', $meta_description)
@section('meta_keywords',  $meta_keywords)

@section('content')


    @include('partials.nav_ncfp_rating')

    <h2 class="ui header">
        {{$header}}
        <div class="sub header">{{$subheader}}</div>
    </h2>
    @if($filter == true)
        @include('partials.rating_search_filter')
    @endif


    <table class="ui celled table">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Rating Standard</th>
            <th>Rating Rapid</th>
            <th>Rating Blitz</th>
            <th>Title</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Birthdate</th>
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

            @endphp
            <tr>
                <td data-label="num">{{$ctr}}</td>
                <td data-label="Name"><span style="font-weight: bold">{{ucwords(strtolower($row->lastname))}}</span>, {{ucwords(strtolower($row->firstname))}}</td>
                <td data-label="Rating Standard">{{$row->standard}}</td>
                <td data-label="Rating Rapid">{{$row->rapid}}</td>
                <td data-label="Rating Blitz">{{$row->blitz}}</td>
                <td data-label="Title">{{strtolower($row->title)}}</td>
                <td data-label="Gender">{{strtolower($row->gender)}}</td>
                <td data-label="Age">{{$row->age}}</td>
                <td data-label="Birthdate">{{$row->birthdate}}</td>
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
    <div class="ui warning message">
        <i class="close icon"></i>
        <div class="header">
            Disclaimer
        </div>
        This is unofficial NCFP rating list, and should not be used as an official reference, you may find the NCFP official rating list on their official <a href="https://www.facebook.com/ncfpsecretariat2012/"
                                                                                                                                                              target="_blank">facebook page</a>
    </div>



@endsection
@section('footer-assets')
    <script>

        $(document).ready(function () {
            $('.ui.dropdown')
                .dropdown();

            $('.sortby')
                .on('click', function() {
                   // $('.callback .checkbox').checkbox( $(this).data('method') );
                    var val = $('input[name=sort_by]:checked').val();
                    if(val != 'name'){
                        $("input[name=order][value=desc]").attr('checked', 'checked');

                    }else{
                        $("input[name=order][value=asc]").attr('checked', 'checked');
                    }
                })
            ;



            $('#customSettings').on('click', function () {
                $('#frmSettings').slideToggle(100);
            });


        });

    </script>
@endsection