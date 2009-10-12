<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb and Maurice Rickard
 * @copyright		Copyright (c) 2009 Gudagi
 * @license		http://gudagi.net/herbigniter/HI_user_guide/license.html
 * @link		http://gudagi.net/herbigniter/
 * @since		Version 1.0
 * @filesource
|--------------------------------------------------------------------------
| Code source notes
|--------------------------------------------------------------------------
|
| Integration with Capcha.  To get this helper working, you must retreive
| a unique public key from Captcha.  Or, you can use ours (not recommended).
| http://www.captcha.net/
|
*/
if ( ! function_exists('generate_captcha'))
{
	function generate_captcha(){
		global $captcha_public_key;
		require_once('recaptchalib.php');
		if ( !isset($captcha_public_key) )
		$captcha_public_key = "6Le9SAgAAAAAAN4PQev2C2hPZAp1cAKNHEeGlqje"; //This is a global key. It will work across all domains. 

		return recaptcha_get_html($captcha_public_key);
	}
}

// Form validation function
if ( ! function_exists('validate_captcha'))
{	
	function validate_captcha($posts){
		global $captcha_private_key;
		require_once('recaptchalib.php');

		if ( !isset($captcha_private_key) )
		$captcha_private_key = "6Le9SAgAAAAAAI17x3P_AkcpLJdDHSSmoSsPldWL";
		
		$resp = recaptcha_check_answer($captcha_private_key, $_SERVER["REMOTE_ADDR"],
					       $this->input->post("recaptcha_challenge_field"),
					       $this->input->post("recaptcha_response_field"));
		
		if (!$resp->is_valid) {
			$this->validation->set_message('validate_captcha', 'The %s doesn\'t match the word in the image.');
			return false;// $resp->error;
		} else {
			return true;
		}	
	}
}

/* End of file recaptcha_helper.php */
/* Location: ./system/helpers/recaptcha_helper.php */