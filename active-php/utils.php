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
	
	/*static*/ function relativePath($file, $path)
	{
		$pathBase = dirname(ActiveUtils::realPath($file));
		$fullPath = ActiveUtils::realPath($path);
		return substr($fullPath, strlen($pathBase) + 1);
	}
}

?>