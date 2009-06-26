<?php

require_once(dirname(__FILE__).'/utils.php');

class Request
{
	/*static*/ function method()
	{
		return Utils::strDefault(
			strtoupper(Utils::mapGet($_GET, 'x-rest-method')),
			Utils::mapGet($_SERVER, 'REQUEST_METHOD'),
			'GET'
		);
	}

	/*static*/ function script()
	{
		$scriptName = Utils::mapGet($_SERVER, 'SCRIPT_NAME', '');
		$script = substr(Request::uri(), 0, strlen($scriptName));
		
		if( $script != $scriptName )
		{
			// SCRIPT_NAME may contains the extension when it should not
			// Remove it
			return Utils::substrBefore($scriptName, '.');
		}
		return $script;
	}

	/*static*/ function scriptDir()
	{
		return rtrim(dirname(Request::script()), '/');
	}

	/*static*/ function uri()
	{
		return Utils::mapGet($_SERVER, 'REQUEST_URI', '');
	}
	
	/*static*/ function relative($uri)
	{
		return Request::scriptDir().'/'.$uri;
	}

	/*static*/ function pathInfo()
	{
		$requestUri = Request::uri();
		$script = Request::script();
		$pathInfo = substr($requestUri, strlen($script));
		
		$pathInfo = Utils::escapeMagicQuotes($pathInfo);
		$pathInfo = Utils::substrBefore($pathInfo, '?');
		$pathInfo = urldecode($pathInfo);
		$pathInfo = ltrim($pathInfo, '/');
		if( strlen($pathInfo) == 0 )
		{
			return array();
		}
		return explode('/', $pathInfo);
	}
	
	/*static*/ function query()
	{
		$queryString = Utils::substrAfter(Request::uri(), '?');
		if( strlen($queryString) == 0 )
		{
			return array();
		}
		$parts = explode('&', trim($queryString, '&'));
		$query = array();
		foreach( $parts as $part )
		{
			$key = Utils::substrBefore($part, '=');
			$value = Utils::substrAfter($part, '=');
			$query[$key] = Utils::mapGet($query, $key, array());
			if( isset($query[$key]) )
			{

			}
			$query[$key][] = $value;
		}
		return $query;
	}

	/*static*/ function input()
	{
		return file_get_contents('php://input');
	}
	
	/*private*/
}

?>