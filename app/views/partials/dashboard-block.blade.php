<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li
        @if( Session::has("admin-nav") && Session::get("admin-nav") == "gateway-prefs") class="active" @endif>
        <a class="dashboard-link" href="{{ URL::to('/')}}/admin/dashboard/gateway"><i class="fa fa-fw fa-dashboard"></i>@if(
            Session::has("scigap_admin"))Gateways @else Gateway Preferences @endif</a>
        </li>
        <li
        @if( Session::has("admin-nav") && Session::get("admin-nav") == "manage-users") class="active" @endif>
            <a class="dashboard-link" href="{{ URL::to('/')}}/admin/dashboard/users"><i class="fa fa-fw fa-bar-chart-o"></i> Users</a>
        </li>
        <li
         @if( Session::has("admin-nav") && Session::get("admin-nav") == "manage-roles") class="active" @endif>
            <a class="dashboard-link" href="{{ URL::to('/')}}/admin/dashboard/roles"><i class="fa fa-fw fa-table"></i>Roles</a>
        </li>
<!--        <li-->
<!--        @if( Session::has("admin-nav") && Session::get("admin-nav") == "credential-store") class="active" @endif>-->
<!--            <a class="dashboard-link" href="{{ URL::to('/')}}/admin/dashboard/credential-store"><i class="fa fa-fw fa-table"></i>Credential-->
<!--                Store</a>-->
<!--        </li>-->
        <li>
            <a><i class="fa fa-fw fa-table"></i>Compute Resources</a>
            <ul>
                @if(Session::has("admin"))
                <li
                @if( Session::has("admin-nav") && Session::get("admin-nav") == "cr-create") class="active" @endif>
                    <a class="dashboard-link" href="{{ URL::to('/')}}/cr/create"><i class="fa fa-fw fa-table"></i>Register</a>
                </li>
                @endif
                @if(Session::has("admin") || Session::has("admin-read-only"))
                <li
                @if( Session::has("admin-nav") && Session::get("admin-nav") == "cr-browse") class="active" @endif>
                    <a class="dashboard-link" href="{{ URL::to('/')}}/cr/browse"><i class="fa fa-fw fa-table"></i>Browse</a>
                </li>
                @endif
            </ul>

        </li>
        <li>
            <a><i class="fa fa-fw fa-table"></i>App Catalog</a>
            <ul>
                @if(Session::has("admin") || Session::has("admin-read-only"))
                <li
                @if( Session::has("admin-nav") && Session::get("admin-nav") == "app-module") class="active" @endif>
                    <a class="dashboard-link" href="{{ URL::to('/')}}/app/module"><i class="fa fa-fw fa-table"></i>Module</a>
                </li>
                <li
                @if( Session::has("admin-nav") && Session::get("admin-nav") == "app-interface") class="active" @endif>
                    <a class="dashboard-link" href="{{ URL::to('/')}}/app/interface"><i class="fa fa-fw fa-table"></i>Interface</a>
                </li>
                <li
                @if( Session::has("admin-nav") && Session::get("admin-nav") == "app-deployment") class="active" @endif>
                    <a class="dashboard-link" href="{{ URL::to('/')}}/app/deployment"><i class="fa fa-fw fa-table"></i>Deployment</a>
                </li>
                @endif
            </ul>

        </li>
        <li
            @if( Session::has("admin-nav") && Session::get("admin-nav") == "exp-statistics") class="active" @endif>
            <a class="dashboard-link"  href="{{ URL::to('/')}}/admin/dashboard/experiments"><i
                    class="fa fa-fw fa-experiments"></i>Experiment Statistics</a>
        </li>
<!--        <li>-->
<!--            <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Settings</a>-->
<!--        </li>-->
        <!--
        <li>
            <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>
        </li>
        <li>
            <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
        </li>
        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>
            <ul id="demo" class="collapse">
                <li>
                    <a href="#">Dropdown Item</a>
                </li>
                <li>
                    <a href="#">Dropdown Item</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
        </li>
        <li>
            <a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
        </li>
        -->
    </ul>
</div>
