<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li
        @if( Session::has("manage")) class="active" @endif>
        <a href="{{ URL::to('/')}}/admin/dashboard/gateway"><i class="fa fa-fw fa-dashboard"></i>@if(
            Session::has("scigap_admin"))Gateways @else Gateway @endif</a>
        </li>
        <li>
            <a href="{{ URL::to('/')}}/admin/dashboard/users"><i class="fa fa-fw fa-bar-chart-o"></i> Users</a>
        </li>
        <li>
            <a href="{{ URL::to('/')}}/admin/dashboard/roles"><i class="fa fa-fw fa-table"></i>Roles</a>
        </li>
        <li>
            <a href="{{ URL::to('/')}}/admin/dashboard/credential-store"><i class="fa fa-fw fa-table"></i>Credential
                Store</a>
        </li>
        <li>
            <a href="{{ URL::to('/')}}/admin/dashboard/resources"><i class="fa fa-fw fa-table"></i>Resources</a>
        </li>
        <li>
            <a href="{{ URL::to('/')}}/admin/dashboard/experiments"><i
                    class="fa fa-fw fa-experiments"></i>Experiments</a>
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
