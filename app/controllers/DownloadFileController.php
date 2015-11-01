<?php

class DownloadFileController extends Controller {

    public function __construct()
    {
        $this->beforeFilter('verifylogin');
        $this->beforeFilter('verifyauthorizeduser');
    }

    public function downloadFile(){
        if(Input::has("filePath") ){
            $filePath = Input::get("filePath");
            $file= Config::get('pga_config.airavata')["data-archive-path"] . "/" . Session::get("username") . "/" . $filePath;
            $headers = array(
                'application/octet-stream',
            );
            return Response::download($file, 'gaussian.in.com', $headers);
        }
    }
}