<?php

class GroupController extends BaseController {
    public function __construct()
    {
        $this->beforeFilter('verifylogin');
        $this->beforeFilter('verifyauthorizeduser');
        Session::put("nav-active", "group");
    }

    public function createView()
    {
        return View::make("group/create");
    }

    public function createSubmit()
    {
        // TODO: Write submission logic
    }

    public function editView()
    {
        // TODO: Write logic to load current group members
    }

    public function summaryView()
    {
        // TODO: Write group display logic
    }

}

?>
