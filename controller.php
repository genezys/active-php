<?php
require_once dirname(__FILE__) . '/request.php';
require_once dirname(__FILE__) . '/response.php';

class Controller
{
	/*static*/ function dispatch($routes)
	{
		Response::contentType('text/plain');
		
		$currentMethod = Request::method();
		$currentPathInfo = Request::pathInfo();
		
		foreach( $routes as $route => $handler )
		{
			$method = Utils::substrBefore($route, ' ');
			$pathInfo = Utils::strSplit(trim(Utils::substrAfter($route, ' '), '/'), '/');
			
			if( $method == $currentMethod )
			{
				$params = Controller::_tryExtractParams($pathInfo, $currentPathInfo);
				if( $params !== false ) 
				{
					call_user_func($handler, $params);
					exit();
				}
			}
		}

		Response::status(404);
		echo 'Not Found';
	}
	
	/*static*/ function authenticateBasic($handler, $realm)
	{
		$user = Request::user();
		$password = Request::password();
		if( call_user_func($handler, $user, $password) === true )
		{
			return true;
		}
		else
		{
			Response::authenticate('Basic', $realm);
			exit();
		}	
	}
	
	/*private*/
	
	/*static*/ function _tryExtractParams($pathInfo, $currentPathInfo)
	{
		if( count($pathInfo) != count($currentPathInfo) ) 
		{
			return false;
		}
		$diff = array_diff_assoc($pathInfo, $currentPathInfo);
		// Test if all difference are actual params
		$params = array();
		foreach( $diff as $index => $param )
		{
			if( substr($param, 0, 1) != ':' )
			{
				return false;
			}
			$params[$param] = $currentPathInfo[$index];
		}
		return $params;
	}
	
	/*static*/ function _compareParams($param, $currentParam)
	{
		if( substr($param, 0, 1) == ':' )
		{
			return 0;
		}
		return strcmp($param, $currentParam);
	}
}

?>