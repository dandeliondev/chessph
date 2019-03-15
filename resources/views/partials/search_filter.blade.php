<form method="get" action="">
    <div class="row">
        <div class="five wide column">
            <div class="ui action input">
                <input type="text" placeholder="Search...">
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
                <div class="ui radio checkbox">
                    <input type="radio" name="sort_by" checked="" tabindex="0" class="hidden">
                    <label>Name</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="sort_by" tabindex="0" class="hidden">
                    <label>Rating Standard</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="sort_by" tabindex="0" class="hidden">
                    <label>Rating Rapid</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="sort_by" tabindex="0" class="hidden">
                    <label>Rating Blitz</label>
                </div>
            </div>

        </div>


        <div class="inline fields">
            <label for="order" class="green_label">Order</label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="order" checked="" tabindex="0" class="hidden">
                    <label>Ascending</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="order" tabindex="0" class="hidden">
                    <label>Descending</label>
                </div>
            </div>
        </div>
        <div class="inline fields">
            <label for="age" class="green_label">Age</label>
            <div class="ui input">
                <input name="age_from" type="number" value="0" min="0" style="max-width: 90px">
            </div>
            &nbsp;to&nbsp;
            <div class="ui input">
                <input name="age_to" type="number" value="100" min="0" max="200" style="max-width: 90px">
            </div>
            &nbsp;<label for="age_basis" class="green_label">Based on</label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="age_basis" checked="" tabindex="0" class="hidden">
                    <label>Birthyear</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="age_basis" tabindex="0" class="hidden">
                    <label>Birthdate</label>
                </div>
            </div>
        </div>
        <div class="inline fields">
            <label for="gender" class="green_label">Gender</label>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="gender" checked="" tabindex="0" class="hidden">
                    <label>All</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="gender" tabindex="0" class="hidden">
                    <label>Male</label>
                </div>
            </div>
            <div class="field">
                <div class="ui radio checkbox">
                    <input type="radio" name="gender" tabindex="0" class="hidden">
                    <label>Female</label>
                </div>
            </div>
        </div>
        <div class="inline fields">
            <label for="title" class="green_label">Title</label>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="un">
                    <label>Untitled</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="nm">
                    <label>NM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="cm">
                    <label>CM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="fm">
                    <label>FM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="im">
                    <label>IM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="gm">
                    <label>GM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="wfm">
                    <label>WFM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="wcm">
                    <label>WCM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="wim">
                    <label>WIM</label>
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="title" value="wgm">
                    <label>WGM</label>
                </div>
            </div>
        </div>

    </div>
</form>