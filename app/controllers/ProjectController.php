<?php

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
        Session::put("nav-active", "project");

    }

    public function createView()
    {
        return View::make("project/create");
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
            return View::make("project/summary",
                array("projectId" => Input::get("projId")));
        } else
            return Redirect::to("home");
    }

    public function editView()
    {
        if (Input::has("projId")) {
            return View::make("project/edit",
                array("projectId" => Input::get("projId"),
                    "project" => ProjectUtilities::get_project($_GET['projId'])
                ));
        } else
            return Redirect::to("home");
    }

    public function editSubmit()
    {
        if (isset($_POST['save'])) {
            $projectDetails["owner"] = Session::get("username");
            $projectDetails["name"] = Input::get("project-name");
            $projectDetails["description"] = Input::get("project-description");

            ProjectUtilities::update_project(Input::get("projectId"), $projectDetails);

            return Redirect::to("project/summary?projId=" . Input::get("projectId"))->with("project_edited", true);
        }
    }

    public function searchView()
    {
        return View::make('project/search');
    }

    public function searchSubmit()
    {
        $search = Input::get('search');
        if (isset($search)) {
            $pageNo = 1;
        } else {
            $pageNo = Input::get('pageNo');
            $prev = Input::get('prev');
            if (empty($pageNo)) {
                $pageNo = 1;
            } else {
                if (isset($prev)) {
                    $pageNo -= 1;
                } else {
                    $pageNo += 1;
                }
            }
        }

        $projects = ProjectUtilities::get_projsearch_results_with_pagination(Input::get("search-key"),
            Input::get("search-value"), $this->limit, ($pageNo - 1) * $this->limit);
        return View::make('project/search', array(
                'pageNo' => $pageNo,
                'limit' => $this->limit,
                'projects' => $projects)
        );
    }

    public function browseView()
    {
        $pageNo = Input::get('pageNo');
        $prev = Input::get('prev');
        if (empty($pageNo)) {
            $pageNo = 1;
        } else {
            if (isset($prev)) {
                $pageNo -= 1;
            } else {
                $pageNo += 1;
            }
        }

        $projects = ProjectUtilities::get_all_user_projects_with_pagination($this->limit, ($pageNo - 1) * $this->limit);
        return View::make('project/browse', array(
            'pageNo' => $pageNo,
            'limit' => $this->limit,
            'projects' => $projects
        ));
    }

}

?>
