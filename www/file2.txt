<?php
require_once dirname(__FILE__).'/utils.php';

class ActiveDiff
{
	/*static*/ function compare($array1, $array2)
	{
//		echo "=============================================\n";
//		print_r($array1);
//		print_r($array2);
//		print_r(array_diff_assoc($array1, $array2));
//		print_r(array_diff_assoc($array2, $array1));
		return ActiveDiff::_compare(array_merge($array1), 0, array_merge($array2), array());
	}

	/*static*/ function patch($array, $patch)
	{
		$array = array_merge($array);
		foreach( $patch as $location => $items )
		{
			$index = intval(substr($location, 0, -1));
			$action = substr($location, -1);
			if( $action === '+' )
			{
				$remaining = array_splice($array, $index);
				foreach( $items as $item ) { array_push($array, $item); }
				foreach( $remaining as $item ) { array_push($array, $item); }
			}
			elseif( $action === '-' )
			{
				foreach( $items as $item )
				{
					unset($array[$index]);
					$index += 1;
				}
			}
		}
		return $array;
	}

	/*private*/

	/*static*/ function _compare($array1, $index, $array2, $diff)
	{
		// var_dump($array1, $array2);

		if( count($array1) == 0 && count($array2) == 0 )
		{
			return $diff;
		}
		if( count($array1) == 0 && count($array2) > 0 )
		{
			$diff[$index.'+'] = $array2;
			return $diff;
		}
		if( count($array2) == 0 && count($array1) > 0 )
		{
			$diff[$index.'-'] = $array1;
			return $diff;
		}

		$item1 = reset($array1);
		$item2 = reset($array2);

		if( $item1 === $item2 )
		{
			// Same !
			array_shift($array1);
			array_shift($array2);
			$index += 1;
		}
		else
		{
			// Search item1 and item2 in the remaining
			$index1 = array_search($item2, $array1);
			$index2 = array_search($item1, $array2);

			if( $index1 === false && $index2 === false )
			{
				$diff[$index.'-'] = array($item1);
				$diff[$index.'+'] = array($item2);
			}
			else
			{
				$max = max(count($array1), count($array2));
				if( $index1 === false ) { $index1 = $max; }
				if( $index2 === false ) { $index2 = $max; }

				if( $index1 >= $index2 )
				{
					// item1 has been found in array2 (before item2 in array1)
					// array2 items has been added
					$diff[$index.'+'] = array_splice($array2, 0, $index2);
					$index += $index2;
				}
				else
				{
					$diff[$index.'-'] = array_splice($array1, 0, $index1);
					$index += $index1;
				}
			}
		}
		return ActiveDiff::_compare($array1, $index, $array2, $diff);
	}
}


?>