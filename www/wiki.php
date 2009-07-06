<?php
require_once dirname(__FILE__) . '/../active-php/index.php';

///////////////////////////////////////////////////////////////////////////////
function authenticate($user, $password)
{
	if( $user == 'admin' && $password == 'plop' ) 
	{
		return true;
	}
	return false;
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('get', 'pages(.:format)', 'getPageList');

function getPageList($params)
{
	ActiveController::views(__FILE__, '../views');
	ActiveController::respondWithView('html', 'text/html');
	ActiveController::respondWithView('xml', 'application/atom+xml');

	ActiveController::respond(array(
		'pages' => array('plop', 'onk'),
	));
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('post', 'pages(.:format)', 'getPageList');

function createPage($params)
{
	
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('get', 'pages/:id(.:format)', 'getPage');
function getPage($params)
{
	if( $params['id'] == 'Plop' ) 
	{
		$params['loggedIn'] = ActiveController::authenticateBasic('Wiki!', 'authenticate');
	}

	ActiveController::respondWith('txt', 'text/plain', 'getPageAsText');
	ActiveController::respondWith('html', 'text/html', 'getPageAsHtml');
	ActiveController::respond($params);
}
function assertEquals($code1, $value2)
{
	$value1 = $code1;
	if( gettype($code1) == 'string' )
	{
		$value1 = eval('return '.$code1.';');
	}

	if( $value1 !== $value2 )
	{
		echo $code1, "\n";
		echo "Got\n";
		print_r($value1);
		echo "instead of\n";
		print_r($value2);
	}
}

function getPageAsText($params)
{
	var_dump(ActiveRequest::query());

	echo 'Page '.$params['id'] . "\n";
	
	if( $params['id'] == 'diff' ) 
	{
		assertEquals("ActiveDiff::compare(array(), array())", array());
		assertEquals("ActiveDiff::compare(array(1,2,3), array(1,2,3))", array());
		assertEquals("ActiveDiff::compare(array(), array(1,2,3))", array('0+' => array(1,2,3)));
		assertEquals("ActiveDiff::compare(array(1,2,3), array())", array('0-' => array(1,2,3)));
		assertEquals("ActiveDiff::compare(array(1,2,3), array(1,2))", array('2-' => array(3)));
		assertEquals("ActiveDiff::compare(array(1,2), array(1,2,3))", array('2+' => array(3)));
		assertEquals("ActiveDiff::compare(array(1,2,3), array(2,3))", array('0-' => array(1)));
		assertEquals("ActiveDiff::compare(array(2,3), array(1,2,3))", array('0+' => array(1)));
		assertEquals("ActiveDiff::compare(array(1,2,3), array(1,3))", array('1-' => array(2)));
		assertEquals("ActiveDiff::compare(array(1,3), array(1,2,3))", array('1+' => array(2)));
		assertEquals("ActiveDiff::compare(array(1,2,3), array(1,3,2))", array('1+' => array(3), '3-' => array(3)));

		$orig = array(1,2,3);
		$result = array(1,3,2);
		assertEquals(ActiveDiff::patch($orig, ActiveDiff::compare($orig, $result)), $result);
		
		$file1 = file('file1.txt', FILE_IGNORE_NEW_LINES);
		$file2 = file('file2.txt', FILE_IGNORE_NEW_LINES);
		var_dump(ActiveDiff::compare($file1, $file2));
		
	}
}
function getPageAsHtml($params)
{
	echo 'Page ' . $params['id'];
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('put', 'pages/:id(.:format)', 'updatePage');

function updatePage($params)
{
	echo 'updatePage';
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::route('delete', 'pages/:id(.:format)', 'deletePage');

function deletePage($params)
{
	echo 'deletePage';
}

///////////////////////////////////////////////////////////////////////////////
ActiveController::dispatch();
?>