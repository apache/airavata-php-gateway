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

    public static function gbrowser($filelist ){
        $protocol = 'http';
        if ( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') 
           $protocol = 'https';

        include("basecode.php");

        $dataRoot = Config::get("pga_config.airavata")["experiment-data-absolute-path"];
        $filelist = explode("\n", RBase64::decode( $filelist ) );
        $folder_path=$filelist[0]. "ARCHIVE" ;
        $content = "[ \n";

        for($i=1; $i<3; $i++){    
            $content = $content . ' {
            type:"bigwig",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode($filelist[$i*2]). '",
            name: "'. $filelist[$i*2-1] .'",
            #fixedscale:{min:0,max:20},
            colorpositive:"#B30086",
            colornegative:"#0000e5",
            height:100,
            mode: "show",
            },'. "\n" ;
        }

        $content = $content . '{
            type:"bedgraph",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode($folder_path . '/out.dREG.pred.gz').'",
            name: "dREG informative pos.:",
            mode: "show",
            colorpositive:"#B30086",
            colornegative:"#0000e5",
            backgroundcolor:"#ffffe5",
            height:30,
            #fixedscale:{min:0, max:1},
        },'. "\n";

        $content = $content . '{
            type:"bedgraph",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode( $folder_path . '/out.dREG.peak.gz').'",
            name: "dREG Peak Calling:",
            mode: "show",
            colorpositive:"#B30086",
            colornegative:"#0000e5",
            backgroundcolor:"#ffffe5",
            height:30,
            #fixedscale:{min:0, max:1},
        },'. "\n";

        $content = $content . '{
            type:"bigwig",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode( $folder_path . '/out.dREG.HD.imputedDnase.bw').'",
            name: "imputed DNase-I signal:",
            #fixedscale:{min:0,max:20},
            colorpositive:"#00B306",
            height:100,
            mode: "show",
        },'. "\n";

        $content = $content . '{
            type:"bedgraph",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode( $folder_path . '/out.dREG.HD.relaxed.bed.gz').'",
            name: "dREG.HD relaxed peaks:",
            mode: "show",
            colorpositive:"#0000e5/#B30086",
            backgroundcolor:"#ffffe5",
            height:30,
            fixedscale:{min:0, max:1},
        },'. "\n";

        $content = $content . '{
            type:"bedgraph",
            url:"'.$protocol.'://'. $_SERVER['HTTP_HOST'] .'/gbfile/'.RBase64::encode( $folder_path . '/out.dREG.HD.stringent.bed.gz').'",
            name: "dREG.HD stringent peaks:",
            mode: "show",
            colorpositive:"#0000e5/#B30086",
            backgroundcolor:"#ffffe5",
            height:30,
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
