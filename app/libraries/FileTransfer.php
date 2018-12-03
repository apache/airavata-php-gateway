<?php

/*
|--------------------------------------------------------------------------
| File Transfer in dREG gateway (upload/download)
| @author: Zhong Wang <wzhy2000@hotmail.com>
|--------------------------------------------------------------------------
*/
/*
 * User Routes
*/
use \TusServer\TusServer;

class FileTransfer {

    public static function gbrowser_dREG($filelist ){
        $protocol = 'http';
        if ( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') 
           $protocol = 'https';

        include("basecode.php");

        $dataRoot = Config::get("pga_config.airavata")["experiment-data-absolute-path"];
        $filelist = explode("\n", RBase64::decode( $filelist ) );
        $folder_path=$filelist[0]. "ARCHIVE" ;
        $content = "[ \n";
        $out_prefix = $filelist[3];

        $content = $content . ' {
            type:"bigwig",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode($filelist[0].'/'.$filelist[1]). '",
            name: "'. $filelist[1] .'",
            #fixedscale:{min:0,max:20},
            summarymethod:"max",
            colorpositive:"#C5000B",
            colornegative:"#0084D1",
            height:100,
            mode: "show",
            },'. "\n" ;


       $content = $content . ' {
            type:"bigwig",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode($filelist[0].'/'.$filelist[2]). '",
            name: "'. $filelist[2] .'",
            #fixedscale:{min:0,max:20},
            summarymethod:"min",
            colorpositive:"#C5000B",
            colornegative:"#0084D1",
            height:100,
            mode: "show",
            },'. "\n" ;

        $content = $content . '{
            type:"bigwig",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode( $folder_path . '/'. $out_prefix .'.dREG.infp.bw').'",
            name: "dREG Info. Sites:",
            mode: "show",
            colorpositive:"#B30086",
            colornegative:"#0000e5",
            backgroundcolor:"#ffffe5",
            height:40,
            fixedscale:{min:0, max:1},
        },'. "\n";

        $content = $content . '{
            type:"bigwig",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode( $folder_path . '/'. $out_prefix .'.dREG.peak.score.bw').'",
            name: "dREG Peak Calling:",
            mode: "show",
            colorpositive:"#B30086",
            colornegative:"#0000e5",
            backgroundcolor:"#ffffe5",
            height:40,
            fixedscale:{min:0.2, max:1.0},
        },'. "\n";


        $content = $content . ']';
        return Response::make($content, 200)
                  ->header('Content-Type', 'text/plain');
    }

    public static function gbrowser_dTOX($filelist ){
        $protocol = 'http';
        if ( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
           $protocol = 'https';

        include("basecode.php");

        $dataRoot = Config::get("pga_config.airavata")["experiment-data-absolute-path"];
        $filelist = explode("\n", RBase64::decode( $filelist ) );
        $folder_path=$filelist[0]. "ARCHIVE" ;
        $content = "[ \n";
        $out_prefix = $filelist[3];

        $content = $content . ' {
            type:"bigwig",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode($filelist[0].'/'.$filelist[1]). '",
            name: "'. $filelist[1] .'",
            #fixedscale:{min:0,max:20},
            summarymethod:"max",
            colorpositive:"#C5000B",
            colornegative:"#0084D1",
            height:100,
            mode: "show",
            },'. "\n" ;


       $content = $content . ' {
            type:"bigwig",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode($filelist[0].'/'.$filelist[2]). '",
            name: "'. $filelist[2] .'",
            #fixedscale:{min:0,max:20},
            summarymethod:"min",
            colorpositive:"#C5000B",
            colornegative:"#0084D1",
            height:100,
            mode: "show",
            },'. "\n" ;

        $content = $content . '{
            type:"bigwig",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode( $folder_path . '/'. $out_prefix .'.dTOX.bound.bed.gz').'",
            name: "dTOX bound status:",
            mode: "show",
            colorpositive:"#B30086",
            colornegative:"#0000e5",
            backgroundcolor:"#ffffe5",
            height:40,
            fixedscale:{min:0, max:1},
        },'. "\n";


        $content = $content . ']';
        return Response::make($content, 200)
                  ->header('Content-Type', 'text/plain');
    }

    public static function gbfile($file ){
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $fileext = pathinfo($file, PATHINFO_EXTENSION);

        include("basecode.php");
        if( $fileext != "")
            $file = RBase64::decode( $filename ) .".".$fileext;
        else
            $file = RBase64::decode( $filename );

        if(0 === strpos($file, '/')){
            $file = substr($file, 1);
        }
    
        $downloadLink = Config::get('pga_config.airavata')['experiment-data-absolute-path'] . '/' . $file;
        if ( !file_exists($downloadLink) )
            return Response::make("", 204);
        else
        {
            if ($_SERVER["REQUEST_METHOD"]=="GET")
               return Response::download($downloadLink);
            else	
               return Response::make("", 200)
                  ->header('Content-Length', filesize($downloadLink));
        }
    }

    public static function upload( $file ){
        $request = \Illuminate\Http\Request::createFromGlobals();
        $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
	
        // Create and configure server
        $server = new TusServer($tmp_dir, $request, $debug = true );
        // Run server
        $server->process(true);
    }
}


?>
