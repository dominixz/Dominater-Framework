<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * The function that check request is from AJAX or not.
 *
 */
 
function is_ajax() 
{
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

/* End of file is_ajax.php */
/* Location: ./system/plugins/is_ajax.php */