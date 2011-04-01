<?php

function OpenTable($axid='',$return=0) {
	/*
	 * v4 extended with id name tag
	 */
	if ($return==1){
    	return '<table class="box"><tr><td class="boxcontent" name="'.$axid.'" id="'.$axid.'">';
	}else{
		echo '<table class="box"><tr><td class="boxcontent" name="'.$axid.'" id="'.$axid.'">';
	}
}

function CloseTable($return=0) {
	if ($return==1){
		return "</td></tr></table>";
	}else{
		echo "</td></tr></table>";
	}
}


/*
 *  TAGLIB
 */
function _imgButton($imgtype='save',$onClickFunction='',$popupdesc=''){
	$imgURL='images/closeBtn.gif';
	switch($imgtype){
		case 'add':
			$imgURL='images/list-add.gif';break;
		case 'remove':
			$imgURL='images/list-remove.gif';break;
		case 'accept':
			$imgURL='images/accept.png';break;
		case 'cancel':
			$imgURL='images/error24.png';break;
		case 'save':
			$imgURL='images/save24.png';break;
		case 'detail':
			$imgURL='images/detail24.png';break;
		case 'edit':
			$imgURL='images/edit24.png';break;
	}
	return '<img src="'.$imgURL.'" onClick="'.$onClickFunction.'" border="0" alt="'.$popupdesc.'">';
}

function _button($caption,$onClickFunction='',$winLocation=''){
	
	if (strlen($winLocation)>0) $onClickFunction='(window.location="'.$winLocation.'")';
	$EL='<INPUT class=\'lsdbbutton\' type=\'submit\' value=\''.$caption.'\' onclick=\''.$onClickFunction.'\' />';
	return $EL;
}

function _tdclickbox($caption,$target){
	/*
	 * this is a helper to render the typical TD clickelements within the GUI
	 * param target = url+params
	 * param caption = content of TD
	 * v4 replaced by _button code using css styling ...
	 */
	$ret='<td class=\'clickbox\' onclick=\'(window.location="'.$target.'")\'';
	$ret=$ret.' onMouseOver=\'mover(this)\' onMouseOut=\'mout(this)\'>'.$caption.'</td>';
	return $ret;
}

function _input($iTYPE,$id,$val='',$size=20,$maxsize=20){
	/*
	* generic helper for creating proper input elements
	* name and id are identical here ...
	* $iTYPE 		0 = hidden
	* 				1 = regular edit
	* 				2 = regular read-only
	* 				3 = textarea
	* 				4 = file
	* bugfix, with escaped strings containing special chars ...
	*/ 
	
	if ($iTYPE==0){
		$EL='<input class=\'lsdb\' type=\'hidden\' id=\''.$id.'\' name=\''.$id.'\' value=\''.$val.'\' />';
	} elseif ($iTYPE==1){
		$EL='<input class=\'lsdb\' type=\'text\' id=\''.$id.'\' name=\''.$id.'\' value=\''.$val.'\' size=\''.$size.'\' maxsize=\''.$maxsize.'\'/>';
	} elseif ($iTYPE==2){
		$EL='<input class=\'lsdb\' type=\'text\' readonly=\'TRUE\' id=\''.$id.'\' name=\''.$id.'\' value=\''.$val.'\' size=\''.$size.'\'/>';
	} elseif ($iTYPE==3){
		$EL='<textarea id=\''.$id.'\' name=\''.$id.'\' cols=\''.$size.'\' rows=\'10\'>'.$val.'</textarea>';
	} elseif ($iTYPE==4){
		$EL='<input type=\'file\' id=\''.$id.'\' name=\''.$id.'\' size=\''.$size.'\'>'.$val.'</input>';
	}
	return $EL;
}

function _checkbox($id,$val=0,$caption=''){
	/*
	 * checkbox definition for a 0/1 box, values is set to 1
	 * chkboxes do not commit if unckecked ...
	 */
	$opt='';
	if ($val==1) $opt='checked=\'checked\'';
	$EL='<input class=\'lsdb\' type=\'checkbox\' id=\''.$id.'\' name=\''.$id.'\' value=\'1\' '.$opt.'/> '.$caption;
	return $EL;
}

function _radio($id,$aVal=array(),$aCaption=array(),$align='horizontal'){
	/*
	 * radio buttons in passed alignment ... vertical or horizontal
	 * <input type="radio" name="name" value="value" /> Caption
	 */
	$i=0;$EL="";
	if ($align=='horizontal'){$br='';} else { $br='<br/>'; }
	foreach ($aVal as $val){
		$EL=$EL.'<input class=\'lsdb\' type=\'radio\' id=\''.$id.'\' name=\''.$id.'\' value=\''.$val.'\' /> '.$aCaption[$i].$br;
		$i=$i+1;
	}
	return $EL;
}


?>
