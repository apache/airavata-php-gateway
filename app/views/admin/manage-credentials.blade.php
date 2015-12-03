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
                @if(Session::has("admin"))
                <table class="table">
                    <tr class="text-center table-condensed">
                        <td>
                            <button class="btn btn-default generate-ssh">Generate a new token</button>
                        </td>
                    </tr>
                </table>
                @endif
                <table class="table table-bordered table-condensed" style="word-wrap: break-word;">
                    <tr>
                        <th class="text-center">
                            Token
                        </th>
                        <th class="text-center">Public Key</th>
                    </tr>
                    @foreach( $tokens as $user => $token)
                    <tr>
                        <td class="">
                            <button class="btn btn-default">Copy Key</button>
                        </td>
                        <td class="public-key">
                            {{ $token }}
                        </td>
                    </tr>
                    @endforeach
                </table>
                

                <!--
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
                -->

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
   $(".generate-ssh").click( function(){
        $.ajax({
          type: "POST",
          url: "{{URL::to('/')}}/create-ssh-token"
        }).success( function( data){
            
        }).fail( function( data){
            failureObject = $.parseJSON( data.responseText);
            $(".generate-ssh").after("<div class='alert alert-danger'>" + failureObject.error.message + "</div>");
        });
   });
</script>
@stop