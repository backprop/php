<?php
class PHPGroupQuery{
		
	public static function groupQuery($query,$columnArray,$startPos=0,$endPos=null){
		$returnArray=array();
		
		// every time we come in here, our start and end records will be defined...except the very initial call in which case
		// we should assume it's from the 0th through last record (n-1)
		if(is_null($endPos)){
			$endPos=count($query)-1;
		}
		
		// get the grouping column assigned to this level of recursion.  array_shift() pops it off and the array is reduced by 1
		$groupColName=array_shift($columnArray);
		
		// if there is a level of grouping to do...
		if($groupColName != ""){
			
			// make sure that column exists.  We can't group on a nonexistent column...
			if(!array_key_exists($groupColName,$query[0])){
				throw new Exception("Group column " . $groupColName . " does not exist");
			}
			
			// get the starting value of the column.  We're going to go through our assigned range and every time this value
			// changes, we're going to write a row in the output query and then group on a lower level if needed
			$currKeyVal=$query[$startPos][$groupColName];
		
			//For our given range and our given key column,
			// chunk each set of rows [defining each from frameStart to frameEnd] that has the same value in the key column
			// [A B C] [D E] [F] [G H I J]
			
			$frameStart=$startPos; //equivalent to setting the [ left bracked in the diagram above
			$frameEnd=null;
			for($i=$startPos;$i<=$endPos;$i++){
				// if the next record's key column has a different value than the one we're looking at:
				// Stop....write out the first record of our assigned group..and go to the next level of grouping, if there is one.
				// And if we're at the last record in our grouping ($i==$endPos),
				// treat it same as the next record being different.. (We have to test for that first or we might overshoot the number
				// of records in the query)
				// 2018-06-20, because SQL is not case sensitive, make the comparison case insensitive by using strtolower()
				if($i==$endPos || strtolower($query[$i+1][$groupColName]) != strtolower($currKeyVal)){
					// OK, we know the next record is different.  So set the ] right bracket right here
					$frameEnd=$i;
					
					// the first record in our task is appended to the return query array
					$returnArray[]=$query[$frameStart];
					// add a handy field called currGroupLevel that shows what we're grouping on at THIS level (good for var_dump())
					$returnArray[count($returnArray)-1]["currGroupLevel"]=$groupColName;
					
					// if there's more grouping to do after this one (even if we were only given a single record!)
					//, add a "group" entry and pass the starting and ending position of what we just did so it can group at the lower level
					if(count($columnArray) > 0){
						$returnArray[count($returnArray)-1]["group"]=self::groupQuery($query,$columnArray,$frameStart,$frameEnd);
					}
					
					// reset the frame start ([) so when we loop back around, it's at the new $i position
					$frameStart=$i+1;
					// set the new "currKeyVal" equal to the next record in the DB...unless we're at the last record of our
					// task...in which case we don't want to error if its the last record in the query.
					if($i != $endPos) $currKeyVal=$query[$i+1][$groupColName];
				}
			}
		}
		
		return $returnArray;
	}
	
}
?>