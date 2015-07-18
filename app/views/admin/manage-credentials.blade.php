@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/admin.css')}}
@stop

@section('content')

<div id="wrapper">
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    @include( 'partials/dashboard-block')
    <div id="page-wrapper">

        <div class="container-fluid">
            <div class="col-md-12">
                @if( Session::has("message"))
                <div class="row">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                        {{ Session::get("message") }}
                    </div>
                </div>
                {{ Session::forget("message") }}
                @endif

                <h1 class="text-center">SSH Keys</h1>

                <table class="table table-bordered table-condensed">
                    <tr>
                        <th class="text-center">
                            Token
                        </th>
                        <th class="text-center">Public Key</th>
                    </tr>
                    @foreach( $tokens as $token)
                    <tr>
                        <td class="role-name">{{ $token }}</td>
                        <td>
                            {{ $public-key }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>Some token</td>
                        <td>$ cat ~/.ssh/id_rsa.pub
                            ssh-rsa AAAAB3NzaC1yc2EAAAABIwAAAQEAklOUpkDHrfHY17SbrmTIpNLTGK9Tjom/BWDSU
                            GPl+nafzlHDTYW7hdI4yZ5ew18JH4JW9jbhUFrviQzM7xlELEVf4h9lFX5QVkbPppSwg0cda3
                            Pbv7kOdJ/MTyBlWXFCR+HAo3FXRitBqxiX1nKhXpHAZsMciLq8V6RjsNAQwdsdMFvSlVK/7XA
                            t3FaoJoAsncM1Q9x5+3V0Ww68/eIFmb1zuUFljQJKprrX88XypNDvjYNby6vw/Pb0rwert/En
                            mZ+AW4OZPnTPI89ZPmVMLuayrD2cE86Z/il8b+gw3r3+1nKatmIkjn2so1d01QraTlMqVSsbx
                            NrRFi9wrf+M7Q== schacon@mylaptop.local
                        </td>
                    </tr>
                </table>
                @if(Session::has("admin"))
                <table class="table">
                    <tr class="text-center table-condensed">
                        <td>
                            <button class="btn btn-default">Generate a new token</button>
                        </td>
                    </tr>
                </table>
                @endif

                @if(Session::has("admin"))
                <div class="row">
                    <h1 class="text-center">My Proxy Credentials</h1>

                    <div class="col-md-offset-3 col-md-6">
                        <table class="table table-striped table-condensed">
                            <tr>
                                <td>My Proxy Server</td>
                                <td><input type="text" class="form-control" placeholder="" value=""/></td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td><input type="text" class="form-control" placeholder="" value=""/></td>
                            </tr>
                            <tr>
                                <td>Passphrase</td>
                                <td><input type="text" class="form-control" placeholder="" value=""/></td>
                            </tr>
                        </table>
                        <table class="table">
                            <tr class="text-center table-condensed">
                                <td>
                                    <button class="btn btn-default">Submit</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif

                <h1 class="text-center">Amazon Credentials</h1>

                <table class="table table-striped table-condensed">
                    <tr class="text-center">
                        <td>Under Development</td>
                    </tr>
                </table>

                <h1 class="text-center">OAuth MyProxy</h1>

                <table class="table table-striped table-condensed">
                    <tr class="text-center">
                        <td>Under Development</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-role-block" tabindex="-1" role="dialog" aria-labelledby="add-modal"
     aria-hidden="true">
    <div class="modal-dialog">

        <form action="{{URL::to('/')}}/admin/deleterole" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">Delete Role Confirmation</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control delete-roleName" name="role"/>
                    Do you really want to delete the role - <span class="delete-role-name"></span>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" class="btn btn-danger" value="Delete"/>
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"/>
                    </div>
                </div>
            </div>

        </form>


    </div>
</div>


@stop

@section('scripts')
@parent
<script>
    $(".toggle-add-role").click(function () {
        $(".add-role").slideDown();
    });

    $(".edit-role-name").click(function () {
        var roleNameSpace = $(this).parent().parent().find(".role-name");
        if (roleNameSpace.find(".edit-role-form").length) {
            roleNameSpace.html(roleNameSpace.find(".original-role-name").val());
        }
        else {
            var role = roleNameSpace.html();
            roleNameSpace.html($(".edit-role").html());
            roleNameSpace.find(".original-role-name").val(role);
            roleNameSpace.find(".new-role-name").val(role);
        }
    });

    $(".delete-role").click(function () {
        $("#delete-role-block").modal("show");
        var roleName = $(this).parent().parent().find(".role-name").html();
        $(".delete-role-name").html(roleName);
        $(".delete-roleName").val(roleName);
    })
</script>
@stop