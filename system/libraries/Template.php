<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
	var $template_data = array();
	var $template_path;

	function Template()
	{
		$this->CI =& get_instance();
		$template = $this->CI->config->item('template');
		$this->template_path = "{$template['dir']}{$template['default']}";
	}
	
	function set($name, $value) {
		$this->template_data[$name] = $value;
	}

	function load($template = '', $view = '' , $view_data = array(), $return = FALSE) {
		$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
		return $this->CI->load->view($template, $this->template_data, $return);
	}

	// load a default template 'template.php'
	function view($view = '' , $view_data = array(), $return = FALSE) {	
		$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
		return $this->CI->load->view($this->template_path, $this->template_data, $return);
	}
	
	function auto($view_data = array(), $return = FALSE) {	
		$view = "{$this->CI->router->class}/{$this->CI->router->method}";
		$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
		return $this->CI->load->view($this->template_path, $this->template_data, $return);
	}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */