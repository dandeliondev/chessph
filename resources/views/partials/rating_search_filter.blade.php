<form class="adjust_text20" method="get" action="/ncfp/rating">
    <div class="row">
        <div class="five wide column">
            <div class="ui action input">
                <input type="text" name="search" value="{{$qs['search']}}" placeholder="Search name or ncfp id">
                <div class="ui right pointing dropdown icon button" id="customSettings">
                    <i class="settings icon"></i>
                </div>
                <button class="ui button" type="submit">Search</button>
            </div>
        </div>
    </div>
    <br/>
    <div class="ui form" id="frmSettings">
        <div class="inline fields">
            <label for="sort_by" class="green_label">Sort By</label>
            <div class="field">
                <div class="ui radio checkbox sortby">
                    <input type="radio" name="sort_by" value="lastname" {{$qs['sort_by'] == 'lastname' ? 'checked' :''}}>
                    <label>Name</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox sortby">
                    <input type="radio" value="standard" name="sort_by" {{$qs['sort_by'] == 'standard' ? 'checked' :''}}>
                    <label>Rating Standard</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox sortby">
                    <input type="radio" value="rapid" name="sort_by" {{$qs['sort_by'] == 'rapid' ? 'checked' :''}}>
                    <label>Rating Rapid</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox sortby">
                    <input type="radio" name="sort_by" value="blitz"{{$qs['sort_by'] == 'blitz' ? 'checked' :''}}>
                    <label>Rating Blitz</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox sortby">
                    <input type="radio" name="sort_by" value="f960"{{$qs['sort_by'] == 'f960' ? 'checked' :''}}>
                    <label>Fischer 960</label>
                </div>
            </div>

        </div>


        <div class="inline fields">
            <label for="order" class="green_label">Order</label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="order" value="asc" {{$qs['order'] == 'asc' ? 'checked' :''}}>
                    <label>Ascending</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="order" value="desc" {{$qs['order'] == 'desc' ? 'checked' :''}}>
                    <label>Descending</label>
                </div>
            </div>
        </div>
        <div class="inline fields">
            <label for="age" class="green_label">Age</label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="age_option" value="any" {{$qs['age_option'] == 'any' ? 'checked' :''}}>
                    <label>Any</label>
                </div>
            </div>
            &nbsp;<label for="age_option" class="green_label">Or</label>&nbsp;&nbsp;
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="age_option" value="range" {{$qs['age_option'] == 'range' ? 'checked' :''}}>
                    <label>Range</label>
                </div>
            </div>
            <div class="ui input">
                <input name="age_from" type="number" value="{{$qs['age_from']}}" min="0" style="max-width: 90px">
            </div>
            &nbsp;to&nbsp;
            <div class="ui input">
                <input name="age_to" type="number" value="{{$qs['age_to']}}" min="0" max="200" style="max-width: 90px">
            </div>
            &nbsp;<label for="age_basis" class="green_label">Based on</label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="age_basis" value="birthyear" {{$qs['age_basis'] == 'birthyear' ? 'checked' :''}}>
                    <label>Birthyear</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="age_basis" value="birthdate" {{$qs['age_basis'] == 'birthdate' ? 'checked' :''}}>
                    <label>Birthdate</label>
                </div>
            </div>
        </div>
        <div class="inline fields">
            <label for="gender" class="green_label">Gender</label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="gender" value="all" {{$qs['gender'] == 'all' ? 'checked' :''}}>
                    <label>All</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="gender" value="m" {{$qs['gender'] == 'm' ? 'checked' :''}}>
                    <label>Male</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="gender" value="f" {{$qs['gender'] == 'f' ? 'checked' :''}}>
                    <label>Female</label>
                </div>
            </div>
        </div>
        <div class="inline fields">
            <label for="title" class="green_label">Title</label>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="un" {{in_array('un',$qs['title']) ? 'checked' :''}}>
                    <label>Untitled</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="agm" {{in_array('agm',$qs['title']) ? 'checked' :''}}>
                    <label>AGM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="nm" {{in_array('nm',$qs['title']) ? 'checked' :''}}>
                    <label>NM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="cm" {{in_array('cm',$qs['title']) ? 'checked' :''}}>
                    <label>CM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="fm" {{in_array('fm',$qs['title']) ? 'checked' :''}}>
                    <label>FM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="im" {{in_array('im',$qs['title']) ? 'checked' :''}}>
                    <label>IM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="gm" {{in_array('gm',$qs['title']) ? 'checked' :''}}>
                    <label>GM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="wcm" {{in_array('wcm',$qs['title']) ? 'checked' :''}}>
                    <label>WCM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="wfm" {{in_array('wfm',$qs['title']) ? 'checked' :''}}>
                    <label>WFM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="wim" {{in_array('wim',$qs['title']) ? 'checked' :''}}>
                    <label>WIM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title[]" value="wgm" {{in_array('wgm',$qs['title']) ? 'checked' :''}}>
                    <label>WGM</label>
                </div>
            </div>
        </div>

    </div>
</form>