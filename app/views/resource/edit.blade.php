@extends('layout.basic')

@section('page-header')
    @parent
    {{ HTML::style('css/style.css') }}
@stop

@section('content')

<div class="container">
	<div class="col-md-offset-2 col-md-8">

		<input type="hidden" class="base-url" value="{{URL::to('/')}}"/>

		<div class="well">
			<h4>Compute Resource : {{ $computeResource->hostName }}</h4>
		</div>
		@if( Session::has("message"))
			<span class="alert alert-success col-md-12">{{Session::get("message")}}</span>
			{{Session::forget("message") }}
		@endif

		<div class="col-md-12">
			<ul class="nav nav-tabs nav-justified" id="tabs" role="tablist">
			  <li class="active"><a href="#tab-desc" data-toggle="tab">Description</a></li>
			  <li><a href="#tab-queues" data-toggle="tab">Queues</a></a></li>
			  <li><a href="#tab-filesystem" data-toggle="tab">FileSystem</a></li>
			  <li><a href="#tab-jobSubmission" data-toggle="tab">Job Submission Interfaces</a></li>
			  <li><a href="#tab-dataMovement" data-toggle="tab">Data Movement Interfaces</a></li>
			</ul>
		</div>

		<div class="tab-content">
        	
        	<div class="tab-pane active" id="tab-desc">

				<form role="form" method="POST" action="{{ URL::to('/') }}/cr/edit">
					<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
					<input type="hidden" name="cr-edit" value="resDesc"/>
					<div class="form-group required">
						<label class="control-label">Host Name</label>
						<input class="form-control hostName" value="{{ $computeResource->hostName }}" maxlength="100" name="hostname" required="required"/>
					</div>
					<div class="form-group">
						<label class="control-label">Host Aliases</label>
						@if( count(  $computeResource->hostAliases) )
							@foreach( $computeResource->hostAliases as $hostAlias )
								<input class="form-control" value="{{$hostAlias}}" maxlength="30" name="hostaliases[]"/>
							@endforeach
						@else
							<input class="form-control" value="" maxlength="30" name="hostaliases[]"/>
						@endif
						<button type="button" class="btn btn-sm btn-default add-alias">Add Aliases</button>
					</div>
					<div class="form-group">
						<label class="control-label">IP Addresses</label>
						@if( count( $computeResource->ipAddresses))
							@foreach( $computeResource->ipAddresses as $ip )
								<input class="form-control" value="{{ $ip }}" maxlength="30" name="ips[]"/>
							@endforeach
						@else
							<input class="form-control" value="" maxlength="30" name="ips[]"/>
						@endif
						<button type="button" class="btn btn-sm btn-default add-ip">Add IP Addresses</button>
					</div>
					<div class="form-group">
						<label class="control-label">Resource Description</label>
						<textarea class="form-control" maxlength="255" name="description">{{ $computeResource->resourceDescription }}</textarea>
					</div>
					<div class="form-group">
						<label class="control-label">Maximum Memory Per Node ( In MB )</label>
						<input type="number" min="0" class="form-control" value="{{ $computeResource->maxMemoryPerNode }}" maxlength="30" name="maxMemoryPerNode"/>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-primary" name="step1" value="Save changes"/>
					</div>

				</form>

			</div>

        	<div class="tab-pane" id="tab-queues">

        		@if( is_array( $computeResource->batchQueues) )
					<h3>Existing Queues :</h3>
					<div class="panel-group" id="accordion">
					@foreach( $computeResource->batchQueues as $index => $queue)
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a class="accordion-toggle collapsed existing-queue-name" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$index}}">{{ $queue->queueName }}</a>
									<div class="pull-right col-md-1">
										<span class="glyphicon glyphicon-trash delete-queue" style="cursor:pointer;" data-toggle="modal" data-target="#delete-queue" data-queue-name="{{ $queue->queueName }}"></span>
									</div>
								</h4>
							</div>
							<div id="collapse-{{$index}}" class="panel-collapse collapse">
								<div class="panel-body">
									<form role="form" method="POST" action="{{ URL::to('/')}}/cr/edit">
										<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
										<div class="queue">
											<input type="hidden" name="cr-edit" value="queue"/>
											<div class="form-group required">
												<label class="control-label">Queue Name <small> ( cannot be changed.) </small></label>
												<input class="form-control" value="{{ $queue->queueName }}" maxlength="30" name="qname" placeholder="Queue Name" readonly />
											</div>
											@include('partials/queue-block', array('queueData'=>$queue))
						          			<div class="form-group">
												<input type="submit" min="0" class="btn" name="step1" value="Update"/>
											</div>
						          		</div>
						      		</form>
						      	</div>
						    </div>
						</div>
			  		@endforeach
			  		</div>
			  	@endif
				<div class="queue-block hide">
					<form role="form" method="POST" action="{{ URL::to('/')}}/cr/edit">
						<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
						<div class="queue">
							<div class="queue">
								<input type="hidden" name="cr-edit" value="queue"/>
								<div class="form-group required">
									<label class="control-label">Queue Name<small> ( A queue name is unique and cannot be changed later.)</small></label>
									<input class="form-control create-queue-name" maxlength="30" name="qname" placeholder="Queue Name" required="required"/>
								</div>
								@include('partials/queue-block')
								
			          		</div>
		          			<div class="form-group">
								<input type="button" class="btn create-queue-form btn-primary" name="step1" value="Create"/>
								<input type="reset" class="btn  btn-success" value="Reset"/>
							</div>
		          		</div>
		      		</form>
		      	</div>
				<div class="form-group well add-queue-block">
					<button type="button" class="btn btn-sm btn-default add-queue">Add a Queue</button>
				</div>

			</div>

        	<div class="tab-pane" id="tab-filesystem">

        		<form role="form" method="POST" action="{{URL::to('/')}}/cr/edit">
					<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
					<input type="hidden" name="cr-edit" value="fileSystems"/>
					<div class="form-group">
						<h3>FileSystem</h3>
						@foreach( $fileSystems as $index => $fileSystem)
							<label class="control-label">{{ $fileSystem }}</label>
							<input class="form-control" name="fileSystems[{{ $index }}]" placeholder="{{ $fileSystem }}" value="@if( isset( $computeResource->fileSystems[ $index]) ){{ $computeResource->fileSystems[ $index] }} @endif"/>
						@endforeach
						</select>
					</div>
					<div class="form-group">
						<button class="btn btn-prim">Update</button>
					</div>
				</form>

			</div>

        	<div class="tab-pane" id="tab-jobSubmission">

        		<div class="form-group">
					<div class="job-submission-info row hide"></div>
					<button type="button" class="btn btn-sm btn-default add-job-submission">Add a new Job Submission Interface</button>
					@if( count( $jobSubmissionInterfaces ) > 1)
        				<button type="button" class="btn btn-sm btn-default update-priority" data-type="jsi" data-toggle="modal" data-target="#update-jsi-priority">Update Priority</button>
        			@endif
				</div>

        		@if( count( $jobSubmissionInterfaces ) )
        			<div class="job-edit-info">
        			@foreach( $jobSubmissionInterfaces as $index => $JSI )

        				<div class="job-protocol-block">
							<form role="form" method="POST" action="{{ URL::to('/') }}/cr/edit">
								<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
								<input type="hidden" name="cr-edit" value="edit-jsp"/>
								<input type="hidden" name="jsiId" value="{{ $JSI->jobSubmissionInterfaceId }}"/>
								<?php $selectedJspIndex = $computeResource->jobSubmissionInterfaces[ $index]->jobSubmissionProtocol; ?>

        						<h4>Job Submission Protocol : {{ $jobSubmissionProtocols[ $selectedJspIndex] }}
									<button type='button' class='close delete-jsi' data-toggle="modal" data-target="#confirm-delete-jsi" data-jsi-id="{{ $JSI->jobSubmissionInterfaceId }}">
										<span class="glyphicon glyphicon-trash delete-jsi" data-toggle="modal" data-target="#confirm-delete-jsi" data-jsi-id="{{ $JSI->jobSubmissionInterfaceId }}"></span>
									</button>
								</h4>
								<input type="hidden" name="jobSubmissionProtocol" value="{{ $selectedJspIndex }}"/>
								@if( $selectedJspIndex == $jobSubmissionProtocolsObject::LOCAL)
									<div class="select-resource-manager-type">
										<div class="form-group required">
											<label class="control-label">Select resource manager type</label>
											<select name="resourceJobManagerType" class="form-control selected-resource-manager" required="required">
											@foreach( $resourceJobManagerTypes as $index => $rJmT)
												<option value="{{ $index }}" @if( $JSI->resourceJobManager->resourceJobManagerType == $index ) selected @endif >{{ $rJmT }}</option>
											@endforeach
											</select>
										</div>
										<div class="form-group">
											<label class="control-label">Push Monitoring End Point</label>
											<input type="text" class="form-control" name="pushMonitoringEndpoint" value="{{ $JSI->resourceJobManager->pushMonitoringEndpoint }}"/>
										</div>
										<div class="form-group">
											<label class="control-label">Job Manager Bin Path</label>
											<input type="text" class="form-control" name="jobManagerBinPath" value="{{ $JSI->resourceJobManager->jobManagerBinPath }}"/>
										</div>
										<div class="form-group">
											<h3>Job Manager Commands</h3>
											@foreach( $jobManagerCommands as $index => $jmc)
												<label class="control-label">{{ $jmc }}</label>
												<input class="form-control" name="jobManagerCommands[{{ $index }}]" placeholder="{{ $jmc }}" value="@if( isset( $JSI->resourceJobManager->jobManagerCommands[$index] ) ) {{ $JSI->resourceJobManager->jobManagerCommands[$index] }} @endif"/>
											@endforeach
											</select>
										</div>
									</div>
								@elseif( $selectedJspIndex == $jobSubmissionProtocolsObject::SSH)
									<div class="form-group required">		
										<label class="control-label">Select Security Protocol</label>
										<select name="securityProtocol" required="required">
										@foreach( $securityProtocols as $index => $sp)
											<option value="{{ $index }}" @if( $JSI->securityProtocol == $index ) selected @endif>{{ $sp }}</option>
										@endforeach
										</select>
									</div>

									<div class="form-group">
										<label class="control-label">Alternate SSH Host Name</label>
						                <input class='form-control' name='alternativeSSHHostName' value="{{ $JSI->alternativeSSHHostName}}"/>
						            </div>
						            <div class="form-group">
										<label class="control-label">SSH Port</label>
						                <input class='form-control' name='sshPort' value="{{ $JSI->sshPort }}"/>
						            </div>

						            <div class="form-group required">
										<label class="control-label">Select Monitoring Mode</label>
						            	<select name="monitorMode" required>
						            		@foreach( $monitorModes as $index => $mode)
						            			<option value="{{ $index }}" @if( $JSI->monitorMode == $index ) selected @endif>{{ $mode}}</option>
						            		@endforeach
						            	</select>
						            </div>

									<div class="form-group">
										<div class="select-resource-manager-type">
											<div class="form-group required">
												<label class="control-label">Select resource manager type</label>
												<select name="resourceJobManagerType" class="form-control selected-resource-manager" required="required">
												@foreach( $resourceJobManagerTypes as $index => $rJmT)
													<option value="{{ $index }}" @if( $JSI->resourceJobManager->resourceJobManagerType == $index ) selected @endif >{{ $rJmT }}</option>
												@endforeach
												</select>
											</div>
											<div class="form-group">
												<label class="control-label">Push Monitoring End Point</label>
												<input type="text" class="form-control" name="pushMonitoringEndpoint" value="{{ $JSI->resourceJobManager->pushMonitoringEndpoint }}"/>
											</div>
											<div class="form-group">
												<label class="control-label">Job Manager Bin Path</label>
												<input type="text" class="form-control" name="jobManagerBinPath" value="{{ $JSI->resourceJobManager->jobManagerBinPath }}"/>
											</div>
											<div class="form-group">
												<h3>Job Manager Commands</h3>
												@foreach( $jobManagerCommands as $index => $jmc)
													<label class="control-label">{{ $jmc }}</label>
													<input class="form-control" name="jobManagerCommands[{{ $index }}]" placeholder="{{ $jmc }}" value="@if( isset( $JSI->resourceJobManager->jobManagerCommands[$index] ) ) {{ $JSI->resourceJobManager->jobManagerCommands[$index] }} @endif"/>
												@endforeach
											</div>
										</div>
									</div>
						            
								@elseif(  $selectedJspIndex == $jobSubmissionProtocolsObject::UNICORE)
									<div class="form-group required">		
										<label class="control-label">Select Security Protocol</label>
										<select name="securityProtocol" required="required">
										@foreach( $securityProtocols as $index => $sp)
											<option value="{{ $index }}" @if( $JSI->securityProtocol == $index ) selected @endif>{{ $sp }}</option>
										@endforeach
										</select>
									</div>
									<div class="form-group">
										<label class="form-label">Unicore End Point URL</label>
										<input class='form-control' name='unicoreEndPointURL' value="{{ $JSI->unicoreEndPointURL }}"/>
									</div>
								@endif
								<div class="form-group">
									<button type="submit" class="btn">Update</button>
								</div>
							</form>

						</div>
        			@endforeach
        			</div>
        		@endif

				<div class="select-job-protocol hide">
					<form role="form" method="POST" action="{{ URL::to('/') }}/cr/edit">
						<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
						<input type="hidden" name="cr-edit" value="jsp"/>
						
						<div class="form-group">
							<label class="control-label">Job Submission Protocol:</label>
							<select name="jobSubmissionProtocol" class="form-control selected-job-protocol" required="required">
								<option></option>
							@foreach( $jobSubmissionProtocols as $index => $jobSubmissionProtocol)
								@if( ! in_array( $index, $addedJSP))
									<option value="{{ $index }}">{{ $jobSubmissionProtocol }}</option>
								@endif
							@endforeach
							</select>
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-primary jspSubmit hide">Add Job Submission Protocol</button>
						</div>
					</form>
				</div>

        	</div>

        	<div class="tab-pane" id="tab-dataMovement">

				<div class="form-group">
					<div class="data-movement-info row hide"></div>
					<button type="button" class="btn btn-sm btn-default add-data-movement">Add a new Data Movement Interface</button>
        			@if( count( $dataMovementInterfaces ) > 1)
						<button type="button" class="btn btn-sm btn-default update-priority" data-type="dmi"  data-toggle="modal" data-target="#update-dmi-priority">Update Priority</button>
					@endif				
				</div>

        		@if( count( $dataMovementInterfaces ) )
        			<div class="job-edit-info">
        			@foreach( $dataMovementInterfaces as $index => $DMI )
        				<div class="data-movement-block">
							<form role="form" method="POST" action="{{ URL::to('/') }}/cr/edit">
								<input type="hidden" name="crId" class="crId" value="{{Input::get('crId') }}"/>
								<input type="hidden" name="cr-edit" value="edit-dmi"/>
								<input type="hidden" name="dmiId" value="{{ $DMI->dataMovementInterfaceId }}"/>

								<?php $selectedDMIIndex = $computeResource->dataMovementInterfaces[ $index]->dataMovementProtocol; ?>

        						<h4>Data Movement Protocol : {{ $dataMovementProtocols[ $selectedDMIIndex] }}
									<button type='button' class='close delete-dmi' data-toggle="modal" data-target="#confirm-delete-dmi" data-dmi-id="{{ $DMI->dataMovementInterfaceId }}">
										<span class="glyphicon glyphicon-trash delete-dmi" data-toggle="modal" data-target="#confirm-delete-dmi" data-dmi-id="{{ $DMI->dataMovementInterfaceId }}"></span>
									</button>
								</h4>
								<input type="hidden" name="dataMovementProtocol" value="{{ $selectedDMIIndex }}"/>
								@if( $selectedDMIIndex == $dataMovementProtocolsObject::LOCAL)
									<!-- Nothing here on local UI -->
								@elseif( $selectedDMIIndex == $dataMovementProtocolsObject::SCP)
									<div class="form-group">		
										<label class="control-label">Select Security Protocol</label>
										<select name="securityProtocol">
										@foreach( $securityProtocols as $index => $sp)
											<option value="{{ $index }}" @if( $DMI->securityProtocol == $index ) selected @endif>{{ $sp }}</option>
										@endforeach
										</select>
									</div>

									<div class="form-group">
										<label class="control-label">Alternate SSH Host Name</label>
						                <input class='form-control' name='alternativeSSHHostName' value="{{ $DMI->alternativeSCPHostName }}"/>
						            </div>
						            <div class="form-group">
										<label class="control-label">SSH Port</label>
						                <input class='form-control' name='sshPort' value="{{ $DMI->sshPort }}"/>
						            </div>
						            <div class="form-group">
						            	<button type="submit" class="btn">Update</button>
						            </div>
								@elseif( $selectedDMIIndex == $dataMovementProtocolsObject::GridFTP)
									<div class="form-group">		
										<label class="control-label">Select Security Protocol</label>
										<select name="securityProtocol">
										@foreach( $securityProtocols as $index => $sp)
											<option value="{{ $index }}" @if( $DMI->securityProtocol == $index ) selected @endif>{{ $sp }}</option>
										@endforeach
										</select>
										<div>
											<div class="form-group required">
												<label class="control-label">Grid FTP End Points</label>
												@foreach( $DMI->gridFTPEndPoints as $endPoint)
													<input class="form-control" maxlength="30" name="gridFTPEndPoints[]" required="required" value="{{$endPoint}}"/>
												@endforeach
												<button type="button" class="btn btn-sm btn-default add-gridFTPEndPoint">Add More Grid FTP End Points</button>
											</div>
										</div>
										<div class="form-group">
							            	<button type="submit" class="btn">Update</button>
							            </div>
									</div>
								@elseif( $selectedDMIIndex == $dataMovementProtocolsObject::UNICORE_STORAGE_SERVICE)
									<div class="form-group">		
										<label class="control-label">Select Security Protocol</label>
										<select name="securityProtocol">
										@foreach( $securityProtocols as $index => $sp)
											<option value="{{ $index }}" @if( $DMI->securityProtocol == $index ) selected @endif>{{ $sp }}</option>
										@endforeach
										</select>
										<div>
											<div class="form-group required">
												<label class="control-label">Unicore End Point URL</label>
												<input class="form-control" maxlength="30" name="unicoreEndPointURL" required="required" value="{{ $DMI->unicoreEndPointURL }}"/>
											</div>
										</div>
										<div class="form-group">
							            	<button type="submit" class="btn">Update</button>
							            </div>
									</div>
								@endif
							</form>
						</div>
					@endforeach
					</div>
				@endif
        		<div class="select-data-movement hide">

					<form role="form" method="POST" action="{{ URL::to('/') }}/cr/edit">
						<input type="hidden" name="crId" class="crId" value="{{Input::get('crId') }}"/>
						<input type="hidden" name="cr-edit" value="dmp"/>
						<h4>
							Select the Data Movement Protocol
						</h4>

						<select name="dataMovementProtocol" class="form-control selected-data-movement-protocol">
							<option></option>
						@foreach( $dataMovementProtocols as $index => $dmp)
							@if( ! in_array( $index, $addedDMI))
								<option value="{{ $index }}">{{ $dmp }}</option>
							@endif
						@endforeach
						</select>

						<div class="form-group">
							<button type="submit" class="btn btn-primary dmpSubmit hide">Add Data Movement Protocol</button>
						</div>

					</form>

				</div>

        	</div>


		</div>


		<div class="resource-manager-block hide">
			<div class="select-resource-manager-type">
				<div class="form-group required">
					<label class="control-label">Select resource manager type</label>
					<select name="resourceJobManagerType" class="form-control selected-resource-manager" required="required">
					@foreach( $resourceJobManagerTypes as $index => $rJmT)
						<option value="{{ $index }}">{{ $rJmT }}</option>
					@endforeach
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label">Push Monitoring End Point</label>
				<input type="text" class="form-control" name="pushMonitoringEndpoint"/>
			</div>
			<div class="form-group">
				<label class="control-label">Job Manager Bin Path</label>
				<input type="text" class="form-control" name="jobManagerBinPath"/>
			</div>
			<div class="form-group">
				<h3>Job Manager Commands</h3>
				@foreach( $jobManagerCommands as $index => $jmc)
					<label class="control-label">{{ $jmc }}</label>
					<input class="form-control" name="jobManagerCommands[{{ $index }}]" placeholder="{{ $jmc }}"/>
				@endforeach
				</select>
			</div>
		</div>

		<div class="ssh-block hide">
			<div class="form-group required">		
				<label class="control-label">Select Security Protocol  </label>
				<select name="securityProtocol" required>
				@foreach( $securityProtocols as $index => $sp)
					<option value="{{ $index }}">{{ $sp }}</option>
				@endforeach
				</select>
			</div>

	        <div class="form-group required">
				<label class="control-label">Select Monitoring Mode  </label>
	        	<select name="monitorMode" required>
	        		@foreach( $monitorModes as $index => $mode)
	        		<option value="{{ $index }}">{{ $mode}}</option>
	        		@endforeach
	        	</select>
	        </div>

			<div class="form-group addedScpValue hide">
				<label class="control-label">Alternate SSH Host Name</label>
                <input class='form-control' name='alternativeSSHHostName'/>
            </div>
            <div class="form-group addedScpValue hide">
				<label class="control-label">SSH Port</label>
                <input class='form-control' name='sshPort'/>
            </div>
		</div>

		<div class="cloud-block hide">
			<div class="form-group">
				<label class="control-label">Node Id</label>
				<input class="form-control" name="nodeId" placeholder="nodId"/>
			</div>
			<div class="form-group">
				<label class="control-label">Node Id</label>
				<input class="form-control" name="nodeId" placeholder="nodId"/>
			</div>
			<div class="form-group">
				<label class="control-label">Executable Type</label>
				<input class="form-control" name="nodeId" placeholder="executableType"/>
			</div>
			<div class="form-group">
			<label class="control-label">Select Provider Name</label>
			<select class="form-control">
				<option name="EC2">EC2</option>
				<option name="AWSEC2">AWEC2</option>
				<option name="RACKSPACE">RACKSPACE</option>
			</select>
			</div>
		</div>

		<div class="dm-gridftp hide">
			<div class="form-group required">
				<label class="control-label">Grid FTP End Points</label>
				<input class="form-control" maxlength="30" name="gridFTPEndPoints[]" required/>
				<button type="button" class="btn btn-sm btn-default add-gridFTPEndPoint">Add More Grid FTP End Points</button>
			</div>
		</div>

		<!-- 
		<div class="form-group">
			<input type="submit" class="btn  btn-primary" name="step2" value="Continue"/>
			<input type="reset" class="btn  btn-success" value="Reset"/>
		</div>

		--> 
	</div>
</div>

<!-- modals -->

<div class="modal fade" id="confirm-delete-jsi" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="{{ URL::to('cr/delete-jsi') }}" method="POST">
			<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
    		<input type="hidden" name="jsiId" value="" class="delete-jsi-confirm"/>
            <div class="modal-header">
                Confirmation
            </div>
            <div class="modal-body">
                Do you really want to delete this Job Submission Interface ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger danger">Delete</button>
            </div>
        </form>

        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete-dmi" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="{{ URL::to('cr/delete-jsi') }}" method="POST">
			<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
    		<input type="hidden" name="dmiId" value="" class="delete-dmi-confirm"/>
            <div class="modal-header">
                Confirmation
            </div>
            <div class="modal-body">
                Do you really want to delete this Data Movement Interface ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger danger">Delete</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="add-jsi" tabindex="-1" role="dialog" aria-labelledby="add-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Add a Job Submission Interface
            </div>
            <div class="modal-body add-jsi-body row">
               
            </div>
        </div>
    </div>
</div>

@if( count( $jobSubmissionInterfaces ) > 1)
<div class="modal fade" id="update-jsi-priority" tabindex="-1" role="dialog" aria-labelledby="add-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               Update the Priority Order
            </div>
            <div class="modal-body">
            	<!-- dirty hack to avoid some code that removes the form tag below this. Needs better fix. -->
            	<form></form>

            	<form action="{{URL::to('/')}}/cr/edit" method="POST" id="jsi-priority-form"> 
					<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
	            	<input type="hidden" name="cr-edit" value="jsi-priority"/>
	        		@foreach( $computeResource->jobSubmissionInterfaces as $index => $JSI )
	            	<div class="row">
	    				<div class="col-md-offset-2 col-md-2">
	        				<label>
	        					{{ $jobSubmissionProtocols[ $JSI->jobSubmissionProtocol] }}
	        				</label>
	        			</div>
	    				<input type="hidden" name="jsi-id[]" maxlength="2" value="{{ $JSI->jobSubmissionInterfaceId }}"/>
	    				<div class="col-md-4">
	        				<input type="number" name="jsi-priority[]" min="0" max="{{ count( $jobSubmissionInterfaces) }}" value="{{ $JSI->priorityOrder }}" required/>
						</div>
	        		</div>
	        		@endforeach
	        		<button type="submit" class="btn btn-update">Update</button>
	        		<div class='priority-updated alert alert-success hide'>
	        			The Job Submission Interface Priority has been updated.
	        		</div>
        		</form>
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="add-dmi" tabindex="-1" role="dialog" aria-labelledby="add-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Add a Data Model Interface
            </div>
            <div class="modal-body add-dmi-body row">
               
            </div>
        </div>
    </div>
</div>

@if( count( $dataMovementInterfaces ) > 1)
<div class="modal fade" id="update-dmi-priority" tabindex="-1" role="dialog" aria-labelledby="add-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
               Update the Priority Order
            </div>
            <div class="modal-body">
            	<form action="{{URL::to('/')}}/cr/edit" method="POST" id="dmi-priority-form"> 
					<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>
	            	<input type="hidden" name="cr-edit" value="dmi-priority"/>
	        		@foreach( $computeResource->dataMovementInterfaces as $index => $DMI )
	            	<div class="row">
	    				<div class="col-md-offset-2 col-md-2">
	        				<label>
	        					{{ $dataMovementProtocols[ $DMI->dataMovementProtocol] }}
	        				</label>
	        			</div>
	    				<input type="hidden" name="dmi-id[]" maxlength="2" value="{{ $DMI->dataMovementInterfaceId }}"/>
	    				<div class="col-md-4">
	        				<input type="number" min="0" name="dmi-priority[]" value="{{ $DMI->priorityOrder }}" required/>
						</div>
	        		</div>
	        		@endforeach
	        		<button type="submit" class="btn btn-update">Update</button>
	        		<div class='priority-updated alert alert-success hide'>
	        			The Data Movement Interface Priority has been updated.
	        		</div>
        		</form>
            </div>
        </div>
    </div>
</div>
@endif


<div class="modal fade" id="delete-queue" tabindex="-1" role="dialog" aria-labelledby="add-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
   			<!-- dirty hack to neutralise form problem in code by just adding an empty form tag before the actual form. Needs fix.-->
        	<form></form>
        	<form action="{{URL::to('/')}}/cr/edit" method="POST"/> 	
				<input type="hidden" name="crId" value="{{Input::get('crId') }}"/>    
				<input type="hidden" name="cr-edit" value="delete-queue"/>
				<input type="hidden" name="queueName" class="delete-queueName" value=""/>
	            <div class="modal-header">
	               Confirmation to Delete Queue
	            </div>
	            <div class="modal-body">
						Do you really want to delete the Batch Queue - <span class="delete-queueName"></span>?
	            </div>
	            <div class="modal-footer">
	            	<button type="submit" class="btn btn-danger">Delete</button>
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            </div>
	        </form>
        </div>
    </div>
</div>
@stop

@section('scripts')
	@parent
    {{ HTML::script('js/script.js') }}

    <script type="text/javascript">
    	$(".delete-queue").click( function(){
    		$(".delete-queueName").val( $(this).data("queue-name") );
    		$(".delete-queueName").html( $(this).data("queue-name") );
    	})
    </script>
@stop