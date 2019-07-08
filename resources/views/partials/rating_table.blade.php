<h3 class="ui header">
    {{$table_header}}
</h3>


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
    @foreach($table_data as $row)
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
                    $standard_diff_disp = '<div class="ui horizontal label basic green mini" title="increase from previous rating"><i class="arrow up icon"></i>'.$standard_diff.'</div>';
                }else{
                    $standard_diff = $standard_diff * -1;
                    $standard_diff_disp = '<div class="ui horizontal label basic red mini" title="decrease from previous rating"><i class="arrow down icon"></i>'.$standard_diff.'</div>';
                }
            }

            if($rapid_diff <> 0){
                if($rapid_diff > 0){
                    $rapid_diff_disp = '<div class="ui horizontal label basic green mini" title="increase from previous rating"><i class="arrow up icon"></i>'.$rapid_diff.'</div>';
                }else{
                $rapid_diff = $rapid_diff * -1;
                    $rapid_diff_disp = '<div class="ui horizontal label basic red mini" title="decrease from previous rating"><i class="arrow down icon"></i>'.$rapid_diff.'</div>';
                }
            }

            if($blitz_diff <> 0){
                if($blitz_diff > 0){
                    $blitz_diff_disp = '<div class="ui horizontal label basic green mini" title="increase from previous rating"><i class="arrow up icon"></i>'.$blitz_diff.'</div>';
                }else{
                $blitz_diff = $blitz_diff * -1;
                    $blitz_diff_disp = '<div class="ui horizontal label basic red mini" title="decrease from previous rating"><i class="arrow down icon"></i>'.$blitz_diff.'</div>';
                }
            }



        @endphp
        <tr>
            <td data-label="num">{{$ctr}}</td>
            <td data-label="Name"><span
                        style="font-weight: bold">{!!$title !!} {{strtoupper($row->lastname)}}</span>, {{ucwords(strtolower($row->firstname))}}
            </td>
            <td data-label="Rating Standard">{{$row->standard}} {!!$standard_diff_disp!!}</td>
            <td data-label="Rating Rapid">{{$row->rapid}} {!!$rapid_diff_disp!!}</td>
            <td data-label="Rating Blitz">{{$row->blitz}} {!!$blitz_diff_disp!!}</td>
            <td data-label="Gender">{{strtolower($row->gender)}}</td>
            <td data-label="Age">{{$row->age}}</td>
            <td data-label="NCFP ID">{{$row->ncfp_id}}</td>
            <td data-label="FIDE ID">{{$row->fide_id}}</td>

        </tr>
    @endforeach

    </tbody>
</table>
@if($paginate)
    @include('partials.pagination', ['paginator' => $table_data])
@endif