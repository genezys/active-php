<?php
require_once dirname(__FILE__).'/utils.php';

class ActiveDiff
{
	/*static*/ function compare($array1, $array2)
	{
		return ActiveDiff::_compare($array1, $array2, array());
	}
	
	/*private*/
	
	/*static*/ function _compare($array1, $array2, $diff)
	{
		// var_dump($array1, $array2);
		
		if( count($array1) == 0 && count($array2) == 0 ) 
		{
			return $diff;
		}
		if( count($array1) == 0 && count($array2) > 0 ) 
		{
			$diff[] = array('+' => $array2);
			return $diff;
		}
		if( count($array2) == 0 && count($array1) > 0 ) 
		{
			$diff[] = array('-' => $array1);
			return $diff;
		}

		$item1 = reset($array1);
		$item2 = reset($array2);
		
		if( $item1 === $item2 ) 
		{
			// Same !
			array_shift($array1);
			array_shift($array2);
		}
		else 
		{
			// Search item1 and item2 in the remaining
			$index1 = array_search($item2, $array1);
			$index2 = array_search($item1, $array2);
						
			if( $index1 === false && $index2 === false )
			{
				$diff[] = array('-' => array($item1));
				$diff[] = array('+' => array($item2));
			}
			else 
			{
				if( $index1 === false ) 
				{
					$index1 = count($array1);
				}
				if( $index2 === false ) 
				{
					$index2 = count($array2);
				}

				if( $index1 >= $index2 ) 
				{
					// item1 has been found in array2 (before item2 in array1)
					// array2 items has been added
					$diff[] = array('+' => array_splice($array2, 0, $index2));
				}
				else
				{
					$diff[] = array('-' => array_splice($array1, 0, $index1));
				}
			}
		}
		
		return ActiveDiff::_compare($array1, $array2, $diff);
	}
}


?>