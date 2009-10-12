<?php
class Setup extends DM_Controller {
	
	function Setup()
	{
		parent::DM_Controller();
		$this->load->helper('file');
		$this->load->helper('form');
		$this->load->helper('url');
	}
	
	function index()
	{
		$this->load->view('setup/index');
	}
	
	function process()
	{
		$this->_database($_POST['database']);
		$this->_autoload();
		$this->_htaccess();
		$this->_removeIndexFromConfig();
		$this->_removeSetup();
		
		echo "Finished Setup. Please remove application/controller/setup.php";
		
	}
	
	function _database($database_config)
	{
		
		$replace_array = array();
		
		foreach($database_config as $key => $value)
		{
			$default_config = "\$db['default']['$key'] = \"\"";
			$new_config = "\$db['default']['$key'] = \"$value\"";
			$replace_array[$default_config] = $new_config;
		}
		
		$database_path = APPPATH.'config/database.php';
		
		$this->_replaceFileContent($database_path,$replace_array);
		echo "Setup Database Complete<br/>";
	}
		
	function _autoload()
	{
		$autoload_path = APPPATH.'config/autoload.php';
		$this->_replaceFileContent($autoload_path,array("\$autoload['libraries'] = array();" => "\$autoload['libraries'] = array('database','datamapper');"));
		echo "Change Autoload Completed<br/>";
	}
	
	function _htaccess()
	{
		$base_folder_name = str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
		
		$htaccess = "# Turn on URL rewriting
RewriteEngine On
# Put your installation directory here:
RewriteBase $base_folder_name
# Allow these directories and files to be displayed directly:
# - index.php (DO NOT FORGET THIS!)
# - robots.txt
# - favicon.ico
# - public (folder that contain javascript and css folder)
RewriteCond $1 ^(index\.php|robots\.txt|favicon\.ico|sitemap\.xml|public)
# No rewriting
RewriteRule ^(.*)$ - [PT,L]
# Rewrite all other URLs to index.php/URL
RewriteRule ^(.*)$ index.php/$1 [PT,L]";
							
		write_file(".htaccess",$htaccess);
		echo "Write .Htaccess File Complete<br/>";
	}
	
	function _removeIndexFromConfig()
	{
		$config_path = APPPATH.'config/config.php';
		
		$encryption_key = md5(rand(1,9999999));
		
		$this->_replaceFileContent($config_path,array(	"\$config['index_page'] = \"index.php\";" => "\$config['index_page'] = \"\";",
																				"\$config['encryption_key'] = \"\";" => "\$config['encryption_key'] = \"$encryption_key\";"));

		echo 'Remove index.php from $config["index_page"]<br/>';
	}
	
	function _replaceFileContent($filepath,$replace_array)
	{
		$content = read_file($filepath);
		$new_content = strtr($content,$replace_array);
		write_file($filepath,$new_content);
	}
	
	function _removeSetup()
	{
		deleteDirectory(APPPATH."views/setup");
		echo "Remove View completed";
	} 
	
}
function deleteDirectory($dir) {
	if (!file_exists($dir)) return true;
	if (!is_dir($dir) || is_link($dir)) return unlink($dir);
		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			if (!deleteDirectory($dir . "/" . $item)) {
				chmod($dir . "/" . $item, 0777);
				if (!deleteDirectory($dir . "/" . $item)) return false;
			};
		}
		return rmdir($dir);
}