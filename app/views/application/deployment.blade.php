@extends('layout.basic')

@section('page-header')
    @parent
    {{ HTML::style('css/style.css') }}
@stop

@section('content')

<div class="container">
	<div class="col-md-offset-2 col-md-8">
		
		<div class="row">
			<button class="btn btn-default create-app-deployment">Create a new Application Deployment</button>
		</div>
		@if( count( $appDeployments) )
			@if( Session::has("message"))
				<div class="row">
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						{{ Session::get("message") }}
					</div>
				</div>
				{{ Session::forget("message") }}
			@endif

			<div class="row">
				<div class="col-md-6">
					<h3>Existing Application Deployments :</h3>
				</div>
				<div class="col-md-6" style="margin-top:3.5%">
					<input type="text" class="col-md-12 filterinput" placeholder="Search by Deployment Id Name" />
				</div>
			</div>
			<div class="panel-group" id="accordion">
			@foreach( $appDeployments as $index => $deployment )
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle collapsed deployment-id" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$index}}">
							{{ $deployment->appDeploymentId }}
							</a>
							<div class="pull-right col-md-2 deployment-options fade">
								<span class="glyphicon glyphicon-pencil edit-app-deployment" style="cursor:pointer;" data-toggle="modal" data-target="#edit-app-deployment-block" data-deployment-id="{{ $deployment->appDeploymentId }}"></span>
								<span class="glyphicon glyphicon-trash delete-app-deployment" style="cursor:pointer;" data-toggle="modal" data-target="#delete-app-deployment-block" data-deployment-id="{{ $deployment->appDeploymentId }}"></span>
							</div>
						</h4>
					</div>
					<div id="collapse-{{$index}}" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="app-deployment-block">
								@include('partials/deployment-block', array( 'deploymentObject' => $deployment, 'computeResources' => $computeResources, 'modules' => $modules) )
							</div>
						</div>
					</div>
				</div>
			@endforeach
			</div>
		@endif

		<div class="load-cmd-ui hide">
			<input name="moduleLoadCmds[]" type="text" class="form-control" placeholder="Module Load Command"/>
		</div>

		<div class="lib-prepend-path-ui hide">
			<div class="col-md-12 well">
				<input name="libraryPrependPathName[]" type="text" class="col-md-4" placeholder="Name"/>
				<input name="libraryPrependPathValue[]" type="text" class="col-md-8" placeholder="Value"/>
			</div>
		</div>

		<div class="lib-append-path-ui hide">
			<div class="col-md-12 well">
				<input name="libraryAppendPathName[]" type="text" class="col-md-4" placeholder="Name"/>
				<input name="libraryAppendPathValue[]" type="text" class="col-md-8" placeholder="Value"/>
			</div>
		</div>

		<div class="environment-ui hide">
			<div class="col-md-12 well">
				<input name="environmentName[]" type="text" class="col-md-4" placeholder="Name"/>
				<input name="environmentValue[]" type="text" class="col-md-8" placeholder="Value"/>
			</div>
		</div>

		<div class="pre-job-command-ui hide">
			<div class="col-md-12 well">
				<input name="preJobCommand[]" type="text" class="col-md-12" placeholder="Pre Job Command"/>
			</div>
		</div>

		<div class="post-job-command-ui hide">
			<div class="col-md-12 well">
				<input name="postJobCommand[]" type="text" class="col-md-12" placeholder="Post Job Command"/>
			</div>
		</div>

		<div class="modal fade" id="edit-app-deployment-block" tabindex="-1" role="dialog" aria-labelledby="add-modal" aria-hidden="true">
		    <div class="modal-dialog">
				<form action="{{URL::to('/')}}/app/deployment-edit" method="POST">	
		        <div class="modal-content">
			    	<div class="modal-header">
			    		<h3 class="text-center">Edit Application Deployment</h3>
			    	</div>
			    	<div class="modal-body row">
						<div class="app-deployment-form-content col-md-12">
						</div>
					</div>
					<div class="modal-footer">
			        	<div class="form-group">
							<input type="submit" class="btn btn-primary" value="Update"/>
							<input type="button" class="btn btn-default" data-dismiss="modal" value ="Cancel"/>
						</div>
			        </div>	
		        </div>
		        </form>
		    </div>
		</div>

		<div class="modal fade" id="create-app-deployment-block" tabindex="-1" role="dialog" aria-labelledby="add-modal" aria-hidden="true">
		    <div class="modal-dialog">
				<form action="{{URL::to('/')}}/app/deployment-create" method="POST">	
		        <div class="modal-content">
			    	<div class="modal-header">
			    		<h3 class="text-center">Create Application Deployment</h3>
			    	</div>
			    	<div class="modal-body row">
						<div class="col-md-12">
							<div class="create-app-deployment-block">
								@include('partials/deployment-block', array( 'computeResources' => $computeResources, 'modules' => $modules) )
							</div>
						</div>
					</div>
					<div class="modal-footer">
			        	<div class="form-group">
							<input type="submit" class="btn btn-primary" value="Create"/>
							<input type="button" class="btn btn-default" data-dismiss="modal" value ="Cancel"/>
						</div>
			        </div>	
		        </div>
		        </form>
		    </div>
		</div>

		<div class="modal fade" id="delete-app-deployment-block" tabindex="-1" role="dialog" aria-labelledby="add-modal" aria-hidden="true">
		    <div class="modal-dialog">

				<form action="{{URL::to('/')}}/app/deployment-delete" method="POST">
			        <div class="modal-content">
			            <div class="modal-header">
			              	<h3 class="text-center">Delete Confirmation Application Deployment</h3>
			            </div>
			            <div class="modal-body">
							<input type="hidden" class="form-control delete-deploymentId" name="appDeploymentId"/>
					 		Do you really want to delete the Application Deployment - <span class="delete-deployment-id"></span>
						</div>
						<div class="modal-footer">
							<div class="form-group">
								<input type="submit" class="btn btn-danger" value="Delete"/>
								<input type="button" class="btn btn-default" data-dismiss="modal" value ="Cancel"/>
							</div>
						</div>
					</div>

				</form>


			</div>
		</div>

	</div>

</div>

@stop

@section('scripts')
	@parent
    {{ HTML::script('js/deployment.js') }}
@stop