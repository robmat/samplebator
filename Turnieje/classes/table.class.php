<?php
class Table {
	var $c; //aktualna wartosc
	var $cfg; //wartosci konfiguracyjne
	var $err; //blad
	
	var $name; //nazwa
	var $op; //opcja
	var $id; //id
	
	var $sql = ''; //sql query
	var $fields = ''; //pola tabeli (do budowy sqla)
	var $values = ''; //wartosci tabeli (do budowy sqla)
	var $updates = ''; //wartosci updatowanej tabeli (do budowy sqla)
	
	function Table($name, $op, $id) {
		$this->name = $name;
		$this->op = $op;
		$this->id = $id;
	}
	
	function display($sql, $option='') {
		$out='';
		if($option) {
			for($i=65; $i<=90; $i++) {
				$out .= '<a href="modules.php?name='.$this->name.'&op='.$option.'&lttr='.chr($i).'">'.chr($i).'</a> ';
				switch($i) {
					case(79):
					$out .= '<a href="modules.php?name='.$this->name.'&op='.$option.'&lttr=Ö">Ö</a> ';
					break;
					
					case(65):
					$out .= '<a href="modules.php?name='.$this->name.'&op='.$option.'&lttr=Ä">Ä</a> ';
					break;
					
					/*case(85):
					$out .= '<a href="modules.php?name='.$this->name.'&op='.$option.'&lttr=Ü">Ü</a> ';
					break;
					
					case(90):
					$out .= '<a href="modules.php?name='.$this->name.'&op='.$option.'&lttr=¯">¯</a> ';
					break;*/
				}
			}
		}
		
		$res = mysql_query($sql);
		
		$out .= '<table><tr>';
		foreach($this->cfg as $key => $value) {
			if($value->no_list != 1) {
			$out .= "<td>".$value->public_name."</td>";
			}
		}
		$out .= '</tr><tr>';
		
		while($obj = mysql_fetch_object($res)) {
			$out .= '<tr>';
			foreach($this->cfg as $key => $value) {
				if($value->no_list != 1) {
					if($value->edit == 1) $out .= '<td><a href="modules.php?name='.$this->name.'&op='.$this->op.'&id='.$obj->id.'">'.$obj->{$key}.'</a></td>';
					else $out .= "<td>".$obj->{$key}."</td>";
				}
			}
			if($this->list_addon_start) $out .= "<td>".$this->list_addon_start.$obj->id.$this->list_addon_end."</td>";
			$out .= '</tr>';
		}
		$out .= '</table>';
		return $out;
	}
	
	function buildTable() {
		//print_array($this);
	$out = '<form name="'.$this->tbl.'" method="post" ';
	if($this->pic) $out .= 'enctype="multipart/form-data" ';
	$out .= 'action="'.$_SERVER['PHP_SELF'].'"><table>';

		foreach($this->cfg as $key => $value) {

		  if(!$value->checkbox) {
			$out .= '<tr><td>';
				if($value->not_empty == 1) $out .= '* ';
			if($value->no_add != 1) $out .= $value->public_name.': </td>';
		  }

		  if($this->cfg->{$key}->type == "picture")
		  {
			$out .= '<td><input type="file" name="'.$value->name.'">';
				if ($this->err->{$key}) $out .= '<span class="error">'.$this->err->{$key}.'</span>';
			$out .= '</td></tr>';
			continue;
		  }
		  if($value->list_me) {
			$out .= '<td>'.$this->getListFromVar($value->name, $this->{$value->list_me}, $this->c->{$key});
			if ($this->err->{$key}) $out .= ' <span class="error">'.$this->err->{$key}.'</span>';
			$out .= '</td>';
			continue;
		  }
		  if($value->radio) {
			$out .= '<td>'.$this->getRadioFromVar($value->name, $this->{$value->radio}, $this->c->{$key});
			if ($this->err->{$key}) $out .= ' <span class="error">'.$this->err->{$key}.'</span>';
			$out .= '</td>';
			continue;
		  }
		  if($value->db_list) {
			$out .= '<td>'.$this->getListFromDB($value->db_list_pk, $value->name, $value->db_list, $value->db_list_sel, $this->c->{$key}, $value->db_list_cond);
			if ($this->err->{$key}) $out .= ' <span class="error">'.$this->err->{$key}.'</span>';
			$out .= '</td>';
			continue;
		  }
		  //----------
		  if($value->db_list2) {
			$out .= '<td>'.$this->getListFromDB2($value->db_list_pk, $value->name, $value->db_list2, $value->db_list_sel1, $value->db_list_sel2, $this->c->{$key}, $value->db_list_cond);
			if ($this->err->{$key}) $out .= ' <span class="error">'.$this->err->{$key}.'</span>';
			$out .= '</td>';
			continue;
		  }
		  //------------
		  if($value->joint) {
			$out .= '<td><input type="text" maxlength="'.$value->maxlength.'" name="'.$value->name.'" value="'.$this->c->{$key}.'"> '.$this->getListFromVar($value->name."#joint", $this->{$value->joint}, $this->c->{$key."#joint"});
				if ($this->err->{$key}) $out .= ' <span class="error">'.$this->err->{$key}.'</span>';
			$out .= '</td></tr>';
			continue;
		  }
		  if($value->checkbox) {
		  	  $elements = sizeof($this->{$value->checkbox});
			  $out .= '<tr><td colspan="2">'.$value->public_name.'</td></tr><tr><td colspan="2"><table width="100%" border="0">';
		  	  for($i=0; $i<$elements; $i++) {
			    if($i%$this->{$key.'_cols'} == 0) $out .= '<tr>';
				$out .= '<td><input type="checkbox" name="'.$value->name.'#chkbox#'.$i.'"';
				if($this->c->{$key.'#chkbox#'.$i} == "on") $out .= 'checked';
				$out .= '> '.$this->{$value->checkbox}[$i].'</td>';
				
				//dorysowanie pustych pól w tabeli
				if($i+1 == $elements) {
					while(($i+1)%$this->{$key.'_cols'} != 0) {
					  $out .= '<td>&nbsp;</td>';
					  $i++;
					}
			    }
				if($i%$this->{$key.'_cols'} == $this->{$key.'_cols'}-1) $out .= '</tr>';
			  }
			  $out .= '</table></td></tr>';
			continue;
		  }
		  if($value->type == "text" || $value->type == "password") {
			$out .= '<td><input type="'.$value->type.'" ';
			if($value->size) $out .= 'size="'.$value->size.'" ';
			$out .= 'maxlength="'.$value->maxlength.'" name="'.$value->name.'" value="'.$this->c->{$key}.'"> ';
			if ($this->err->{$key}) $out .= $this->err->{$key};
			$out .= '</td></tr>';
			continue;
		  }
		  
		  if($value->type == "textarea") {
			$out .= '<td><textarea name="'.$value->name.'" rows="5" columns="60">'.$this->c->{$key}.'</textarea></td></tr>';
			continue;
		  }
		}

	$out .='<tr><td colspan="2">';
		if($this->id) $out .='<input type="hidden" name="id" value="'.$this->id.'">';
	$out .='<input type="hidden" name="op" value="'.$this->op.'"><input type="hidden" name="name" value="'.$this->name.'"><input type="submit" name="submit" value="'._Wyslij.'"></td></tr></table></form>';
	return $out;
	}
	
	function getListFromVar($listName, $variable, $selected = '', $style = '') {
	$out = '<select name="'.$listName.'"';
	if ($style) $out .= ' class="'.$style.'">';
	else $out .= '>';
	
		$out .= '<option value="">'._Wybierzzlisty.'</option>';
		if(is_array($variable)){
			foreach ($variable as $key => $value) {
				$out .= '<option value="'.$key.'"';
				if ($key == $selected) $out .= ' selected>'.$value.'</option>';
				else $out .= '>'.$value.'</option>';
			}
		}
	$out .= '</select>';
	return $out;
	}
	
	function getListFromDB($pk, $listName, $table, $field, $selected = '', $condition = '', $style = '', $jscript = '') {
	$sql = 'SELECT '.$pk.', '.$field.' FROM '.$table;
		if($condition) $sql .= ' WHERE '.$condition;
	$sql .= ' ORDER BY '.$field;
	
	$res = mysql_query($sql);
	$out = '<select name="'.$listName.'"';
		if ($style) $out .= ' class="'.$style.'"';
		if ($jscript) $out .= ' '.$jscript;
	$out .= '>';
		$out .= '<option value="">'._Wybierzzlisty.'</option>';
	
		while($obj = mysql_fetch_object($res)) {
			$out .= '<option value="'.$obj->{$pk}.'"';
			if ($obj->{$pk} == $selected) $out .= ' selected>'.$obj->{$field}.'</option>';
			else $out .= '>'.$obj->{$field}.'</option>';
		}
	$out .= '</select>';
	return $out;
	}
	
	function getListFromDB2($pk, $listName, $table, $field1, $field2, $selected = '', $condition = '', $style = '', $jscript = '') {
	$sql = 'SELECT '.$pk.', '.$field1.','.$field2.' FROM '.$table;
		if($condition) $sql .= ' WHERE '.$condition;
	$sql .= ' ORDER BY '.$field1;
	
	$res = mysql_query($sql);
	$out = '<select name="'.$listName.'"';
		if ($style) $out .= ' class="'.$style.'"';
		if ($jscript) $out .= ' '.$jscript;
	$out .= '>';
		$out .= '<option value="">'._Wybierzzlisty.'</option>';
	
		while($obj = mysql_fetch_object($res)) {
			$out .= '<option value="'.$obj->{$pk}.'"';
			if ($obj->{$pk} == $selected) $out .= ' selected>'.$obj->{$field1}.' '.$obj->{$field2}.'</option>';
			else $out .= '>'.$obj->{$field1}.' '.$obj->{$field2}.'</option>';
		}
	$out .= '</select>';
	return $out;
	}
	
	function getRadioFromVar($listName, $variable, $selected = '', $style = '') {
	$out = '';
		foreach ($variable as $key => $value) {
			$out .= '<input type="radio" name="'.$listName.'" value="'.$key.'"';
			if ($style) $out .= ' class="'.$style.'"';
			if ($key == $selected) $out .= ' checked> '.$value.' &nbsp; ';
			else $out .= '> '.$value.' &nbsp; ';
		}
	return $out;
	}
	
	function getFormValues() {
		foreach($_POST as $key => $value) {
			if($key != "submit" && $key !="op" && $key !="name") {
				if(!get_magic_quotes_gpc()) $value = mysql_escape_string($value);
				$value = trim($value);
				$this->c->{$key} = $value;
			}
		}
	}
	
	function getDBValues() {
		$this->makeSQL('SELECT');
		$res = mysql_query($this->sql);
		$obj = mysql_fetch_object($res);
		foreach($this->cfg as $key => $value) {
			$this->c->{$key} = $obj->{$key};
		}
	}
	
  function compactTable() {
	foreach ($this->c as $key => $value) {
		if($this->cfg->{$key}->joint) {
			if(!empty($value)) $this->c->{$key} .= '^'.$this->c->{$key.'#joint'};
		}
		if(strpos($key, "chkbox")) {
			$actual = explode('#', $key);
			empty($this->c->{$actual[0]}) ? $this->c->{$actual[0]} = $actual[2] : $this->c->{$actual[0]} .= '^'.$actual[2];
		}
	}
  }
  
  function expandTable() {
	foreach ($this->c as $key => $value) {
		if($this->cfg->{$key}->joint) {
			$val = explode('^', $value);
			$this->c->{$key} = $val[0];
			$this->c->{$key.'#joint'} = $val[1];
		}
		if($this->cfg->{$key}->checkbox) {
			$ex = explode('^', $value);
			foreach($ex as $nr) {
				$this->c->{$key.'#chkbox#'.$nr} = "on";
			}
		}
	}
  }
  
  function stripJoints() {
  	foreach ($this->c as $key => $value) {
		if($this->cfg->{$key}->joint) {
			$val = explode('^', $value);
			$this->c->{$key} = $val[0];
		}
	}
  }
  
  function makeSQL($sqlOption) {
	global $prefix;
  	if($sqlOption == "INSERT") {
		$this->makeFieldsAndValuesList();
		$this->sql = "INSERT INTO ".$prefix."_".$this->tbl." (".$this->fields.") VALUES (".$this->values.")";

	}
	
	if($sqlOption == "SELECT") {
		$this->sql = "SELECT * FROM ".$prefix."_".$this->tbl." WHERE id = ".$this->id;
	}
	
	if($sqlOption == "UPDATE") {
		$this->makeUpdateList();
		$this->sql = "UPDATE ".$prefix."_".$this->tbl." SET ".$this->updates." WHERE id = ".$this->id;
	}
	
	if($sqlOption == "DELETE") {
		$this->sql = "DELETE FROM ".$prefix."_".$this->tbl." WHERE id = ".$this->id;
	}
  }
  
  function makeFieldsAndValuesList() {
  	foreach ($this->c as $key => $value) {
		if(!strpos($key, "#")) {
		empty($this->fields) ? $this->fields = $key : $this->fields .= ', '.$key;
		empty($this->values) ? $this->values = "'".$value."'" : $this->values .= ", '".$value."'";
		}
	}
  }
  
  function makeUpdateList() {
  	foreach ($this->c as $key => $value) {
		if(!strpos($key, "#")) {
		empty($this->updates) ? $this->updates = $key." = '".$value."'" : $this->updates .= ", ".$key." = '".$value."'";
		}
	}
  }
  
  function killMe($updir = '') {
  global $prefix;
  	$this->sql = "DELETE FROM ".$prefix."_".$this->tbl." WHERE id = ".$this->id;
  
  foreach($this->cfg as $key => $value) {
  	  if($value->type == "picture") {
		if(is_file($updir.$key.'_'.$this->id.'.jpg')) unlink($updir.$key.'_'.$this->id.'.jpg');
	  }
	}
  
  }
  
  function checkPhotoScaledUpload($id, $file, $uploaddir, $width) {
  global $prefix;
  $errorFlag = 0;
  
	$filename = $_FILES[$file]['tmp_name'];

		// Get new dimensions
		list($width_orig, $height_orig) = getimagesize($filename);
		$height = ($width / $width_orig) * $height_orig;
	  
		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		if($_FILES[$file]['type'] == "image/jpeg") $image = imagecreatefromjpeg($filename);
		if($_FILES[$file]['type'] == "image/pjpeg") $image = imagecreatefromjpeg($filename);
		if($_FILES[$file]['type'] == "image/gif") $image = imagecreatefromgif($filename);
		if($_FILES[$file]['type'] == "image/x-png") $image = imagecreatefrompng($filename);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

		//zaktualizowanie wpisu nazwy zdjecia w bazie
		$filename = $file.'_'.$id.'.jpg';
		$sql = "UPDATE ".$prefix."_".$this->tbl." SET ".$file." = '".$filename."' WHERE id = ".$id;

		if(!mysql_query($sql)) {
			$this->err->{$file} = "Problem with updating picture";
			return ($errorFlag = 1);
		}

		// Output
		if(!imagejpeg($image_p, $uploaddir.$filename, 100)) {
			 $this->err->{$file} = "Problem with saving picture";
			 $errorFlag = 1;
		}

		ImageDestroy($image_p);
		ImageDestroy($image);

  return $errorFlag;
}

  function checkTable() {
	$result = true;

	foreach ($this->c as $name => $value)
	  if(false === $this->checkField($name)) $result = false;
	  if($this->pic) {
	  	if(false === $this->checkFiles()) $result = false;
	  }
	  return $result;
  }
  
  function checkField($name) {
  $arg = trim($this->c->{$name});
  
	// NOT EMPTY
	if ($this->cfg->{$name}->not_empty && $arg == '') {
	  $this->err->{$name} = _EMPTYFIELD;
	  return false;
	}
	
	// IS NUMERIC
	if ($this->cfg->{$name}->numeric && !is_numeric($arg)) {
	  $this->err->{$name} = _Toniejestliczba;
	  return false;
	}
	
	// IS DATE
	if ($this->cfg->{$name}->is_date == 1 && !preg_match("/\d{2}-\d{2}-\d{4}/", $arg)) {
	  $this->err->{$name} = _ToniejestformatdatyDDMMRRRR;
	  return false;
	}
	
	// NOT EMPTY PICTURE
	if ($this->cfg->{$name}->type == "picture" && $this->cfg->{$name}->not_empty && $_FILES[$this->cfg->{$name}]['error'] == 4) {
	  $this->err->{$name} = "This field cannot be empty";
	  return false;
	}
  }

function checkFiles() {
  $result = true;

  	foreach($_FILES as $key => $value) {
		if($this->cfg->{$key}->not_empty && $_FILES[$key]['error'] == 4) {
			$this->err->{$key} = "Select picture";
			$result = false;
		}
		
		if($_FILES[$key]['error'] != 4) {
		
			if(!is_uploaded_file($_FILES[$key]['tmp_name'])) {
				$this->err->{$key} = "Select picture";
			$result = false;
			}
			if($_FILES[$key]['size'] > 1024000) { 
				$this->err->{$key} = "Picture is too big";
				$result = false;
			}
			if($_FILES[$key]['error'] != 0) {
				$this->err->{$key} = "Problem with picture uploading";
				$result = false;
			}
	
			if(!($_FILES[$key]['type'] == "image/jpeg" || $_FILES[$key]['type'] == "image/pjpeg" || $_FILES[$key]['type'] == "image/x-png" || $_FILES[$key]['type'] == "image/gif")) {
				$this->err->{$key} = "Not supported graphic format";
				$result = false;
			}
		}
  }
  return $result;
} 

	function showman($trick) {
		echo "<pre>";
		print_r($trick);
		echo "</pre>";
	}
 
}  

?>