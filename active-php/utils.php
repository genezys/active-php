<?php

class ActiveUtils
{
	/*static*/ function arrayGet($array, $key, $default = null)
	{
		return isset($array[$key]) ? $array[$key] : $default;
	}
}

?>