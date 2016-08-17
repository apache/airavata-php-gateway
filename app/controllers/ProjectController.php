<?php

use Airavata\Model\Group\ResourceType;

class ProjectController extends BaseController
{

    /**
     * Limit used in fetching paginated results
     * @var int
     */
    var $limit = 20;

    /**
     *    Instantiate a new ProjectController Instance
     **/

    public function __construct()
    {
        $this->beforeFilter('verifylogin');
        $this->beforeFilter('verifyauthorizeduser');
        Session::put("nav-active", "project");

    }

    public function createView()
    {
        $users = SharingUtilities::getAllUserProfiles();
        return View::make("project/create", array("users" => json_encode($users), "owner" => json_encode(array())));
    }

    public function createSubmit()
    {
        if (isset($_POST['save'])) {
            $projectId = ProjectUtilities::create_project();
            return Redirect::to('project/summary?projId=' . $projectId);
        } else {
            return Redirect::to('project/create');
        }
    }

    public function summary()
    {
        if (Input::has("projId")) {
            Session::put("projId", Input::get("projId"));

            $project = ProjectUtilities::get_project(Input::get('projId'));

            $users = SharingUtilities::getProfilesForSharedUsers(Input::get('projId'), ResourceType::PROJECT);

            $owner = array();
            if (strcmp(Session::get("username"), $project->owner) !== 0) {
                $owner[$project->owner] = $users[$project->owner];
                $users = array_diff_key($users, $owner);
            }

            $experiments = ProjectUtilities::get_experiments_in_project(Input::get("projId"));

            $experiment_can_write = array();
            foreach($experiments as $experiment) {
                if (SharingUtilities::userCanWrite(Session::get("username"), $experiment->experimentId, ResourceType::EXPERIMENT)) {
                    $experiment_can_write[$experiment->experimentId] = true;
                }
                else {
                    $experiment_can_write[$experiment->experimentId] = false;
                }
            }

            return View::make("project/summary",
                array("projectId" => Input::get("projId"),
                      "experiments" => $experiments,
                      "users" => json_encode($users),
                      "owner" => json_encode($owner),
                      "project_can_write" => SharingUtilities::userCanWrite(Session::get("username"), Input::get("projId"), ResourceType::PROJECT),
                      "experiment_can_write" => $experiment_can_write
                  ));
        } else
            return Redirect::to("home");
    }

    public function editView()
    {
        if (Input::has("projId")) {
            if (SharingUtilities::userCanWrite(Session::get("username"), Input::get("projId"), ResourceType::PROJECT)) {
                $project = ProjectUtilities::get_project($_GET['projId']);
                $users = SharingUtilities::getProfilesForSharedUsers(Input::get('projId'), ResourceType::PROJECT);
                $owner = array();

                if (strcmp(Session::get("username"), $project->owner) !== 0) {
                    $owner[$project->owner] = $users[$project->owner];
                    $users = array_diff_key($users, $owner);
                }

                return View::make("project/edit",
                    array("projectId" => Input::get("projId"),
                        "project" => $project,
                        "users" => json_encode($users),
                        "owner" => json_encode($owner)
                    ));
                }
            else {
                return Redirect::to('project/summary?projId=' . Input::get("projId"))->with("error", "You do not have permission to edit this project.");
            }
        } else
            return Redirect::to("home");
    }

    public function editSubmit()
    {
        if (isset($_POST['save']) && SharingUtilities::userCanWrite(Session::get("username"), Input::get("projectId"), ResourceType::PROJECT)) {
            $projectDetails = array();
            $projectDetails["owner"] = Session::get("username");
            $projectDetails["name"] = Input::get("project-name");
            $projectDetails["description"] = Input::get("project-description");

            ProjectUtilities::update_project(Input::get("projectId"), $projectDetails);

            return Redirect::to("project/summary?projId=" . Input::get("projectId"))->with("project_edited", true);
        }
    }

    public function browseView()
    {
        $pageNo = Input::get('pageNo');
        $prev = Input::get('prev');
        $isSearch = Input::get('search');
        if (empty($pageNo) || isset($isSearch) ) {
            $pageNo = 1;
        } else {
            if (isset($prev)) {
                $pageNo -= 1;
            } else {
                $pageNo += 1;
            }
        }

        $searchValue = Input::get("search-value");
        if(!empty($searchValue)){
            $projects = ProjectUtilities::get_proj_search_results_with_pagination(Input::get("search-key"),
                Input::get("search-value"), $this->limit, ($pageNo - 1) * $this->limit);
        }else{
            $projects = ProjectUtilities::get_all_user_accessible_projects_with_pagination($this->limit, ($pageNo - 1) * $this->limit);
        }

        $can_write = array();
        $user = Session::get("username");
        foreach($projects as $project) {
            if (SharingUtilities::userCanWrite($user, $project->projectID, ResourceType::PROJECT)) {
                $can_write[$project->projectID] = true;
            }
            else {
                $can_write[$project->projectID] = false;
            }
        }

        return View::make('project/browse', array(
            'pageNo' => $pageNo,
            'limit' => $this->limit,
            'projects' => $projects,
            'can_write' => $can_write
        ));
    }

    /**
     * Generate JSON containing permissions information for this project.
     *
     * This function retrieves the user profile and permissions for every user
     * other than the client that has access to the project. In the event that
     * the project does not exist, return an error message.
     */
    public function sharedUsers()
    {
        if (Session::has("authz-token") && array_key_exists('resourceId', $_GET)) {
            return Response::json(SharingUtilities::getProfilesForSharedUsers($_GET['resourceId'], ResourceType::PROJECT));
        }
        else {
            return Response::json(array("error" => "Error: No project specified"));
        }
    }

    public function unsharedUsers()
    {
        if (Session::has("authz-token") && array_key_exists('resourceId', $_GET)) {
            return Response::json(SharingUtilities::getProfilesForUnsharedUsers($_GET['resourceId'], ResourceType::PROJECT));
        }
        else {
            return Response::json(array("error" => "Error: No project specified"));
        }
    }
}

?>
