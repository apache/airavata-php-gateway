<?php
class RBase64
{
	public static function encode($string){
	    $string = base64_encode($string);
	    $reversed_string="";
	    $string_length = strlen($string);

	    for($i=$string_length-1;$i>-1;$i--)
	    {
	        if ($string[$i]==='=')
	           $reversed_string .= '_';
	        else
	           $reversed_string .= $string[$i];
	    }
	    return $reversed_string;
	}


	public static function decode($rstring){
	    $string="";
	    $string_length = strlen($rstring);
	
	    for($i=$string_length-1;$i>-1;$i--)
	    {
	        if ($rstring[$i]==='_')
	           $string .= '=';
	        else
	           $string .= $rstring[$i];
	    }
	
	    return base64_decode($string);
	}
}
?>
