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
 * @category		Javascript
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/audio_helper.html
 */

// ------------------------------------------------------------------------

if ( !function_exists( 'HI_getid3' ) ) {
function HI_getid3() {}
require_once( 'getid3/getid3.php');
}

// Returns an array indexed by "artist" "title" or "album"

if ( !function_exists('mp3titles') ) {
function mp3titles( $file ) {
	// Title the mp3s
 // Create ID3 object
 $getid3 = new getID3;
 $getid3->encoding = 'ISO 8859-1';

        $getid3->Analyze( realpath(APPPATH . "../../" ) . "/" . $file);
        $artist = $title = '';
            if (@$getid3->info['tags']) {
            foreach ($getid3->info['tags'] as $tag => $tag_info) {
                if (@$getid3->info['tags'][$tag]['artist'] || @$getid3->info['tags'][$tag]['title']) {
                    $artist = @$getid3->info['tags'][$tag]['artist'][0];
                    $title  = @$getid3->info['tags'][$tag]['title'] [0];
                    $album  = @$getid3->info['tags'][$tag]['album'] [0];
                    break;
                }
            }
        }
	
   if ( isset($album) )
   return array( "artist" => $artist, "title" => $title, "album" => $album );
   else
   return array( "artist" => $artist, "title" => $title );
}
}

/* End of file audio_helper.php */
/* Location: ./system/helpers/audio_helper.php */