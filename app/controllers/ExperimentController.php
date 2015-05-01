 <?php

class ExperimentController extends BaseController {

	/**
	*    Instantiate a new ExperimentController Instance
	**/

	public function __construct()
	{
		$this->beforeFilter('verifylogin');
		Session::put("nav-active", "experiment");
	}

	public function createView()
	{
		Session::forget( 'exp_create_continue');
		return View::make('experiment/create');
	}

	public function createSubmit()
	{
		$inputs = Input::all();

		if( isset( $_POST['continue'] ))
		{
			Session::put( 'exp_create_continue', true);
			
			$computeResources = Utilities::create_compute_resources_select($_POST['application'], null);

			$app_config = Utilities::read_config();

			$queueDefaults = array( "queueName" => $app_config["queue-name"],
						        	"nodeCount" => $app_config["node-count"],
						        	"cpuCount" => $app_config["total-cpu-count"],
						        	"wallTimeLimit" => $app_config["wall-time-limit"]
							);

			$experimentInputs = array( 
								"disabled" => ' disabled',
						        "experimentName" => $_POST['experiment-name'],
						        "experimentDescription" => $_POST['experiment-description'] . ' ',
						        "project" => $_POST['project'],
						        "application" => $_POST['application'],
						        "allowedFileSize" => $app_config["server-allowed-file-size"],
						        "echo" => ($_POST['application'] == 'Echo')? ' selected' : '',
						        "wrf" => ($_POST['application'] == 'WRF')? ' selected' : '',
						        "queueDefaults" => $queueDefaults,
						        "advancedOptions" => $app_config["advanced-experiment-options"],
						        "computeResources" => $computeResources,
						        "resourceHostId" => null,
						        "advancedOptions" => $app_config["advanced-experiment-options"]
					        );
			return View::make( "experiment/create-complete", array( "expInputs" => $experimentInputs) );
		}

		else if (isset($_POST['save']) || isset($_POST['launch']))
		{
		    $expId = Utilities::create_experiment();

		    if (isset($_POST['launch']) && $expId)
		    {
		        Utilities::launch_experiment($expId);
		    }
		    /* Not required.
		    else
		    {
		        Utilities::print_success_message("<p>Experiment {$_POST['experiment-name']} created!</p>" .
		            '<p>You will be redirected to the summary page shortly, or you can
		            <a href=' . URL::to('/') . '"/experiment/summary?expId=' . $expId . '">go directly</a> to experiment summary page.</p>');
		        
		    }*/
        	return Redirect::to('experiment/summary?expId=' . $expId);
		}
		else
			return Redirect::to("home")->with("message", "Something went wrong here. Please file a bug report using the link in the Help menu.");
	}

	public function summary()
	{
		$experiment = Utilities::get_experiment($_GET['expId']);
		if( $experiment != null)
		{
			$project = Utilities::get_project($experiment->projectID);
			$expVal = Utilities::get_experiment_values( $experiment, $project);
			$jobDetails = Utilities::get_job_details( $experiment->experimentID);
			$transferDetails = Utilities::get_transfer_details( $experiment->experimentID);
			//var_dump( $jobDetails); exit;
			// User should not clone or edit a failed experiment. Only create clones of it.
			if( $expVal["experimentStatusString"] == "FAILED")
				$expVal["editable"] = false;

			$expVal["cancelable"] = false;
			if( $expVal["experimentStatusString"] == "LAUNCHED" || $expVal["experimentStatusString"] == "EXECUTING" )
				$expVal["cancelable"] = true;

			$data = array(
										"expId" => Input::get("expId"),
										"experiment" => $experiment,
										"project" => $project,
										"jobDetails" => $jobDetails,
										"expVal" => $expVal
						);

			if( Request::ajax() )
			{
				//admin wants to see an experiment summary
				if( Input::has("dashboard"))
				{
					$data["dashboard"] = true;
					return View::make("partials/experiment-info", $data);
				}
				else
					return json_encode( $experiment);
			}
			else
			{
				return View::make( "experiment/summary", $data);
			}
		}
		else
		{
			if( Input::has("dashboard"))
				return View::make( "partials/experiment-info", array("invalidExperimentId" => 1)); 
			else
				return View::make( "experiment/summary", array("invalidExperimentId" => 1));
		}
	}

	public function expCancel()
	{
		Utilities::cancel_experiment( Input::get("expId"));

		return Redirect::to('experiment/summary?expId=' . Input::get("expId"));
	}

	public function expChange()
	{
		//var_dump( Input::all() ); exit;
		$experiment = Utilities::get_experiment( Input::get('expId') );
		$project = Utilities::get_project($experiment->projectID);

		$expVal = Utilities::get_experiment_values( $experiment, $project);
		/*if (isset($_POST['save']))
		{
		    $updatedExperiment = Utilities::apply_changes_to_experiment($experiment);

		    Utilities::update_experiment($experiment->experimentID, $updatedExperiment);
		}*/
		if (isset($_POST['launch']))
		{
		    Utilities::launch_experiment($experiment->experimentID);
			return Redirect::to('experiment/summary?expId=' . $experiment->experimentID);
		}
		elseif (isset($_POST['clone']))
		{
		    $cloneId = Utilities::clone_experiment($experiment->experimentID);
		    $experiment = Utilities::get_experiment( $cloneId );
			$project = Utilities::get_project($experiment->projectID);

			$expVal = Utilities::get_experiment_values( $experiment, $project);

			return Redirect::to('experiment/edit?expId=' . $experiment->experimentID);

		}
		
		elseif (isset($_POST['cancel']))
		{
		    Utilities::cancel_experiment($experiment->experimentID);
			return Redirect::to('experiment/summary?expId=' . $experiment->experimentID);

		}
	}

	public function editView()
	{
		$app_config = Utilities::read_config();
		$queueDefaults = array( "queueName" => $app_config["queue-name"],
						        "nodeCount" => $app_config["node-count"],
						        "cpuCount" => $app_config["total-cpu-count"],
						        "wallTimeLimit" => $app_config["wall-time-limit"]
							);

		$experiment = Utilities::get_experiment($_GET['expId']);
		$project = Utilities::get_project($experiment->projectID);

		$expVal = Utilities::get_experiment_values( $experiment, $project);
		$computeResources = Utilities::create_compute_resources_select($experiment->applicationId, $expVal['scheduling']->resourceHostId);

		$experimentInputs = array(	
								"disabled" => ' ',
						        "experimentName" => $experiment->name,
						        "experimentDescription" => $experiment->description,
						        "application" => $experiment->applicationId,
						      	"allowedFileSize" => $app_config["server-allowed-file-size"],
								'experiment' => $experiment,
								"queueDefaults" => $queueDefaults,
								'project' => $project,
								'expVal' => $expVal,
								'cloning' => true,
						        'advancedOptions' => $app_config["advanced-experiment-options"],
						        'computeResources' => $computeResources,
						        "resourceHostId" => $expVal['scheduling']->resourceHostId,
								'project' => $project,
								'expVal' => $expVal,
								'cloning' => true,
						        'advancedOptions' => $app_config["advanced-experiment-options"]
								);
		return View::make("experiment/edit", array("expInputs" => $experimentInputs) );
	}

	public function editSubmit()
	{
		if (isset($_POST['save']) || isset($_POST['launch']))
		{
	        $experiment = Utilities::get_experiment(Input::get('expId') ); // update local experiment variable
		    $updatedExperiment = Utilities::apply_changes_to_experiment($experiment, Input::all() );

		    Utilities::update_experiment($experiment->experimentID, $updatedExperiment);

		    if (isset($_POST['save']))
		    {
		        $experiment = Utilities::get_experiment(Input::get('expId') ); // update local experiment variable
		    }
		    if (isset($_POST['launch']))
		    {
		        Utilities::launch_experiment($experiment->experimentID);
		    }

			return Redirect::to('experiment/summary?expId=' . $experiment->experimentID);
		}
		else
			return View::make("home");
	}

	public function searchView()
	{
		$experimentStates = Utilities::getExpStates();
		return View::make("experiment/search", array( "expStates" => $experimentStates ) );
	}

	public function searchSubmit()
	{
		$expContainer = Utilities::get_expsearch_results( Input::all() );

		$experimentStates = Utilities::getExpStates();
		return View::make('experiment/search', array(
													'expStates' => $experimentStates,
													'expContainer' => $expContainer 
												));
	}

	public function getQueueView()
	{
		$queues = Utilities::getQueueDatafromResourceId( Input::get("crId"));
		$app_config = Utilities::read_config();
		$queueDefaults = array( "queueName" => $app_config["queue-name"],
						        "nodeCount" => $app_config["node-count"],
						        "cpuCount" => $app_config["total-cpu-count"],
						        "wallTimeLimit" => $app_config["wall-time-limit"]
							);
		return View::make("partials/experiment-queue-block", array( "queues" => $queues, "queueDefaults" => $queueDefaults) );
	}
}

?>
