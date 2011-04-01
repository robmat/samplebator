<?php

if (eregi("sql_layer.php",$_SERVER['PHP_SELF'])) {
    Header("Location: ../index.php");
    die();
}

/*
 * sql_connect($host, $user, $password, $db)
 * returns the connection ID
 */

class ResultSet {
	var $result;
	var $total_rows;
	var $fetched_rows;

	function set_result( $res ) {
		$this->result = $res;
	}

	function get_result() {
		return $this->result;
	}

	function set_total_rows( $rows ) {
		$this->total_rows = $rows;
	}

	function get_total_rows() {
		return $this->total_rows;
	}

	function set_fetched_rows( $rows ) {
		$this->fetched_rows = $rows;
	}

	function get_fetched_rows() {
		return $this->fetched_rows;
	}

	function increment_fetched_rows() {
		$this->fetched_rows = $this->fetched_rows + 1;
	}
}
	


function sql_connect($host, $user, $password, $db)
{
        $dbi=@mysql_connect($host, $user, $password);
        mysql_select_db($db);
        //mysql_query( "SET CHARACTER SET latin1" );
        mysql_query( "SET character_set_results = 'latin1'" );
        return $dbi;
}


function sql_logout($id)
{
        $dbi=@mysql_close($id);
        return $dbi;
}


/* 
 * sql_query($query, $id)
 * executes an SQL statement, returns a result identifier
 */

function sql_query($query, $id)
{
        $res=@mysql_query($query, $id);
        return $res;
}       
        
/*  
 * sql_num_rows($res)
 * given a result identifier, returns the number of affected rows
 */  

function sql_num_rows($res)
{
       $rows=mysql_num_rows($res);
       return $rows;      
}                                    
                                     
/*                                   
 * sql_fetch_row($res,$row)           
 * given a result identifier, returns an array with the resulting row  
 * Needs also a row number for compatibility with PostgreSQL           
 */                                  
                                     
function sql_fetch_row(&$res, $nr)    
{                                    
        $row = mysql_fetch_row($res);
        return $row;        
}                                    
                                     
/*                                   
 * sql_fetch_array($res,$row)        
 * given a result identifier, returns an associative array             
 * with the resulting row using field names as keys.                   
 * Needs also a row number for compatibility with PostgreSQL.          
 */                                  
                                     
function sql_fetch_array(&$res, $nr)  
{                                        
        $row = array();
        $row = mysql_fetch_array($res);
        return $row;
}

function SQL_fetch_object(&$res, $nr)
{                                    
global $dbtype;                      
    $row = mysql_fetch_object($res);
	if($row) return $row;
	else return false;
}

?>
