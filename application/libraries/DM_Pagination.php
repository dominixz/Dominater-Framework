<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class DM_Pagination extends CI_Pagination {
	function DM_Pagination()
	{
		parent::CI_Pagination();
	}
	function create_pagination($url='',$count=0,$offset=0,$local=TRUE)
	{
		$CI =& get_instance();
		$this->base_url = $local ? $CI->config->site_url($url) : $url;
		$this->total_rows = $count;
		return $this->create_links();
	}
}

/* End of file application_pagination.php */
/* Location: ./system/application/libraries/application_pagination.php */