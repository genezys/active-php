<?php
require_once(dirname(__FILE__) . '/request.php');
require_once(dirname(__FILE__) . '/response.php');

class Controller
{
	var $METHODS = array('get', 'post', 'put', 'delete');

	function Controller()
	{
		Response::contentType('text/plain');
		var_dump(Request::method());
		var_dump(Request::script());
		var_dump(Request::scriptDir());
		var_dump(Request::uri());
		var_dump(Request::pathInfo());
		var_dump(Request::query());
	}
}

?>