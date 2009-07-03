<?php

class ActiveString
{
	/*static*/ function defaults(/*...*/)
	{
		$values = func_get_args();
		foreach( $values as $value )
		{
			if( strlen($value) > 0 )
			{
				return $value;
			}
		}
	}
		
	/*static*/ function before($str, $what)
	{
		$index = strpos($str, $what);
		if( $index !== false )
		{
			return substr($str, 0, $index);
		}
		return $str;
	}
	
	/*static*/ function after($str, $what)
	{
		$index = strpos($str, $what);
		if( $index !== false )
		{
			return substr($str, $index + 1);
		}
		return '';
	}
	
	/*static*/ function endsWith($str, $with)
	{
		$len = strlen($with);
		return ( substr($str, -$len) == $with );
	}
	
	/*static*/ function escapeMagicQuotes($str)
	{
		if( get_magic_quotes_gpc() )
		{
			return stripslashes($str);
		}
		return $str;
	}
}

?>