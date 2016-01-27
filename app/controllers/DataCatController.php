<?php

class DataCatController extends BaseController
{

    public function select()
    {
        $results = json_decode(file_get_contents('http://localhost:9000/query-api/select?q=sddslfnlsdf'), true);
        if(!isset($results) || empty($results)){
            $results = array();
        }
//        var_dump($results);exit;
        return View::make('datacat/select', array("results" => $results));
    }

}
