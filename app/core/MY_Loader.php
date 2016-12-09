<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Loader extends CI_Loader
{
	/**
	 * Constructor
	 *
	 * Sets the path to the view files and gets the initial output buffering level
	 */
	function __construct()
	{
		parent::__construct();

		if (!class_exists('Template_'))
		{
			require_once(APPPATH.'third_party/Template_/Template_.class'.EXT);
		}
		$this->template_();
	}
	/**
	 * Template_ Loader
	 *
	 * @param	string	the Template_ credentials
	 * @return	object
	 */
	public function template_()
	{
		// Grab the super object
		$CI =& get_instance();

		$CI->tpl = new Template_;
	}
}

/* End of file MY_Loader.php */
/* Location: ./app/core/MY_Loader.php */
