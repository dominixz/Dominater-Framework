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
 * HerbIgniter Persistent Hashing Helpers
 *
 * @package		HerbIgniter
 * @subpackage		Helpers
 * @category		Mixed Bag of Tricks
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/data_helper.html
 */

// ------------------------------------------------------------------------

if(!function_exists('not_null'))
{
	function not_null( $a ) { return !is_null($a); }
}

if(!function_exists('dropzerolen'))
{  
   // Drops zero length strings from a string array, returns new array
   function dropzerolen( $strarray ) {
        $c = count($strarray); $x=0;
	for ( $i=0; $i<$c; $i++ ) if ( strlen($strarray[$i]) > 0 ) $newarray[$x++] = $strarray[$i];
	return $newarray;
   }
}

if(!function_exists('interpolate'))
{  
function interpolate( $min, $max, $ratio ) {
      return ( $min + ($max - $min / $ratio == 0 ? 1 : $ratio ) );	
   }
}
   

/*
   function array_push( $stack, $value ) {
      if ( !is_array($stack) ) $stack=array();
      $c = count($stack);
      if ( $c == 0 ) $stack[0] = $value;
      else $stack[$c] = $value;
      return $stack;
   }
   
   function array_pop( $stack ) {
      $c = count($stack);
      $value = $stack[$c-1];
      unset($stack[$c-1]);
      return $value;
   } 
*/



/* End of file data_helper.php */
/* Location: ./system/helpers/data_helper.php */