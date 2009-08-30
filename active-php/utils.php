<?php

class ActiveUtils
{
	/*static*/ function arrayGet($array, $key, $default = null)
	{
		return isset($array[$key]) ? $array[$key] : $default;
	}
	
	/*static*/ function relativePath($file, $path)
	{
		return substr(realpath($path), strlen(dirname(realpath($file))) + 1);
	}
}

?>