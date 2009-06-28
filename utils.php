<?php

class Utils
{
	/*static*/ function arrayGet($a, $index, $default = null)
	{
		return sizeof($a) > $index ? $a[$index] : $default;
	}

	/*static*/ function mapGet($map, $key, $default = null)
	{
		return isset($map[$key]) ? $map[$key] : $default;
	}
	
	/*static*/ function strDefault()
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
	
	/*static*/ function strSplit($str, $separator)
	{
		if( strlen($str) == 0 )
		{
			return array();
		}
		return explode($separator, $str);
	}
	
	/*static*/ function substrBefore($str, $what)
	{
		$index = strpos($str, $what);
		if( $index !== false )
		{
			return substr($str, 0, $index);
		}
		return $str;
	}
	
	/*static*/ function substrAfter($str, $what)
	{
		$index = strpos($str, $what);
		if( $index !== false )
		{
			return substr($str, $index + 1);
		}
		return '';
	}
	
	/*static*/ function strEndsWith($str, $with)
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

	/*static*/ function cutInTwo($str, $separator, &$str1, &$str2)
	{
		$parts = explode($separator, $str);
		$str1 = Utils::ArrayGetAt($parts, 0);
		$str2 = Utils::ArrayGetAt($parts, 1);
	}

	/*static*/ function arrayDiffSameSize($array1, $array2)
	{
		$size1 = sizeof($array1);
		$size2 = sizeof($array2);

		if( $size1 !== $size2 )
		{ 
			return false;
		}

		$diff = array();

		for( $i = 0; $i < $size1; ++$i )
		{
			$value1 = $array1[$i];
			$value2 = $array2[$i];

			if( $value1 !== $value2 )
			{
				$diff[$i] = array($value1, $value2);
			}
		}
		return $diff;
	}
}

?>