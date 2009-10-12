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
 * HerbIgniter Security Helpers
 *
 * @package		HerbIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/social_helper.html
 */

// ------------------------------------------------------------------------

/**
 * XSS Filtering
 *
 * @access	public
 * @param	string
 * @param	bool	whether or not the content is an image file
 * @return	string
 */

if ( ! function_exists('track_with_ga'))
{
	    function track_with_ga( $tracker_id ) {
	     return '    
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("' . $tracker_id . '");
pageTracker._trackPageview();
} catch(err) {}</script>
';
    }

}

// Facebook et al embedded url niceness
if ( ! function_exists('meta_tagger'))
{
function meta_tagger( $title, $image_full_url, $description, $keywords='' ) {
return '<meta name="title" content="' . $title . '"><meta name="medium" content="medium_type">
<meta name="description" content="' . $description . '">' . ( !empty($keywords) ? '
<meta name="keywords" content="' . $keywords . '">' : '' ) . 
'<link rel="image_src" href="' . $image_full_url . '">';	
}
}


if ( ! function_exists('tag_cloud'))
{
  function tag_cloud($tags, $search_url_prefix ) {
	$output = "";
        // $tags is the array
       
        arsort($tags);
       
        $max_size = 32; // max font size in pixels
        $min_size = 12; // min font size in pixels
       
        // largest and smallest array values
        $max_qty = max(array_values($tags));
        $min_qty = min(array_values($tags));
       
        // find the range of values
        $spread = $max_qty - $min_qty;
        if ($spread == 0) { // we don't want to divide by zero
                $spread = 1;
        }
       
        // set the font-size increment
        $step = ($max_size - $min_size) / ($spread);
       
        // loop through the tag array
        foreach ($tags as $key => $value) {
                // calculate font-size
                // find the $value in excess of $min_qty
                // multiply by the font-size increment ($size)
                // and add the $min_size set above
                $size = round($min_size + (($value - $min_qty) * $step));
       
                $output .= '<a href="' . $search_url_prefix . urlencode($key) . '" style="font-size: ' . $size . 'px" title="' . $value . ' things tagged with ' . $key . '">' . $key . '</a> ';
        }
	
	return $output;
  }
}






/* End of file social_helper.php */
/* Location: ./system/helpers/social_helper.php */