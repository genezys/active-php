<?php

class ActiveUtils
{
	/*static*/ function arrayGet($map, $key, $default = null)
	{
		return isset($map[$key]) ? $map[$key] : $default;
	}
}

?>