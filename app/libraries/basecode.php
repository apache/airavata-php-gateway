<?php
class Base32
{
	const BITS_5_RIGHT = 31;
	protected static $CHARS = '0123456789abcdefghijklmnopqrstuv';
	
	public static function encode($data)
	{
		$dataSize = strlen($data);
		$res = '';
		$remainder = 0;
		$remainderSize = 0;
		
		for ($i = 0; $i < $dataSize; $i++)
		{
			$b = ord($data[$i]);
			$remainder = ($remainder << 8) | $b;
			$remainderSize += 8;
			while ($remainderSize > 4)
			{
				$remainderSize -= 5;
				$c = $remainder & (self::BITS_5_RIGHT << $remainderSize);
				$c >>= $remainderSize;
				$res .= self::$CHARS[$c];
			}
		}
		if ($remainderSize > 0)
		{
			// remainderSize < 5:
			$remainder <<= (5 - $remainderSize);
			$c = $remainder & self::BITS_5_RIGHT;
			$res .= self::$CHARS[$c];
		}
		
		return $res;
	}
	
	public static function decode($data)
	{
		$data = strtolower($data);
		$dataSize = strlen($data);
		$buf = 0;
		$bufSize = 0;
		$res = '';
		
		for ($i = 0; $i < $dataSize; $i++)
		{
			$c = $data[$i];
			$b = strpos(self::$CHARS, $c);
			if ($b === false)
				throw new Exception('Encoded string is invalid, it contains unknown char #'.ord($c));
			$buf = ($buf << 5) | $b;
			$bufSize += 5;
			if ($bufSize > 7)
			{
				$bufSize -= 8;
				$b = ($buf & (0xff << $bufSize)) >> $bufSize;
				$res .= chr($b);
			}
		}
		
		return $res;
	}
}

function rbase64_encode($string){
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


function rbase64_decode($rstring){
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


?>
