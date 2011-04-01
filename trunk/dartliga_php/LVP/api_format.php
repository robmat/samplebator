<?php
/**
 * FORMAT Layer: api_format.php
 * functions in here serve as translator between Recordsets from the DB Layer and
 * the output in the presentation layer
 */
if (eregi("api_format.php",$_SERVER['PHP_SELF'])) {
    Header("Location: ./");
    die();
}

function unhtmlentities($string)
{
    // replace numeric entities
    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
    $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
    // replace literal entities
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
}
	/**
	 * purpose:	serialize a Recordset style array into a csv seperated row
	 * 			each row is terminated by <br> tag.
	 * returns: string
	 */
function RecordsetToCSV(&$RECORDSET){
	$strRET="";
	foreach($RECORDSET as $ROW){
		foreach ($ROW as $val)
        {
            $strRET=$strRET.$val.";";
        }
		$strRET=$strRET."<br>";
	}
	return $strRET;
}

	/**
	 * purpose:		this converts a Recordset style array into HTML Table rows, no Table def inkluded
	 * 				each cell is class=dcell, buttonactions if set render buttons with onClick events
	 * param:		$aFields = array of fields from RS to render into the table
	 * 				$aActionParams=2-dimensional array
	 * returns: 	HTML string
	 */
function RecordsetToDataTable(&$RECORDSET,$aFields=array(),$aButtonAction=array(),$aActionParams=array(),$btnCaption=array()){
	$strRET='';
	if (!sizeof($RECORDSET)>0) return $strRET;
	if (!sizeof($aFields)>0) return $strRET;
	
	foreach($RECORDSET as $ROW){
		$P='';
		$strRET=$strRET.'<tr>';
		foreach($aFields as $key){
			$strRET=$strRET.'<td class=\'dcell\'>'.$ROW[$key].'</td>';
		}
		$i=0;
		foreach($aButtonAction as $action){
			$strRET=$strRET.'<td class=\'dcell\'><button onclick=\''.$action.'(';
			$P='';
			foreach($aActionParams[$i] as $key){
				$P=$P.",$ROW[$key]";
			}
			$P=substr($P,1);
			$strRET=$strRET.$P.')\'>'.$btnCaption[$i].'</button></td>';
			$i++;
		}
		$strRET=$strRET.'</tr>';
	}
	return $strRET;
}

	/**
	*	purpose:	Format Layer, create SelectBox from RecordSet
	* 				fieldsarray[0] is used as value, lineitem is generated out of fields array
	* 	params:		nameofelement / selected option / js:selection_changeaction
	*	returns:	HTML OptionSelectBox
	*/
function RecordsetToSelectOptionList(&$RECORDSET,$aFields=array(),$name_id,$val_select=0,$selchangeaction='',$allowNoSelect=1){
	$strRET="<select id=\"$name_id\" name=\"$name_id\" onchange=\"$selchangeaction\" size=\"1\">";
	if ($allowNoSelect==1) {$strRET=$strRET.'<option value=\'0\'>-- No selection --</option>';}
	foreach($RECORDSET as $ROW){
		$idxvalue=$aFields[0];
		$item="";
		if ($ROW[$idxvalue] == $val_select) {
			$strRET=$strRET.'<option value=\''.$ROW[$idxvalue].'\' selected=\'selected\'>';
		} else {
			$strRET=$strRET.'<option value=\''.$ROW[$idxvalue].'\'>';
		}
		foreach($aFields as $key){
			if ($key>0) $item=$item.' '.$ROW[$key];
		}
		$strRET=$strRET.$item.'</option>';
	}
	return $strRET.'</select>';
}

	/**
	 * purpose: 	generic table renderer for any ordered recordset
	 * 					optional clicktarget and hover effect, if no target passed than its a regular table
	 * param: recordset of 1-x arrays containing 1-x cells
	 * 				cssclass = class for TR
	 * 				target= clicktarget url
	 * 				P1 = 	cell(index) of parameter %P1% in target url, since this is usually the PID or EVID
	 * 				P2 = 	cell(index) of parameter %P2% in target url, since this is usually the PID or EVID
 	 * 							we don't render this cells into the output ....
	 *  				$firstfield = index of first field to show (usually suppress ID fields)
	 * output: string = htmltable with clickrows 
	 * 
	 */
function RecordsetToClickTable(&$RS,$firstfield=0,$clicktarget='nop',$idxP1='',$idxP2=''){
	// make sure our variables are not valid even if passed as 'empty' needed for the render part ..
	if (strlen($idxP1)>0) $P1=$idxP1;
	if (strlen($idxP2)>0) $P2=$idxP2;
	if (strlen($clicktarget)==0) $clicktarget='nop';
	$strRET='';
	#debug('RecordsetToClickTable:'.sizeof($RS));
	foreach($RS as $row){
		$jumptarget='';
		if ($clicktarget<>'nop'){
			// check on target id substitution if P1,P2 set, replace by value from row-set
			if (isset($P1)) $jumptarget=str_replace('%P1%', $row[$P1], $clicktarget);
			if (isset($P2)) $jumptarget=str_replace('%P2%', $row[$P2], $jumptarget);
			$TR='<tr class=\'clickcell\' onclick=\'document.location="'.$jumptarget.'"\' onMouseOver=\'mover(this)\' onMouseOut=\'mout(this)\'>';
			# class css does not work in IE ....
			#$TR="<tr class=\"clickcell\" onclick=\"document.location='$jumptarget'\")>";
		} else {
			$TR='<tr class=\'datarow\'>';
		}
		
		$c=0;
		foreach($row as $cell){
				// only render output if this is not the param cell for the target jump
				// only render if we show this field
				if ($c>=$firstfield){
						if (isset($P1) && $c<>$P1)$TR=$TR.'<td>'.$cell.'</td>';
				}
			$c++;
		}
		$strRET=$strRET.$TR.'</tr>';
	}
	return $strRET;
}

function ArrayToTableHead(&$aTH,$cssclass='thead'){
	/*
	 * create a nice table head from an array of strings
	 * using the thead class as default
	 */
	$strRET='<tr>';
	foreach ($aTH as $sHead) {
		$strRET=$strRET.'<td class="'.$cssclass.'">'.$sHead.'</td>';
	}
	return $strRET.'</tr>';
}
?>