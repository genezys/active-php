<?php
require_once dirname(__FILE__).'/request.php';
require_once dirname(__FILE__).'/response.php';

class ActiveController
{
	static $encoding;
	static $views;
	static $routes = array();
	static $responseTypes = array(); 
	static $format;
	
	/*static*/ function route($method, $path, $handler)
	{
		ActiveController::$routes[] = array(
			'method' => $method,
			'path' => $path,
			'handler' => $handler,
		);
	}
	
	/*static*/ function dispatch()
	{
		$currentMethod = ActiveRequest::method();
		$currentPathInfo = ActiveRequest::pathInfo();
		
		foreach( ActiveController::$routes as $route )
		{
			$pathInfo = trim($route['path'], '/');
			
			if( strcasecmp($route['method'], $currentMethod) == 0 )
			{
				$params = ActiveController::_tryExtractParams($pathInfo, $currentPathInfo);
				if( $params !== false ) 
				{
					ActiveController::$format = $params['format'];
					call_user_func($route['handler'], $params);
					return;
				}
			}
		}

		ActiveResponse::status(404);
		echo ActiveResponse::messageFromStatus(404);
	}
	
	/*static*/ function authenticateBasic($realm, $handler)
	{
		$user = ActiveRequest::user();
		$password = ActiveRequest::password();
		if( call_user_func($handler, $user, $password) === true )
		{
			return true;
		}
		else
		{
			ActiveResponse::authenticate('Basic', $realm);
			exit();
		}	
	}
	
	/*static*/ function encoding($encoding)
	{
		ActiveController::$encoding = $encoding;
	}
	
	/*static*/ function views($fileRelativeTo, $path)
	{
		ActiveController::$views = dirname($fileRelativeTo).'/'.$path.'/'.basename($fileRelativeTo, '.php').'.';
	}
	
	/*static*/ function respondWith($extension, $mime, $handler)
	{
		ActiveController::_addResponseType($extension, $mime, array('handler' => $handler));
	}
	
	/*static*/ function respondWithView($extension, $mime)
	{
		ActiveController::_addResponseType($extension, $mime, 
			array('view' => ActiveController::$views.$extension.'.php')
		);
	}
	
	/*static*/ function respond($values)
	{
		$pathInfo = ActiveRequest::pathInfo();
		$types = ActiveRequest::types();
		
		$registeredTypes = array();
		foreach( ActiveController::$responseTypes as $responseType ) 
		{
			if( strlen(ActiveController::$format) > 0 )
			{
				// Extension overrides any other option
				if( ActiveController::$format == $responseType['extension'] ) 
				{
					$registeredTypes = array($responseType);
					break;
				}
			}
			else 
			{
				// If type is handled by the browser, we will keep it
				$priority = ActiveController::_typeHandled($types, $responseType['mime']);
				if( $priority !== false ) 
				{
					$registeredTypes[$priority] = $responseType;
				}
			}
		}
		if( count($registeredTypes) == 0 ) 
		{
			ActiveResponse::status(501);
			echo ActiveResponse::messageFromStatus(501);
			return;
		}
		
		// Sort based on browser preferences
		krsort($registeredTypes);
		// Use prefered type to respond
		$preferedType = reset($registeredTypes);
		// Response
		ActiveResponse::contentType($preferedType['mime'], ActiveController::$encoding);
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
		ActiveController::$responseTypes[] = array_merge(array(
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
		$pathInfo = preg_replace('$([.\+*?()[/\\]])$', '\\\\\\1', $pathInfo);
		$pathInfo = preg_replace('$\\\\\\((.+?)\\\\\\)$', '(\\1)?', $pathInfo);
		$pathInfo = preg_replace('$:([a-z]+)$', '(?P<\\1>.+?)', $pathInfo);
		$pathInfo = '/^'.$pathInfo.'$/';
		
		if( preg_match($pathInfo, $currentPathInfo, $matches) )
		{
			$params = array();
			foreach( $matches as $key => $value ) 
			{
				if( !is_numeric($key) )
				{
					$params[$key] = $value;
				}
			}
			return $params;
		}
		return false;
	}
}

?>