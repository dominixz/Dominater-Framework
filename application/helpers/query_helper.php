<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright		Copyright (c) 2009 Gudagi
 * @license		http://gudagi.net/herbigniter/HI_user_guide/license.html
 * @link		http://gudagi.net/herbigniter/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter MySQL Query Helpers
 *
 * @package		HerbIgniter
 * @subpackage		Helpers
 * @category		Queries and MySQL
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/query_helper.html
 */

// ------------------------------------------------------------------------

    
        // bitvector mathematics
if(!function_exists('flag'))
{           
    function flag( $bit, $flag ) {
      $bit = (int) $bit;
      $flag = (int) $flag;
	    if ( $bit & $flag ) return true;
	    return false;
    }
}

if(!function_exists('on'))
{       
    function on( $bit, $flag ) {
      $bit = (int) $bit;
      $flag = (int) $flag;
	    return $bit & $flag;
    }
}

if(!function_exists('off'))
{       
    function off( $bit, $flag ) {
      $bit = (int) $bit;
      $flag = (int) $flag;
	    return $bit & ~($flag);
    }
}

if(!function_exists('bittoggle'))
{     
    function bittoggle( $bit, $flag ) {
      $bit = (int) $bit;
      $flag = (int) $flag;
	    return $bit ^ (1 << $flag);
    }
}

// String crappy crap

if(!function_exists('adt'))
{
        // Adds ` ticks to a list of fields seperated by , commas (fix by RainCT)
        // see also: adq, sq, msq
	function adt( $strlist ) {
                $stra = explode(',', str_replace('`', '', $strlist));
                if ( count($stra) == 1 ) return '`'.$stra[0].'`';
                foreach ( $stra AS $key => $value) {
                        $stra[$key] = ' `'.$value.'`';
                }
                return implode(',', $stra);
        }
}
    
if(!function_exists('adq'))
{	
        // Adds ' single quote to a list of fields seperated by , commas (fix by RainCT)
        // see also: adt, sq, msq
	function adq( $strlist ) {
                $stra = explode(',', str_replace("'", '', $strlist));
                if ( count($stra) == 1 ) return "'".$stra[0]."'";
                foreach ( $stra AS $key => $value) {
                        $stra[$key] = " '".$value."'";
                }
                return implode(',', $stra);
        }
}

if(!function_exists('sq'))
{	
	// Slash quotes: fixes \" and \' to be " and ' (the sourceforge bug)
        // see also: adt, adq, msq
	function sq( $str ) {
	    $str = str_replace("\\'", "'", $str);
	    $str = str_replace("\\\"", '"', $str);
	    return $str;
	}
}
	
if(!function_exists('msq'))
{
	// Make slash quotes: fixes " and ' to be \" and \' (to include in javascript)
        // see also: adt, sq, adq
	function msq( $str ) {
	    $str = str_replace("'", "\\'", $str);
	    $str = str_replace("\"", '\\\"', $str);
	    return $str;
	}
}
	
if(!function_exists('qs'))
{	
	// Make slash quotes: fixes "  to be \" (to include in javascript)
	// Fixes \n to be \\n
        // see also: adt, sq, adq
	function qs( $str ) {
	    $str = str_replace('"', "\\\"", $str);
	    $str = str_replace("\n", " ", $str);
	    return $str;
	}
}

//--------------------------------------------------------------------------------------------------------------
// Mysql "raw" functions -- to be used as an included function in files that aren't really linked to HerbIgniter
// We needed this once, so here it is.

if(!function_exists('err'))
{
    // Report all PHP/MySQL interface errors
    if ( !isset($err_file) ) $err_file = "unknown";
    function err( $die, $query="" ) {
        global $err_file;
        if ( isset($err_file) && strlen($err_file) > 0 ) echo 'File: ' . $err_file . '<br>';
        if ( strlen($query) > 0    ) echo 'Query:<br>' . $query . '<br>'; 
        die($die);
    }
}

if(!function_exists('mysql_get_last_id'))
{
    function mysql_get_last_id($table) {
     $q = "SELECT LAST_INSERT_ID() FROM $table";
     return mysql_num_rows(mysql_query($q));
    }
}
    
if(!function_exists('mysql_insert'))
{
    // Save an object into the database, create new by default.
    // Returns mysql_insert_id(); only relevant for auto increment keys; will not be accurate if you use it on
    // a table without an auto-incrementing primary key
    function mysql_insert( $table, $field, $value ) { global $target_db;
        $query = "INSERT INTO " . $table . "( " . adt($field) . ") VALUES (" . adq($value) . ");";
        $res = mysql_query($query,$target_db) or err(mysql_error(),$query);
	return $res;
    }
}

if(!function_exists('mysql_set'))
{    
    // Updates an object in the database by id
    function mysql_set( $table, $id, $field, $value ) {  global $target_db;
	$query = "UPDATE " . $table . " SET `" . $field . "`='" . $value . "' WHERE id = '" . $id . "';";
	$res = mysql_query($query,$target_db) or err(mysql_error(),$query);
        return $res;
    }
}

if(!function_exists('mysql_now'))
{   
    // Stores the right now time
    function mysql_now( $table, $id, $field ) {  global $target_db;
	$query = "UPDATE " . $table . " SET `" . $field . "`=NOW() WHERE id = '" . $id . "';";
	$res = mysql_query($query,$target_db) or err(mysql_error(),$query);
        return $res;        
    }
}

if(!function_exists('mysql_activate'))
{       
    // Bitvector activation
    function mysql_activate( $table, $id, $field, $value ) {  global $target_db;
        $query = "SELECT * FROM " . $table . " WHERE (id = '" . $id ."');";
	$res = mysql_query($query,$target_db) or err(mysql_error(),$query);   
        $row = mysql_fetch_assoc( $res );
	if ( flag($row[$field], $value) ) return;
        $flag = $row[$field] | $value;
        mysql_set($table,$id,$field,$flag);
    }
}

if(!function_exists('mysql_deactivate'))
{       
    // Bitvector deactivation
    function mysql_deactivate( $table, $id, $field, $value ) {  global $target_db;
        $query = "SELECT * FROM " . $table . " WHERE HC = '" . $id ."';";
	$res = mysql_query($query,$target_db) or err(mysql_error(),$query); 
        $row = mysql_fetch_assoc( $res );
	if ( !flag($row[$field], $value) ) return;
        $flag = $row[$field] & ~($value);
        mysql_set($table,$id,$field,$flag);        
    }
}

if(!function_exists('mysql_toggle'))
{   
   // Bitvector toggle
   function mysql_toggle( $table, $id, $field, $value ) {  global $target_db;
        $query = "SELECT * FROM `" . $table . "` WHERE id = '" . $id ."';";
	$res = mysql_query($query,$target_db) or err(mysql_error(),$query); 
        $row = mysql_fetch_assoc( $res );
	if ( flag($row[$field], $value) ) deactivate($table,$id,$field,$value);
        else activate($table,$id,$field,$value);
    }
}

if(!function_exists('mysql_toggle'))
{   
    // Bitvector value request
    function mysql_flag_value( $table, $id, $field ) {  global $target_db;
        $query = "SELECT * FROM `" . $table . "` WHERE id = '" . $id ."';";
	$res = mysql_query($query,$target_db) or err(mysql_error(),$query); 
        $row = mysql_fetch_assoc( $res );
        $flag = $row[$field];
        return ( $flag );       
    }
}

if(!function_exists('mysql_has'))
{
    // Bitvector value assertion
    function mysql_has( $table, $id, $field, $value ) {  global $target_db;
        $query = "SELECT * FROM `" . $table . "` WHERE id = '" . $id ."';";
	$res = mysql_query($query,$target_db) or err(mysql_error(),$query); 
        $row = mysql_fetch_assoc( $res );
        $flag = $row[$field];
        return ( $flag & $value );
    }
}

if(!function_exists('mysql_add_field'))
{
    // Adds a field to a table
    function mysql_add_field( $table, $field, $type ) { global $target_db;
        $query = "ALTER TABLE `" . $table . "` ADD `" . $field . "` " . $type . ";";
        $res = mysql_query($query,$target_db) or err(mysql_error(),$query);
        return $res;
    }
}

if(!function_exists('mysql_find'))
{    
    // Finds the first item in the table with an id and a value
    function mysql_find( $table, $id, $value, $other="" ) { global $target_db;  // $Herb &= get_instance();
 	    $query = "SELECT * FROM " . $table . " WHERE `" . $id . "` = '" . $value . "' " . $other . ";";
	    $res = mysql_query($query,$target_db) or err(mysql_error(),$query);
	    if ( mysql_num_rows($res) > 0 ) return mysql_fetch_assoc($res);
	    return NULL;       
    }
}

if(!function_exists('mysql_find_like'))
{    
    // Finds items in the table with an id and a value
    function mysql_find_like( $table, $id, $value, $sort_or_limit="" ) { global $target_db;
 	    $query = "SELECT * FROM " . $table . " WHERE `" . $id . "` = '" . $value . "' " . $sort_or_limit . ";";
	    $res = mysql_query($query,$target_db) or err(mysql_error(),$query);
	    if ( mysql_num_rows($res) > 0 ) return ($res);
	    return NULL;       
    }
}

if(!function_exists('mysql_find_month'))
{
    function mysql_find_month( $table, $field, $month, $year, $sort_or_limit="" )  { global $target_db;
 	    $query = "SELECT * FROM " . $table
	           . " WHERE EXTRACT(`' . $field . '`,YEAR) = '" . $year .
		   "' AND EXTRACT(`' . $field . '`,MONTH) = '" . $month . "' " . $sort_or_limit . "; ";
	    $res = mysql_query($query,$target_db) or err(mysql_error(),$query);
	    if ( mysql_num_rows($res) > 0 ) return ($res);
	    return NULL;       
    }
}
    
if(!function_exists('mysql_find_not_like'))
{
    // Finds items in the table not matching an id and a value
    function mysql_find_not_like( $table, $id, $value, $sort_or_limit="" ) { global $target_db;
 	    $query = "SELECT * FROM " . $table . " WHERE `" . $id . "` <> '" . $value . "' " . $sort_or_limit . ";";
	    $res = mysql_query($query,$target_db) or err(mysql_error(),$query);
	    if ( mysql_num_rows($res) > 0 ) return ($res);
	    return NULL;       
    }
}
    
if(!function_exists('mysql_find_all'))
{
    // Finds all items in the table (future plateau danger zone)
    function mysql_find_all( $table, $sort_or_limit="" ) { global $target_db;
 	    $query = "SELECT * FROM " . $table . " " . $sort_or_limit . ";";
	    $res = mysql_query($query,$target_db) or err(mysql_error(),$query);
	    if ( mysql_num_rows($res) > 0 ) return ($res);
	    return NULL;       
    }
}

if(!function_exists('mysql_get_related'))
{   
    // finds all related records with a specific table, field and value
    function mysql_get_related( $table, $id, $val ) { global $target_db;
        $query = "SELECT * FROM " . $table . " WHERE " . $id . " = '" . $val ."';";
	    $res = mysql_query($query,$target_db) or err(mysql_error(),$query);
        if ( mysql_num_rows($res) > 0 ) return $res;
        return NULL;        
    }
}
    
if(!function_exists('mysql_find_sorted'))
{
    // return a certain number of sorted records from a particular table
    function mysql_find_sorted( $table, $order_by, $limit, $asc_desc="DESC" ) { global $target_db;
      $query = "SELECT * FROM " . $table . " ORDER BY " . $order_by . " " . $asc_desc . " LIMIT " . $limit . ';';
          $res = mysql_query($query,$target_db) or err(mysql_error(),$query);
      return $res;
    }
}


if(!function_exists('mysql_to_array'))
{
 // Converts a mysql resource to a numerically indexed array  (qsort friendly)
function mysql_to_array($res) {
    $tab=array();
    $i=0;
    if ( not_null($res) )
    while ( $r=mysql_fetch_assoc($res) ) $tab[$i++]=$r;
    return $tab;
}
}

// -------------------- herbigniter versions of common mysql queries

if(!function_exists('find_like'))
{
    // Finds items in the table with an id and a value
    function find_like( $table, $id, $value, $sort_or_limit="" ) { $Herb =& get_instance();
           $query = "SELECT * FROM " . $table . " WHERE `" . $id . "` = '" . $value . "' " . $sort_or_limit . ";";
	   $res = $Herb->db->query($query);
	   return $res->result_array();
    }
}

if(!function_exists('find_similar'))
{
    // Finds items in the table with an id and a substring matching value
    function find_similar( $table, $id, $value, $sort_or_limit="" ) { $Herb =& get_instance();
           $query = "SELECT * FROM " . $table . " WHERE `" . $id . "` LIKE '%" . $value . "%' " . $sort_or_limit . ";";
	   $res = $Herb->db->query($query);
	   return $res->result_array();
    }
}
    
if(!function_exists('find_all'))
{    
    // Finds all items in the table (future plateau danger zone)
    function find_all( $table, $sort_or_limit="" ) { $Herb =& get_instance();
 	    $query = "SELECT * FROM " . $table . " " . $sort_or_limit . ";";
	    $res = $Herb->db->query($query);
	    if ( $res->num_rows($res) > 0 ) return ($res->result_array());
	    return NULL;       
    }
}

if(!function_exists('find_month'))
{  
    function find_month( $table, $field, $month, $year, $sort_or_limit="" )  { $Herb =& get_instance();
 	   
 	   //get date range--if it's month finder, then it's a span of one month
		
	   $startdate = date('Y-m-d H:i:s', mktime(0,0,0, $month, '01', $year));
	   $enddate = date('Y-m-d H:i:s',mktime(0,0,0, ($month + 1), '01', $year));
 	   
 	   $query = "SELECT * FROM " . $table
	           . " WHERE `" . $field . "` >= '" . $startdate 
		    . "' AND `" . $field . "` < '" . $enddate . "' " . $sort_or_limit . "; ";
		
		
		
	 	//echo 'month: '.$month.'<br>year: '.$year.'<br />'.$query.'<br>';
		   
	   $res = $Herb->db->query($query);
	   return $res->result_array();
    }
}

if(!function_exists('insert'))
{      
    // Save an object into the database, create new by default.
    // Returns mysql_insert_id(); only relevant for auto increment keys; will not be accurate if you use it on
    // a table without an auto-incrementing primary key
   function insert( $table, $field, $value ) {  $Herb =& get_instance();
        $query = "INSERT INTO " . $table . "( " . adt($field) . ") VALUES (" . adq($value) . ");";
	$res = $Herb->db->query($query);
	return $this->db->insert_id();
    }
}

if(!function_exists('new_id'))
{    
    function new_id( $table, $field="id" ) {  $Herb =& get_instance();
 	$query = "SELECT * FROM " . $table . " ORDER BY `" . $field . "` DESC LIMIT 1;";
	$res = $Herb->db->query($query);
	$res = $res->result_array();
	return ( $res[$field]+1 );
    }
}

if(!function_exists('multiinsert'))
{    
     function multiinsert( $table ) {  $Herb =& get_instance();
        $args = func_get_args();
	$num = func_num_args();
	if ( $num < 3 ) return;
        for ( $i=1; $i < $num; $i+=2 ) {
		if ( !isset($fields) ) $fields = '`'.$args[$i].'`';
		else
		$fields .= ',`' . $args[$i] . '`';

		if ( !isset($values) ) $values = $Herb->db->escape($args[$i+1]);
		else
		$values .= ',' . $Herb->db->escape($args[$i+1]);
	}
        $query = "INSERT INTO " . $table . "( " . adt($fields) . ") VALUES ( " . $values . " )";
	//echo 'multiinsert: ' . $query;
	$res = $Herb->db->query($query);
	return;
    }
}

if(!function_exists('multiupdate'))
{  
   function multiupdate( $table, $id ) {  $Herb =& get_instance();
        $args = func_get_args();
	$num = func_num_args();
	if ( $num < 4 ) return;
        for ( $i=2; $i < $num; $i+=2 ) {
		if ( !isset($fieldvalues) ) $fieldvalues = '`'.$args[$i].'`='.$Herb->db->escape($args[$i+1]);
		else
		$fieldvalues .= ',`'.$args[$i].'`='.$Herb->db->escape($args[$i+1]);
	}
        $query = "UPDATE " . $table . " SET " . $fieldvalues . " WHERE `id`='" . $id . "' ";
	//echo 'multiupdate: ' . $query;
	$res = $Herb->db->query($query);
	return;
    }
}

if(!function_exists('set'))
{  
    // Updates an object in the database by id
    function set( $table, $id, $field, $value ) {  $Herb =& get_instance();
	$query = "UPDATE " . $table . " SET `" . $field . "`=" . $Herb->db->escape($value) . " WHERE id = '" . $id . "';";
	$res = $Herb->db->query($query);
	return $res;//$res->result_array();
    }
}
    
if(!function_exists('now'))
{  
    // Stores the right now time
    function now( $table, $id, $field ) {  $Herb =& get_instance();
	$query = "UPDATE " . $table . " SET `" . $field . "`=NOW() WHERE id = '" . $id . "';";
	$res = $Herb->db->query($query);
	return $res->result_array();
    }
}

if(!function_exists('activate'))
{  
    // Bitvector activation
    function activate( $table, $id, $field, $value ) {   $Herb =& get_instance();
        $query = "SELECT * FROM " . $table . " WHERE (id = '" . $id ."');";
        $res = $Herb->db->query($query);
        $row = $res->result_array();
	if ( flag($row[$field], $value) ) return;
        $flag = $row[$field] | $value;
        set($table,$id,$field,$flag);
    }
}

if(!function_exists('deactivate'))
{  
    // Bitvector deactivation
    function deactivate( $table, $id, $field, $value ) {   $Herb =& get_instance();
        $query = "SELECT * FROM " . $table . " WHERE HC = '" . $id ."';";
        $res = $Herb->db->query($query);
        $row = $res->result_array();
	if ( !flag($row[$field], $value) ) return;
        $flag = $row[$field] & ~($value);
        set($table,$id,$field,$flag);        
    }
}

if(!function_exists('toggle'))
{  
   // Bitvector toggle
   function toggle( $table, $id, $field, $value ) {  $Herb =& get_instance();
        $query = "SELECT * FROM `" . $table . "` WHERE id = '" . $id ."';";
        $res = $Herb->db->query($query);
        $row = $res->result_array();
	if ( flag($row[$field], $value) ) deactivate($table,$id,$field,$value);
        else activate($table,$id,$field,$value);
   }
}

if(!function_exists('flag_value'))
{  
    // Bitvector value request
    function flag_value( $table, $id, $field ) {   $Herb =& get_instance();
        $query = "SELECT * FROM `" . $table . "` WHERE id = '" . $id ."';";
        $res = $Herb->db->query($query);
        $row = $res->result_array();
        $flag = $row[$field];
        return ( $flag );       
    }
}

if(!function_exists('has'))
{      
    // Bitvector value assertion
    function has( $table, $id, $field, $value ) {   $Herb =& get_instance();
        $query = "SELECT * FROM `" . $table . "` WHERE id = '" . $id ."';";
        $res = $Herb->db->query($query);
        $row = $res->result_array();
        $flag = $row[$field];
        return ( $flag & $value );
    }
}

if(!function_exists('add_field'))
{      
    // Adds a field to a table
    function add_field( $table, $field, $type ) {  $Herb =& get_instance();
        $query = "ALTER TABLE `" . $table . "` ADD `" . $field . "` " . $type . ";";
	$res = $Herb->db->query($query);
	return $res->result_array();
    }
}

if(!function_exists('find'))
{      
    // Finds the first item in the table with an id and a value
    function find( $table, $id, $value, $other="" ) {  $Herb =& get_instance();
 	    $query = "SELECT * FROM " . $table . " WHERE `" . $id . "` = '" . $value . "' " . $other . ";";
    	    $res = $Herb->db->query($query);
	    if ( $res->num_rows() > 0 ) { $res = $res->result_array(); return $res[0]; }
	    return NULL;       
    }
}

if(!function_exists('delete'))
{
    // Use with caution
    function delete( $table, $id, $matching ) { $Herb =& get_instance();
        $query = 'DELETE FROM ' . $table . ' WHERE `' . $id . "`='" . $matching . "'";
	$res = $Herb->db->query($query);
        return $res;
    }
}

if (!function_exists('yes')) {
    //Returns true when a yes/no is set to Y
    function yes( $table, $id, $field, $ynfield ) {
	$us = find($table,$id,$field);
	if ( is_null($us) ) return false;
	if ( $us[$ynfield] == 'Y'
	  || $us[$ynfield] == 'y'
	  || $us[$ynfield] == 'yes'
	  || $us[$ynfield] == 'YES'
	  || $us[$ynfield] == 'Yes'
	  || $us[$ynfield] > 0 ) return true;
	return false;
    }
}

if (!function_exists('no')) {
    //Returns true when a yes/no is set to Y
    function no( $table, $id, $field, $ynfield ) {
	$us = find($table,$id,$field);
	if ( is_null($us) ) return true;
	if ( $us[$ynfield] == 'Y'
	  || $us[$ynfield] == 'y'
	  || $us[$ynfield] == 'yes'
	  || $us[$ynfield] == 'YES'
	  || $us[$ynfield] == 'Yes'
	  || $us[$ynfield] > 0 ) return false;
	return true;
    }
}

/* End of file query_helper.php */
/* Location: ./system/helpers/query_helper.php */