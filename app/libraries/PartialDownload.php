<?php 
/*

The following byte serving code is (C) 2004 Razvan Florian. You may find the latest version at 
http://www.coneural.org/florian/papers/04_byteserving.php

*/
function set_range($range, $filesize, &$first, &$last){
  /*
  Sets the first and last bytes of a range, given a range expressed as a string 
  and the size of the file.

  If the end of the range is not specified, or the end of the range is greater 
  than the length of the file, $last is set as the end of the file.

  If the begining of the range is not specified, the meaning of the value after 
  the dash is "get the last n bytes of the file".

  If $first is greater than $last, the range is not satisfiable, and we should 
  return a response with a status of 416 (Requested range not satisfiable).

  Examples:
  $range='0-499', $filesize=1000 => $first=0, $last=499 .
  $range='500-', $filesize=1000 => $first=500, $last=999 .
  $range='500-1200', $filesize=1000 => $first=500, $last=999 .
  $range='-200', $filesize=1000 => $first=800, $last=999 .

  */
  $dash=strpos($range,'-');
  $first=trim(substr($range,0,$dash));
  $last=trim(substr($range,$dash+1));
  if ($first=='') {
    //suffix byte range: gets last n bytes
    $suffix=$last;
    $last=$filesize-1;
    $first=$filesize-$suffix;
    if($first<0) $first=0;
  } else {
    if ($last=='' || $last>$filesize-1) $last=$filesize-1;
  }
  if($first>$last){
    //unsatisfiable range
    header("Status: 416 Requested range not satisfiable");
    header("Content-Range: */$filesize");
    exit;
  }
}

function buffered_read($file, $bytes, $buffer_size=1024){
  /*
  Outputs up to $bytes from the file $file to standard output, $buffer_size bytes at a time.
  */
  $bytes_left=$bytes;
  while($bytes_left>0 && !feof($file)){
    if($bytes_left>$buffer_size)
      $bytes_to_read=$buffer_size;
    else
      $bytes_to_read=$bytes_left;
    $bytes_left-=$bytes_to_read;
    $contents=fread($file, $bytes_to_read);
    echo $contents;
    flush();
  }
}

function byteserve($filename){
  /*
  Byteserves the file $filename.  

  When there is a request for a single range, the content is transmitted 
  with a Content-Range header, and a Content-Length header showing the number 
  of bytes actually transferred.

  When there is a request for multiple ranges, these are transmitted as a 
  multipart message. The multipart media type used for this purpose is 
  "multipart/byteranges".
  */

  $fileext = strtoupper( pathinfo($filename, PATHINFO_EXTENSION) );
  $filesize=filesize($filename);
  $file=fopen($filename,"rb");

  $ranges=NULL;
  if ($_SERVER['REQUEST_METHOD']=='GET' && isset($_SERVER['HTTP_RANGE']) && $range=stristr(trim($_SERVER['HTTP_RANGE']),'bytes=')){
    $range=substr($range,6);
    $boundary='g45d64df96bmdf4sdgh45hf5';//set a random boundary
    $ranges=explode(',',$range);
  }

  if($ranges && count($ranges)){
    header("HTTP/1.1 206 Partial content");
    header("Accept-Ranges: bytes");
    if(count($ranges)>1){
      /*
      More than one range is requested. 
      */

      //compute content length
      $content_length=0;
      foreach ($ranges as $range){
        set_range($range, $filesize, $first, $last);
        $content_length+=strlen("\r\n--$boundary\r\n");
if ($fileext=="PDF")
        $content_length+=strlen("Content-type: application/pdf\r\n");
else
        $content_length+=strlen("Content-type: application/octet-stream\r\n");
        $content_length+=strlen("Content-range: bytes $first-$last/$filesize\r\n\r\n");
        $content_length+=$last-$first+1;          
      }
      $content_length+=strlen("\r\n--$boundary--\r\n");

      //output headers
      header("Content-Length: $content_length");
      //see http://httpd.apache.org/docs/misc/known_client_problems.html for an discussion of x-byteranges vs. byteranges
      header("Content-Type: multipart/x-byteranges; boundary=$boundary");

      //output the content
      foreach ($ranges as $range){
        set_range($range, $filesize, $first, $last);
        echo "\r\n--$boundary\r\n";
if ($fileext=="PDF")
        echo "Content-type: application/pdf\r\n";
else
        echo "Content-type: application/octet-stream\r\n";
        echo "Content-range: bytes $first-$last/$filesize\r\n\r\n";
        fseek($file,$first);
        buffered_read ($file, $last-$first+1);          
      }
      echo "\r\n--$boundary--\r\n";
    } else {
      /*
      A single range is requested.
      */
      $range=$ranges[0];
      set_range($range, $filesize, $first, $last);  
      header("Content-Length: ".($last-$first+1) );
      header("Content-Range: bytes $first-$last/$filesize");
if ($fileext=="PDF")
      header("Content-Type: application/pdf");  
else
      header("Content-Type: application/octet-stream");  
      fseek($file,$first);
      buffered_read($file, $last-$first+1);
    }
  } else{
    //no byteserving
    header("Accept-Ranges: bytes");
    header("Content-Length: $filesize");
if ($fileext=="PDF")
    header("Content-Type: application/pdf");  
else
    header("Content-Type: application/octet-stream");
    readfile($filename);
  }
  fclose($file);
}

function serve($filename, $download=0){
  //Just serves the file without byteserving
  //if $download=true, then the save file dialog appears
  $filesize=filesize($filename);
  header("Content-Length: $filesize");
  header("Content-Type: application/octet-stream");
  $filename_parts=pathinfo($filename);
  if($download) header('Content-disposition: attachment; filename='.$filename_parts['basename']);
  readfile($filename);
}

//unset magic quotes; otherwise, file contents will be modified
//set_magic_quotes_runtime(0);

//do not send cache limiter header
//ini_set('session.cache_limiter','none');

