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
 * @category		Hash Codes
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/hash_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Write File Atomically
 *
 * Dumps a file avoiding concurrency issues on Linux, sorry Windows
 * (ok, I'm not sorry, Windows is!)
 *
 * NOTE: This function also appears in file_helper.php
 *
 * @access	public
 * @param	string	path to file
 * @param	string	content
 * @return	string
 */	
if ( ! function_exists('file_put_contents_atomic'))
{
    //file_put_contents() will cause concurrency problems - that is, it doesn't write files atomically (in a single operation), which sometimes means that one php script will be able to, for example, read a file before another script is done writing that file completely.
    //The following function was derived from a function in Smarty (http://smarty.php.net) which uses rename() to replace the file - rename() is atomic on Linux.
    //On Windows, rename() is not currently atomic, but should be in the next release. Until then, this function, if used on Windows, will fall back on unlink() and rename(), which is still not atomic...
  
    define("FILE_PUT_CONTENTS_ATOMIC_TEMP", dirname(__FILE__)."/cache");
    define("FILE_PUT_CONTENTS_ATOMIC_MODE", 0777);    

    function file_put_contents_atomic($filename, $content) {  
      $temp = tempnam(FILE_PUT_CONTENTS_ATOMIC_TEMP, 'temp');
      if (!($f = @fopen($temp, 'wb'))) {
        $temp = FILE_PUT_CONTENTS_ATOMIC_TEMP
                . DIRECTORY_SEPARATOR
                . uniqid('temp');
        if (!($f = @fopen($temp, 'wb'))) {
            trigger_error("file_put_contents_atomic() : error writing temporary file '$temp'", E_USER_WARNING);
            return false;
        }
      }  
      @fwrite($f, $content);
      @fclose($f);
      if (!@rename($temp, $filename)) {
           @unlink($filename);
           @rename($temp, $filename);
      }  
      @chmod($filename, FILE_PUT_CONTENTS_ATOMIC_MODE);   
      return true;   
    }
}

if(!function_exists('hash_code'))
{ 
    // Generates a unique 32 bit string based on previous executions
    // Requiries a writeable directory 'hashes' to store a flatfile of the hash codes
    // URL-safe hashing only letters Aa-Zz and 0-9
    // Optional parameters: define a set, for multiple exclusive hash sets,
    //                      define a hash length, defaulting to 254 chars    
    // 1.55409285284366e+60 unique values
    function hash_code( $codeset = "1", $hashlength = 254 ) {
             $fn = "hashes/Hashes_" . $codeset . ".txt";
             
        if ( file_exists($fn) )
        $previous = file_get_contents($fn);
        else $previous = "";
        $hashcodes = explode("\n",$previous);

        $found = 1;
        while ( $found > 0 ) {
         // generate a new hash
         $newcode = "";
         for ( $x = 0; $x < $hashlength; $x++ ) {
             if ( rand(0,1) == 1 ) {
                          $newcode = $newcode . chr(rand(48,57));
             } else
             if ( rand(0,1) == 1 ) {
                          $newcode = $newcode . chr(rand(65,90));
             } else       $newcode = $newcode . chr(rand(97,122));
          }
          $found = 0; // check for duplicates, each must be unique
          $array_length = count($hashcodes);
          for ( $y = 0; $y < $array_length; $y++ ) {
             if ( strcmp( $hashcodes[$y], $newcode ) == 0 ) $found++;
          }
        } 
        $hashcodes[] = $newcode; 
        file_put_contents_atomic($fn,implode("\n",$hashcodes));
        return $newcode;
    }
}
    
if(!function_exists('hash_temp'))
{ 
    function hash_temp( $hashlength = 254 ) {

         $newcode = "";
         for ( $x = 0; $x < $hashlength; $x++ ) {
             if ( rand(0,1) == 1 ) {
                          $newcode = $newcode . chr(rand(48,57));
             } else
             if ( rand(0,1) == 1 ) {
                          $newcode = $newcode . chr(rand(65,90));
             } else       $newcode = $newcode . chr(rand(97,122));
          }
	  
        $hashcodes[] = $newcode; 
        return $newcode;
    }
}

if(!function_exists('hash_key'))
{ 
function hash_key( $length=255 ) { return "id VARCHAR(" . $length . ")"; }
}

if(!function_exists('hash_ref'))
{ 
function hash_ref( $name='id', $length=255 ) { return $name . " VARCHAR(" . $length . ")"; }
}


/* End of file hash_helper.php */
/* Location: ./system/helpers/hash_helper.php */