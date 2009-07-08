<?php
require_once dirname(__FILE__).'/utils.php';

class ActiveDiff
{
	/*static*/ function compare($array1, $array2)
	{
		$diff = array();
		$index = 0;
		
		for( $i = 0; $i < 100; ++$i )
		{
			if( count($array1) == 0 && count($array2) == 0 )
			{
				break;
			}
			if( count($array1) == 0 && count($array2) > 0 )
			{
				$diff[$index.'+'] = $array2;
				break;
			}
			if( count($array2) == 0 && count($array1) > 0 )
			{
				$diff[$index.'-'] = $array1;
				break;
			}
	
			$item1 = array_shift($array1);
			$item2 = array_shift($array2);

			echo "-$item1\n";
			echo "-$item2\n";
			echo "\n";

			if( $item1 === $item2 )
			{
				// Same !
				$index += 1;
			}
			else
			{
				// Search item1 and item2 in the remaining
				$index1 = array_search($item2, $array1);
				$index2 = array_search($item1, $array2);

				if( $index1 === false && $index2 === false )
				{
					// Not found in any remaining array
					$diff[$index.'+'] = array($item2);
					$diff[$index.'-'] = array($item1);
					$index += 1;
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
						$diff[$index.'+'] = array_splice($array2, 0, $index1 - 1);
						array_unshift($diff[$index.'+'], $item2);
						$index += $index2;
					}
					else
					{
						$diff[$index.'-'] = array_splice($array1, 0, $index2 - 1);
						array_unshift($diff[$index.'-'], $item1);
						$index += 1;
					}
				}
			}
		}
		return $diff;
	}

	/*static*/ function patch(&$array, &$patch)
	{
		$array = array_merge($array);
		foreach( $patch as $location => $items )
		{
			$index = intval(substr($location, 0, -1));
			$action = substr($location, -1);
			if( $action === '+' )
			{
				array_splice($array, $index, 0, $items);
			}
			elseif( $action === '-' )
			{
				array_splice($array, $index, count($items));
			}
		}
		return $array;
	}

	/*private*/
}


?>