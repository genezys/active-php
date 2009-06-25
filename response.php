<?php

require_once(dirname(__FILE__).'/utils.php');

class Response
{
	/*static*/ function httpMessageFromStatus($code)
	{
		switch( $code )
		{
		// 1xx Information
		case 100: return "Continue";                       
		case 101: return "Switching Protocols"; 
		case 102: return "Processing";
		// 2xx Success
		case 200: return "OK";                             
		case 201: return "Created";
		case 202: return "Accepted";                       
		case 203: return "Non-Authoritative Information";
		case 204: return 'No Content';                     
		case 205: return "Reset Content";
		case 206: return "Partial Content";                
		case 207: return "Multi-Status";
		case 210: return "Content Different";
		// 3xx Redirection
		case 300: return "Multiple Choices";               
		case 301: return "Moved Permanently";
		case 302: return "Moved Temporarily";              
		case 303: return "See Other";
		case 304: return "Not Modified";                   
		case 305: return "Use Proxy";
		case 307: return "Temporary Redirect";
		// 4xx Client Error
		case 400: return "Bad Request";                    
		case 401: return "Unauthorized";
		case 402: return "Payment Required";               
		case 403: return "Forbidden";
		case 404: return "Not Found";                      
		case 405: return "Method Not Allowed";
		case 406: return "Not Acceptable";                 
		case 407: return "Proxy Authentication Required";
		case 408: return "Request Time-out";               
		case 409: return "Conflict";
		case 410: return "Gone";                           
		case 411: return "Length Required";
		case 412: return "Precondition Failed";            
		case 413: return "Request Entity Too Large";
		case 414: return "Request-URI Too Long";           
		case 415: return "Unsupported Media Type";
		case 416: return "Requested range unsatifiable";   
		case 417: return "Expectation failed";
		case 422: return "Unprocessable entity";           
		case 423: return "Locked";
		case 424: return "Method failure";
		// 5xx Server Error
		case 500: return "Internal Server Error";          
		case 501: return "Not Implemented";
		case 502: return "Bad Gateway";                    
		case 503: return "Service Unavailable";
		case 504: return "Gateway Time-out";               
		case 505: return "HTTP Version not supported";
		case 507: return "Insufficient storage";
		}
	}

	/*static*/ function status($status)
	{
		$msg = Response::httpMessageFromCode($status);
		header('HTTP/1.1 '.$status.' '.$msg);
	}
	
	/*static*/ function contentType($contentType, $encoding = '')
	{
		$encoding = Utils::strDefault($encoding, 'UTF-8');
		header('Content-Type: '.$contentType.'; charset='.$encoding);
	}

	/*static*/ function location($uri)
	{
		header('Location: '.$uri);
	}

	/*static*/ function noCache()
	{
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Pragma: no-cache"); // HTTP/1.0
	}

	/*static*/ function authenticate($type, $realm)
	{
		header('WWW-Authenticate: '.$type.' realm="'.$realm.'"');
	}
}

?>