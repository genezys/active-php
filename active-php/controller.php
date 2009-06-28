<?php
require_once dirname(__FILE__).'/request.php';
require_once dirname(__FILE__).'/response.php';

class Controller
{
	static $encoding;
	static $views;
	static $routes = array();
	static $responseTypes = array(); 
	
	/*static*/ function route($method, $path, $handler)
	{
		Controller::$routes[] = array(
			'method' => $method,
			'path' => $path,
			'handler' => $handler,
		);
	}
	
	/*static*/ function dispatch()
	{
		$currentMethod = Request::method();
		$currentPathInfo = Request::pathInfo();
		
		foreach( Controller::$routes as $route )
		{
			$pathInfo = Utils::strSplit(trim($route['path'], '/'), '/');
			
			if( strcasecmp($route['method'], $currentMethod) == 0 )
			{
				$params = Controller::_tryExtractParams($pathInfo, $currentPathInfo);
				if( $params !== false ) 
				{
					call_user_func($route['handler'], $params);
					return;
				}
			}
		}

		Response::status(404);
		echo Response::messageFromStatus(404);
	}
	
	/*static*/ function authenticateBasic($realm, $handler)
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
	
	/*static*/ function encoding($encoding)
	{
		Controller::$encoding = $encoding;
	}
	
	/*static*/ function views($fileRelativeTo, $path)
	{
		Controller::$views = dirname($fileRelativeTo).'/'.$path.'/'.basename($fileRelativeTo, '.php').'.';
	}
	
	/*static*/ function respondWith($extension, $mime, $handler)
	{
		Controller::_addResponseType($extension, $mime, array('handler' => $handler));
	}
	
	/*static*/ function respondWithView($extension, $mime)
	{
		Controller::_addResponseType($extension, $mime, 
			array('view' => Controller::$views.$extension.'.php')
		);
	}
	
	/*static*/ function respond($values)
	{
		$pathInfo = Request::pathInfo();
		$lastPathInfoPart = end($pathInfo);
		$types = Request::types();
		
		
		$registeredTypes = array();
		foreach( Controller::$responseTypes as $responseType ) 
		{
			// Extension overrides any other option
			if( Utils::strEndsWith($lastPathInfoPart, '.'.$responseType['extension']) ) 
			{
				$registeredTypes = array($responseType);
				break;
			}
			// If type is handled by the browser, we will keep it
			$priority = Controller::_typeHandled($types, $responseType['mime']);
			if( $priority !== false ) 
			{
				$registeredTypes[$priority] = $responseType;
			}
		}
		
		// Sort based on browser preferences
		krsort($registeredTypes);
		// Use prefered type to respond
		$preferedType = reset($registeredTypes);
		// Response
		Response::contentType($preferedType['mime'], Controller::$encoding);
		if( isset($preferedType['view']) ) 
		{
			include $preferedType['view'];
		}
		else 
		{
			call_user_func($preferedType['handler'], $values);
		}
	}
	
	/*private*/
	
	/*static*/ function _addResponseType($extension, $mime, $response)
	{
		Controller::$responseTypes[] = array_merge(array(
			'mime' => $mime,
			'extension' => $extension,
		), $response);
	}
	
	/*static*/ function _typeHandled($types, $mimeSearched)
	{
		foreach( $types as $priority => $mimes ) 
		{
			foreach( $mimes as $index => $mime ) 
			{
				if( $mime == '*/*' || $mime == $mimeSearched ) 
				{
					return $priority.''.(count($mimes) - $index);
				}
			}
		}
		return false;
	}
	
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
			$value = Utils::substrBefore($currentPathInfo[$index], '.');
			if( $param == $value )
			{
				continue;
			}
			if( substr($param, 0, 1) != ':' )
			{
				return false;
			}
			$params[$param] = $value;
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