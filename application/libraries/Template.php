<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {

		var $template_data = array();
		
		function set($name, $value)
		{
			$this->template_data[$name] = $value;
		}
	
		function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
		{               
			$this->CI =& get_instance();
			$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));			
			return $this->CI->load->view($template, $this->template_data, $return);
		}
		
		function view($view = '' , $view_data = array(), $return = FALSE) {
			$this->CI =& get_instance();
			
			# Dominixz Hack #
			$controller = $this->CI->router->class;
			$method = $this->CI->router->method;
			
			$base_template = $this->_base_template($controller,$method);
			# End DominixZ Hack #
			
			$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
			
			
			return $this->CI->load->view($base_template, $this->template_data, $return);
		}
		
		function response()
		{
			$this->CI =& get_instance();
			
			$data = $this->CI->output->get_output();
			
			$controller = $this->CI->router->class;
			$method = $this->CI->router->method;
				
			$view = "$controller/$method";
				
			if(empty($data) && !file_exists(APPPATH .'views/'. $view.'.php'))
			{
				return;
			}
			
			if(!empty($data) && !is_array($data))
			{
				return;
			}
			
			if(is_ajax())
			{
				 $this->CI->output->set_output(json_encode($data));	
			}
			else
			{
				$base_template = $this->_base_template($controller,$method);
				
				 $this->CI->output->set_output('');
				
				$this->set('contents', $this->CI->load->view($view, $data, TRUE));
				
				return $this->CI->load->view($base_template, $this->template_data);			
			}
			
		}
		
		function _base_template($controller,$method)
		{
			$this->CI =& get_instance();
			$template = $this->CI->config->item('template');
			
			$base_template = $template['dir'];
			
			if(isset($template[$controller][$method]))
			{
				$base_template .= $template[$controller][$method];
			}
			else if(isset($template[$controller]['default']))
			{
				$base_template .= $template[$controller]['default'];
			}
			else
			{
				$base_template .= $template['default'];
			}
			
			return $base_template;
		}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */