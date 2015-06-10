<?php

use Airavata\API\Error\AiravataClientException;
use Airavata\API\Error\AiravataSystemException;
use Airavata\API\Error\InvalidRequestException;
use Airavata\Facades\Airavata;
use Airavata\Model\Workspace\Project;

class ProjectUtilities
{

    /**
     * Get all projects owned by the given user
     * @param $username
     * @return null
     */
    public static function get_all_user_projects($gatewayId, $username)
    {
        $userProjects = null;

        try {
            $userProjects = Airavata::getAllUserProjects($gatewayId, $username);
            //var_dump( $userProjects); exit;
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem getting the user\'s projects.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem getting the user\'s projects.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            if ($ase->airavataErrorType == 2) // 2 = INTERNAL_ERROR
            {
                CommonUtilities::print_warning_message('<p>You must create a project before you can create an experiment.
                Click <a href="' . URL::to('/') . '/project/create">here</a> to create a project.</p>');
            } else {
                CommonUtilities::print_error_message('<p>There was a problem getting the user\'s projects.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                    '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
            }
        }

        return $userProjects;
    }

    /**
     * Get the project with the given ID
     * @param $projectId
     * @return null
     */
    public static function get_project($projectId)
    {

        try {
            return Airavata::getProject($projectId);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem getting the project.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem getting the project.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem getting the project.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataSystemException!<br><br>' . $ase->getMessage() . '</p>');
        }

    }

    /**
     * Create a select input and populate it with project options from the database
     */
    public static function create_project_select($projectId = null, $editable = true)
    {
        $editable ? $disabled = '' : $disabled = 'disabled';
        $userProjects = ProjectUtilities::get_all_user_projects(Session::get("gateway_id"), Session::get('username'));

        echo '<select class="form-control" name="project" id="project" required ' . $disabled . '>';
        if (sizeof($userProjects) > 0) {
            foreach ($userProjects as $project) {
                if ($project->projectID == $projectId) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }

                echo '<option value="' . $project->projectID . '" ' . $selected . '>' . $project->name . '</option>';
            }
        }
        echo '</select>';
        if (sizeof($userProjects) == 0) {
            CommonUtilities::print_warning_message('<p>You must create a project before you can create an experiment.
                Click <a href="' . URL::to('/') . '/project/create">here</a> to create a project.</p>');
        }
    }

    //moved from create project view.

    public static function create_project()
    {
        $project = new Project();
        $project->owner = Session::get('username');
        $project->name = $_POST['project-name'];
        $project->description = $_POST['project-description'];


        $projectId = null;

        try {
            $projectId = Airavata::createProject(Config::get('pga_config.airavata')['gateway-id'], $project);

            if ($projectId) {
                CommonUtilities::print_success_message("<p>Project {$_POST['project-name']} created!</p>" .
                    '<p>You will be redirected to the summary page shortly, or you can
                    <a href="project/summary?projId=' . $projectId . '">go directly</a> to the project summary page.</p>');
            } else {
                CommonUtilities::print_error_message("Error creating project {$_POST['project-name']}!");
            }
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('AiravataSystemException!<br><br>' . $ase->getMessage());
        }

        return $projectId;
    }

    /**
     * Get experiments in project
     * @param $projectId
     * @return array|null
     */
    public static function get_experiments_in_project($projectId)
    {

        $experiments = array();

        try {
            $experiments = Airavata::getAllExperimentsInProject($projectId);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('AiravataSystemException!<br><br>' . $ase->getMessage());
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
        }

        return $experiments;
    }

    public static function update_project($projectId, $projectDetails)
    {

        $updatedProject = new Project();
        $updatedProject->owner = $projectDetails["owner"];
        $updatedProject->name = $projectDetails["name"];
        $updatedProject->description = $projectDetails["description"];

        try {
            Airavata::updateProject($projectId, $updatedProject);

            //Utilities::print_success_message('Project updated! Click <a href="project_summary.php?projId=' . $projectId . '">here</a> to view the project summary.');
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
        } catch (ProjectNotFoundException $pnfe) {
            CommonUtilities::print_error_message('ProjectNotFoundException!<br><br>' . $pnfe->getMessage());
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('AiravataSystemException!<br><br>' . $ase->getMessage());
        }
    }


    public static function get_all_user_projects_with_pagination($limit, $offset)
    {

        $projects = array();

        try {
            $projects = Airavata::getAllUserProjectsWithPagination(Session::get("gateway_id"),
                Session::get("username"), $limit, $offset);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
        } catch (AiravataSystemException $ase) {
            if ($ase->airavataErrorType == 2) // 2 = INTERNAL_ERROR
            {
                CommonUtilities::print_info_message('<p>You have not created any projects yet, so no results will be returned!</p>
                                <p>Click <a href="create_project.php">here</a> to create a new project.</p>');
            } else {
                CommonUtilities::print_error_message('There was a problem with Airavata. Please try again later, or report a bug using the link in the Help menu.');
                //print_error_message('AiravataSystemException!<br><br>' . $ase->airavataErrorType . ': ' . $ase->getMessage());
            }
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
        }

        return $projects;
    }


    public static function get_projsearch_results_with_pagination($searchKey, $searchValue, $limit, $offset)
    {

        $projects = array();

        try {
            switch ($searchKey) {
                case 'project-name':
                    $projects = Airavata::searchProjectsByProjectNameWithPagination(Session::get("gateway_id"),
                        Session::get("username"), $searchValue, $limit, $offset);
                    break;
                case 'project-description':
                    $projects = Airavata::searchProjectsByProjectDescWithPagination(Session::get("gateway_id"),
                        Session::get("username"), $searchValue, $limit, $offset);
                    break;
            }
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
        } catch (AiravataSystemException $ase) {
            if ($ase->airavataErrorType == 2) // 2 = INTERNAL_ERROR
            {
                CommonUtilities::print_info_message('<p>You have not created any projects yet, so no results will be returned!</p>
                                <p>Click <a href="create_project.php">here</a> to create a new project.</p>');
            } else {
                CommonUtilities::print_error_message('There was a problem with Airavata. Please try again later, or report a bug using the link in the Help menu.');
                //print_error_message('AiravataSystemException!<br><br>' . $ase->airavataErrorType . ': ' . $ase->getMessage());
            }
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
        }

        return $projects;
    }


    public static function get_projsearch_results($searchKey, $searchValue)
    {

        $projects = array();

        try {
            switch ($searchKey) {
                case 'project-name':
                    $projects = Airavata::searchProjectsByProjectName(Session::get("gateway_id"), Session::get("username"), $searchValue);
                    break;
                case 'project-description':
                    $projects = Airavata::searchProjectsByProjectDesc(Session::get("gateway_id"), Session::get("username"), $searchValue);
                    break;
            }
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
        } catch (AiravataSystemException $ase) {
            if ($ase->airavataErrorType == 2) // 2 = INTERNAL_ERROR
            {
                CommonUtilities::print_info_message('<p>You have not created any projects yet, so no results will be returned!</p>
                                <p>Click <a href="create_project.php">here</a> to create a new project.</p>');
            } else {
                CommonUtilities::print_error_message('There was a problem with Airavata. Please try again later, or report a bug using the link in the Help menu.');
                //print_error_message('AiravataSystemException!<br><br>' . $ase->airavataErrorType . ': ' . $ase->getMessage());
            }
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
        }

        return $projects;
    }

}