<?php

class ActiveUtils
{
	/*static*/ function arrayGet($array, $key, $default = null)
	{
		return isset($array[$key]) ? $array[$key] : $default;
	}

	/*static*/ function realPath($path)
	{
		return str_replace('\\', '/', realpath($path));
	}
	
	/*static*/ function dirName($path)
	{
		return dirname(ActiveUtils::realPath($file));
	}
}

?>