<?php

class ActiveUtils
{
	/*static*/ function arrayGet($array, $key, $default = null)
	{
		return isset($array[$key]) ? $array[$key] : $default;
	}

	/*static*/ function realPath($path)
	{
		return str_replace(realpath($path), '\\', '/');
	}
	
	/*static*/ function relativePath($file, $path)
	{
		return substr(ActiveUtils::realPath($path), strlen(dirname(ActiveUtils::realPath($file))) + 1);
	}
}

?>