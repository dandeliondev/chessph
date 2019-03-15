@extends('layouts.app')
@section('title', 'NCFP Rating')

@section('content')

    <div class="ui secondary pointing menu">
        <a class="item active">
            Home
        </a>
        <div class="ui pointing dropdown link item">
            <span class="text">Top 100</span>
            <i class="dropdown icon"></i>
            <div class="menu">

                @foreach($nav['top100'] as $label=>$link)
                    <a class="item" href="{{$link}}">{{$label}}</a>
                @endforeach

            </div>
        </div>
        <div class="ui pointing dropdown link item">
            <span class="text">Top 100 Men</span>
            <i class="dropdown icon"></i>
            <div class="menu">

                @foreach($nav['top100m'] as $label=>$link)
                    <a class="item" href="{{$link}}">{{$label}}</a>
                @endforeach

            </div>
        </div>
        <div class="ui pointing dropdown link item">
            <span class="text">Top 100 Women</span>
            <i class="dropdown icon"></i>
            <div class="menu">

                @foreach($nav['top100w'] as $label=>$link)
                    <a class="item" href="{{$link}}">{{$label}}</a>
                @endforeach

            </div>
        </div>


    </div>

    @include('partials.search_filter')


    <table class="ui celled table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Job</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td data-label="Name">James</td>
            <td data-label="Age">24</td>
            <td data-label="Job">Engineer</td>
        </tr>
        <tr>
            <td data-label="Name">Jill</td>
            <td data-label="Age">26</td>
            <td data-label="Job">Engineer</td>
        </tr>
        <tr>
            <td data-label="Name">Elyse</td>
            <td data-label="Age">24</td>
            <td data-label="Job">Designer</td>
        </tr>
        </tbody>
    </table>




@endsection
@section('footer-assets')
    <script>

        $(document).ready(function () {
            $('.ui.dropdown')
                .dropdown();


            $('#customSettings').on('click', function () {
                $('#frmSettings').slideToggle(100);
            });


        });

    </script>
@endsection