<?php
require_once dirname(__FILE__).'/utils.php';

class Request
{
	/*static*/ function method()
	{
		return Utils::strDefault(
			Utils::mapGet($_GET, 'x-rest-method'),
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
		$pathInfo = trim($pathInfo, '/');
		if( strlen($pathInfo) == 0 )
		{
			return array();
		}
		return explode('/', $pathInfo);
	}
	
	/*static*/ function query()
	{
		$queryString = Utils::substrAfter(Request::uri(), '?');
		$query = array();
		parse_str($queryString, $query);
		return $query;
	}

	/*static*/ function input()
	{
		return file_get_contents('php://input');
	}
	
	/*static*/ function user()
	{
		return Utils::mapGet($_SERVER, 'PHP_AUTH_USER');
	}

	/*static*/ function password()
	{
		return Utils::mapGet($_SERVER, 'PHP_AUTH_PW');
	}
	
	/*static*/ function types()
	{
		return Request::_parseAccept('HTTP_ACCEPT');
	}
	
	/*static*/ function languages()
	{
		return Request::_parseAccept('HTTP_ACCEPT_LANGUAGE');
	}
	
	/*static*/ function encodings()
	{
		return Request::_parseAccept('HTTP_ACCEPT_ENCODING');
	}
	
	/*static*/ function charsets()
	{
		return Request::_parseAccept('HTTP_ACCEPT_CHARSET');
	}
	
	/*private*/
	
	/*static*/ function _parseAccept($key)
	{
		$acceptString = Utils::mapGet($_SERVER, $key, '');
		preg_match_all('$([\-/+*A-Za-z0-9]+(?:,[\-/+*a-z0-9]+)*)(?:;q=([0-9.]+))?$', $acceptString, $matches);
		
		$accept = array();
		foreach( $matches[2] as $index => $priority ) 
		{
			$values = Utils::strSplit($matches[1][$index], ',');
			$accept[$priority] = array_merge(Utils::mapGet($accept, $priority, array()), $values);
		}
		krsort($accept, SORT_NUMERIC);
		return $accept;		
	}
}

?>