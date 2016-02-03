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
                <div class="loading-img text-center hide">
                   <img src="../../assets/ajax-loader.gif"/>
                </div>
                @endif
                <table class="table table-bordered table-condensed" style="word-wrap: break-word;">
                    <tr>
                        <th class="text-center">
                            Token
                        </th>
                        <th class="text-center">Public Key</th>
                        @if( Session::has("admin"))
                        <th>Delete</th>
                        @endif
                    </tr>
                    <tbody class="token-values">
                    @foreach( $tokens as $token => $publicKey)
                    <tr>
                        <td class="">
                            {{ $token }}
                        </td>
                        <td class="public-key">
                            {{ $publicKey }}
                        </td>
                        @if( Session::has("admin"))
                        <td>
                            <span data-token="{{$token}}" class="glyphicon glyphicon-trash remove-token"></span>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                    </tbody>
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


<!-- Remove a Compute Resource from a Gateway -->
<div class="modal fade" id="remove-token-block" tabindex="-1" role="dialog" aria-labelledby="add-modal"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-center">Remove SSH Key Confirmation</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control remove-crId" name="rem-crId"/>
                <input type="hidden" class="form-control cr-gpId" name="gpId"/>

                Are you sure, you want to remove the SSH Key?<span class="remove-token-name"> </span>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <input type="submit" class="btn btn-danger" value="Remove"/>
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"/>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
@parent
<script>
   $(".generate-ssh").click( function(){
        $(".loading-img").removeClass("hide");
        $.ajax({
          type: "POST",
          url: "{{URL::to('/')}}/admin/create-ssh-token"
        }).success( function( data){

            var tokenJson = data;

            //$(".token-values").html("");
            $(".generate-ssh").after("<div class='alert alert-success new-token-msg'>New Token has been generated.</div>");

            $(".token-values").prepend("<tr class='alert alert-success'><td>" + tokenJson.token + "</td><td class='public-key'>" + tokenJson.pubkey + "</td>" + "<td><a href=''><span data-token='"+tokenJson.token+"' class='glyphicon glyphicon-trash remove-token'></span></a></td></<tr>");
            $(".loading-img").addClass("hide");
            
            setInterval( function(){
                $(".new-token-msg").fadeOut();
            }, 3000);
        }).fail( function( data){
        $(".loading-img").addClass("hide");

            failureObject = $.parseJSON( data.responseText);
            $(".generate-ssh").after("<div class='alert alert-danger'>" + failureObject.error.message + "</div>");
        });
   });

   $(".remove-token").click( function(){
        var removeSpan = $(this);
        var tr = removeSpan.parent().parent();
        var tokenToRemove = removeSpan.data("token");
        var publicKey = tr.children(".public-key").html();
        tr.children(".public-key").html("<div class='alert alert-danger'>Do you really want to remove the token? This action cannot be undone.<br/>" +
                                                                    "<span class='btn-group'>"+
                                                                    "<input type='button' class='btn btn-default remove-token-confirmation' value='Yes'/>" +
                                                                    "<input type='button' class='btn btn-default remove-token-cancel' value='Cancel'/>"+
                                                                    "</span></div>");

        
        tr.find( ".remove-token-confirmation").click( function(){
            $(".loading-img").removeClass("hide");
            $.ajax({
              type: "POST",
              data:{ "token" : tokenToRemove},
              url: "{{URL::to('/')}}/admin/remove-ssh-token"
              }).success( function( data){
                if( data.responseText == 1)
                    tr.addClass("alert").addClass("alert-danger");
                        tr.fadeOut(1000);
            }).fail( function( data){
                tr.after("<tr class='alert alert-danger'><td></td><td>Error occurred : " + $.parseJSON( data.responseText).error.message + "</td><td></td></tr>");
            }).complete( function(){
                $(".loading-img").addClass("hide");

            });
        });
        tr.find( ".remove-token-cancel").click( function(){
            tr.children(".public-key").html( publicKey);
        });
        
   });
</script>
@stop