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

                    <div class="row">

                        <div class="col-md-6">
                            <h3>Existing Gateway Resource Profiles :</h3>
                        </div>
                        <div class="col-md-6" style="margin-top:3.5%">
                            <input type="text" class="col-md-12 filterinput" placeholder="Search by Gateway Name" />
                        </div>
                    </div>
                    <div class="panel-group" id="accordion2">
                    @foreach( $gateways as $indexGP => $gp )
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed gateway-name" data-toggle="collapse" data-parent="#accordion2" href="#collapse-gateway-{{$indexGP}}">
                                    {{ $gp->gatewayName }}
                                    </a>
                                    <div class="pull-right col-md-2 gateway-options fade">
                                        <span class="glyphicon glyphicon-pencil edit-gateway" style="cursor:pointer;" data-toggle="modal" data-target="#edit-gateway-block" data-gp-id="{{ $gp->gatewayId }}" data-gp-name="{{ $gp->gatewayName }}"></span>
                                        <span class="glyphicon glyphicon-trash delete-gateway" style="cursor:pointer;" data-toggle="modal" data-target="#delete-gateway-block" data-gp-name="{{$gp->gatewayName}}" data-gp-id="{{ $gp->gatewayId }}"></span>
                                    </div>
                                </h4>
                            </div>
                            <div id="collapse-gateway-{{$indexGP}}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="app-interface-block">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h4><span class="glyphicon glyphicon-plus"></span> Add a user as Admin to this Gateway</h4>
                                                <form action="{{URL::to('/')}}/admin/addgatewayadmin" method="POST" role="form" enctype="multipart/form-data">
                                                    <div class="form-group required">
                                                        <label for="experiment-name" class="control-label">Enter Username</label>
                                                        <input type="text" class="form-control" name="username" id="experiment-name" placeholder="username" autofocus required="required">
                                                        <input type="hidden" name="gateway_name" value="{{ $gp->gatewayName }}"/>
                                                    </div>
                                                    <div class="btn-toolbar">
                                                        <input name="add" type="submit" class="btn btn-primary" value="Add Admin"/>
                                                    </div>   
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-default toggle-add-tenant"><span class="glyphicon glyphicon-plus"></span>Add a new gateway</button>
                </div>
                <div class="add-tenant col-md-6">
                    <div class="form-group">
                        <label>Enter Domain Name</label>
                        <input type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Enter Admin Username</label>
                        <input type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Enter Admin Password</label>
                        <input type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Re-enter Admin Password</label>
                        <input type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="form-control btn btn-primary" value="Register" />
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>

@stop


@section('scripts')
    @parent
    <script>
        $(".add-tenant").slideUp();
        
        $(".toggle-add-tenant").click( function(){
            $(".add-tenant").slideDown();
        });
    </script>
@stop