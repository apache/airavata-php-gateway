@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/admin.css')}}
@stop

@section('content')
<div class="container">
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

        @if( Session::has('new-gateway-provider') )
            <div style="margin-top:50px;" class="col-md-12">
            @if( Session::has("existing-gateway-provider") )
                <h3>List of Requested Gateways</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>Gateway Name</th>
                            <th>Gateway Request Status</th>
                            <th>Actions</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach( $requestedGateways as $gatewayId => $gateway)
                        <tr>
                            <td>{{ $gateway["gatewayInfo"]->gatewayName }}</td>
                            <td>{{ $gateway["approvalStatus"] }}</td>
                            <td>
                                @if( $gateway["approvalStatus"] == "APPROVED")
                                    <div class="btn-group" role="group" aria-label="...">
                                        <button type="button" class="btn btn-default view-credentials" data-gatewayobject="{{ htmlentities( json_encode( $gateway['gatewayInfo'])) }}">View Credentials</button>
                                        <!--
                                        <button type="button" class="btn btn-default"><a href="{{URL::to('/')}}/admin/dashboard?gatewayId={{$gatewayId}}">Manage Gateway</a></button>
                                        
                                        <button type="button" class="btn btn-danger deactivateGateway-button" data-toggle="modal" data-target="#deactivateGateway" data-gatewayid="{{$gatewayId}}">Deactivate Gateway</button>
                                        -->
                                    </div>
                                @elseif( $gateway["approvalStatus"] == "REQUESTED")
                                    <a href="{{URL::to('/')}}/admin/update-gateway-request?gateway_id={{$gatewayId}}&status=4">
                                        <button type="button" class="btn btn-danger">Cancel Request</button>
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{$gateway["gatewayInfo"]->declinedReason}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="well">
                <h4 class="text-center">Need faster or more customised solutions for your Gateway? Contact us at: <a href="mailto:contact@scigap.org">contact@scigap.org</a></h4>
                </div>
            @endif
            </div>
            <div class="col-md-12">
            <button class="gateway-request-button btn btn-default">Request a New Gateway</button>
                
            @if ($errors->has())
                @foreach ($errors->all() as $error)
                {{ CommonUtilities::print_error_message($error) }}
                @endforeach
            @endif
            <div class="row @if(! $errors->has())hide @endif gateway-request-form">
                <div class="col-md-offset-2 col-md-8">
                    <form id="add-tenant-form" action="{{ URL::to('/') }}/provider/request-gateway">
                        <div class="col-md-12 text-center" style="margin-top:20px;">
                            <h3>Request your gateway now!</h3>
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Gateway Name</label>
                            <input type="text" maxlength="50" name="gateway-name" class="form-control" required="required" value="{{Input::old('gateway-name') }}" />
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Gateway Acronym </label>
                            <input type="text" name="gateway-acronym" class="gateway-acronym form-control" required="required" value="{{Input::old('gateway-acronym') }}" maxlength="6" data-toggle="popover" data-placement="left" data-content="Acronym cannot contain digits or special characters."/>
                        </div>

                        <div class="form-group required">
                            <label class="control-label">Gateway URL</label>
                            <input type="text" name="gateway-url" id="gateway-url" class="form-control" value="{{Input::old('gateway-url') }}" data-container="body" data-toggle="popover" data-placement="left" data-content="URL to Portal home page or Download URL (for desktop applications) where gateway has been deployed."/>
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Gateway Admin Username</label>
                            <input type="text" name="admin-username" value="{{Input::old('admin-username')}}" class="form-control" required="required" />
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Gateway Admin Password</label>
                            <input type="password" id="password" name="admin-password" class="form-control" required="required" title="" type="password" data-container="body" data-toggle="popover" data-placement="left" data-content="Password needs to contain at least (a) One lower case letter (b) One Upper case letter and (c) One number (d) One of the following special characters - !@#$*"/>
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Admin Password Confirmation</label>
                            <input type="password" name="admin-password-confirm" class="form-control" required="required"/>
                        </div>

                        <div class="form-group required">
                            <label class="control-label">Admin First Name</label>
                            <input type="text" name="admin-firstname" class="form-control" required="required" value="{{Input::old('admin-firstname') }}"/>
                        </div>

                        <div class="form-group required">
                            <label class="control-label">Admin Last Name</label>
                            <input type="text" name="admin-lastname" class="form-control" required="required" value="{{Input::old('admin-lastname') }}"/>
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Gateway Contact Email</label>
                            <input type="text" name="email-address" class="form-control" required="required" value="{{Input::old('email-address') }}"/>
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Project Details</label>
                            <textarea type="text" name="project-details" id="project-details" class="form-control" required="required"  data-container="body" data-toggle="popover" data-placement="left" data-content="This information will help us to understand and identify your gateway requirements, such as local or remote resources, user management, field of science and communities supported, applications and interfaces, license handling, allocation management, data management, etc... It will help us in serving you and providing you with the best option for you and your research community.">{{Input::old('project-details') }}</textarea>
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Public Project Description</label>
                            <textarea type="text" name="public-project-description" id="public-project-description" class="form-control" required="required"  data-container="body" data-toggle="popover" data-placement="left" data-content="This description will be used to describe the gateway in the Science Gateways List. It help a user decide whether or not this gateway will be useful to them.">{{Input::old('public-project-description') }}</textarea>
                        </div>
                        <input type="submit" value="Send Request" class="btn btn-primary"/>
                        <input type="reset" value="Reset" class="btn">
                    </form>
                </div>
            </div>
            <hr/>
            </div>
        <!-- View Credentials -->
        <div class="modal fade" id="viewCredentials" tabindex="-1" role="dialog" aria-labelledby="vc">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Gateway Credentials</h4>
              </div>
              <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gateway Id</td>
                            <td class="gateway-id"></td>
                        </tr>
                        <tr>
                            <td>Admin Username</td>
                            <td class="admin-username"></td>
                        </tr>
                        <tr>
                            <td>Admin Password</td>
                            <td class="admin-password"></td>
                        </tr>
                        <tr>
                            <td>Oauth Client Key</td>
                            <td class="oauth-client-key"></td>
                        </tr>
                        <tr>
                            <td>Oauth Client Secret</td>
                            <td class="oauth-client-secret"></td>
                        </tr>
                    </tbody>
                </table>
              </div>
              <!--
              <div class="modal-footer">
              </div>
              -->
            </div>
          </div>
        </div>

        <!-- Deactivate Modal -->
        <div class="modal fade" id="deactivateGateway" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Deactivate Confirmation</h4>
              </div>
              <div class="modal-body">
                Are you sure, you want to deactivate this Gateway? This action cannot be undone.
              </div>
              <div class="modal-footer">
                <form action="{{URL::to('/')}}/admin/update-gateway-request?status=3" method="GET">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input type="hidden" id="deactivateGatewayId" name="gateway_id" value=""/>
                    <button type="submit" class="btn btn-danger">Deactivate</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        @elseif( Session::has('authorized-user') || Session::has('admin') || Session::has('admin-read-only') )
        <div class="row text-center breathing-space">
            <h1>Gateway: {{Session::get("gateway_id")}}</h1>
            <h3>Let's get started!</h3>
        </div>
        <div class="row text-center admin-options">

            <div class="row well">

                <h3>See what's happening in your projects</h3>

                <a href="{{URL::to('/')}}/project/browse">
                    <div class="@if( Session::has('admin') || Session::has('admin-read-only')) col-md-4 @else col-md-6 @endif well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-off console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Projects</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/experiment/browse">
                    <div class="@if( Session::has('admin') || Session::has('admin-read-only')) col-md-4 @else col-md-6 @endif well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-tasks console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Experiments</h4>
                        </div>
                    </div>
                </a>

                @if( Session::has('admin') || Session::has('admin-read-only'))
                <a href="{{URL::to('/')}}/admin/dashboard/experiments">
                    <div class="col-md-4  well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-stats console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Experiment Statistics</h4>
                        </div>
                    </div>
                </a>
                @endif
            </div>

            @if( Session::has('admin') || Session::has('admin-read-only') )

            <div class="row well">

                <h3>Manage Users Access</h3>
                <a href="{{URL::to('/')}}/admin/dashboard/users">
                    <div class="col-md-6  well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-user  console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Users</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/admin/dashboard/roles">
                    <div class=" col-md-6  well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-eye-open  console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse User Roles</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="row well">

                <h3>Manage Computing and Storage Resouces and Preferences for your Gateway</h3>

                <a href="{{URL::to('/')}}/cr/browse">
                    <div class=" col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-briefcase  console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Compute Resources</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/admin/dashboard/gateway">
                    <div class=" col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-sort console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Gateway Profile</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/sr/browse">
                    <div class=" col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-folder-open console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Storage Resources</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/admin/dashboard/credential-store">
                    <div class=" col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-lock console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Credential Store</h4>
                        </div>
                    </div>
                </a>

            </div>

            <div class="row well">

                <h3>Manage Application Modules, Interfaces and Deployments</h3>
                <a href="{{URL::to('/')}}/app/module">
                    <div class="col-md-4 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-th-large console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Application Modules</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/app/interface">
                    <div class="col-md-4 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-phone console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Application Interfaces</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/app/deployment">
                    <div class="col-md-4 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-random console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Application Deployments</h4>
                        </div>
                    </div>
                </a>
                @endif

                
                <!--
                <div class=" col-md-4">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-list-alt console-icon"></span>
                    </div>
                    <div class="col-md-12">
                        Reports
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-question-sign console-icon"></span>
                    </div>
                    <div class="col-md-12">
                        Support
                    </div>
                </div>
            </div>
            -->

        </div>
    </div>
    @else
    <div>
        <div class="row text-center breathing-space">
            <h1>Hi! You look new here.</h1>
        </div>
        <div class="row well">
            <h4>Your {{ Config::get('pga_config.portal')['portal-title'] }} account is pending approval. You will be notified via email upon approval by {{ Config::get('pga_config.portal')['portal-title'] }} Admin.</h4>
        </div>
    </div>
    @endif

    <!--
    Hidden until completed.
    <div class="col-md-12 text-center">
        <a href="{{URL::to('/')}}/allocation-request">
            <button class="btn btn-default ">Request an allocation</button>
        </a>
    </div>
    -->

</div>

@stop

@section('scripts')
@parent
<script>

    $(".add-tenant").slideUp();

    $(".toggle-add-tenant").click(function () {
        $('html, body').animate({
            scrollTop: $(".toggle-add-tenant").offset().top
        }, 500);
        $(".add-tenant").slideDown();
    });

    $(".gateway-request-button").click( function(){
        $(".gateway-request-form").removeClass("hide");
    });

    $("#password").popover({
        'trigger':'focus'
    });

    $("#domain").popover({
        'trigger':'focus'
    });

    $("#gateway-url").popover({
        'trigger':'focus'
    });

    $(".gateway-acronym").popover({
        'trigger':'focus'
    });

    $("#project-details").popover({
        'trigger':'focus'
    });

    $("#public-project-description").popover({
        'trigger':'focus'
    });

    $("#add-tenant-form").on("submit", function(e){
        e.preventDefault();
        console.log( !/[^a-z]/i.test( $(".gateway-acronym").val()));
    });

    $(".deactivateGateway-button").click( function(){
        var gatewayId = $(this).data("gatewayid");
        $("#deactivateGatewayId").val( gatewayId);
    });

    $(".view-credentials").click( function(){
        var gatewayObject = $(this).data("gatewayobject");
        $(".admin-username").html( gatewayObject["identityServerUserName"]);
        $(".admin-password").html( gatewayObject["identityServerPasswordToken"]);
        $(".gateway-id").html( gatewayObject["gatewayId"]);
        $(".oauth-client-key").html( gatewayObject["oauthClientId"]);
        $(".oauth-client-secret").html( gatewayObject["oauthClientSecret"]);
        $("#viewCredentials").modal("show");
    });
</script>
@stop