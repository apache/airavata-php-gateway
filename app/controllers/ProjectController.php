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
        return View::make("project/create", array("users" => json_encode($users)));
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

            $users = SharingUtilities::getProfilesForSharedUsers(Input::get('projId'), ResourceType::PROJECT);

            return View::make("project/summary",
                array("projectId" => Input::get("projId"), "users" => json_encode($users)));
        } else
            return Redirect::to("home");
    }

    public function editView()
    {
        if (Input::has("projId")) {
            $users = SharingUtilities::getProfilesForSharedUsers(Input::get('projId'), ResourceType::PROJECT);

            return View::make("project/edit",
                array("projectId" => Input::get("projId"),
                    "project" => ProjectUtilities::get_project($_GET['projId']),
                     "users" => json_encode($users)
                ));
        } else
            return Redirect::to("home");
    }

    public function editSubmit()
    {
        if (isset($_POST['save'])) {
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

        return View::make('project/browse', array(
            'pageNo' => $pageNo,
            'limit' => $this->limit,
            'projects' => $projects
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
        if (array_key_exists('resourceId', $_GET)) {
            return Response::json(SharingUtilities::getProfilesForSharedUsers($_GET['resourceId'], ResourceType::PROJECT));
        }
        else {
            return Response::json(array("error" => "Error: No project specified"));
        }
    }

    public function unsharedUsers()
    {
        if (array_key_exists('resourceId', $_GET)) {
            return Response::json(SharingUtilities::getProfilesForUnsharedUsers($_GET['resourceId'], ResourceType::PROJECT));
        }
        else {
            return Response::json(array("error" => "Error: No project specified"));
        }
    }
}

?>
