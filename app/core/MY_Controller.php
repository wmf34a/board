<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->tpl->compile_dir = _CPL.'guide';
		$this->tpl->template_dir = FCPATH.'views';
		$this->tpl->cache_dir = _CACHE;
		if(!is_dir(_CPL.'guide')){
			mkdir(_CPL.'guide', 0777, true);
		}

		// template_dir을 기준으로 한 상대경로
		define('POPUP_DIR', 'popup');
		define('JSTREE_DIR', 'jstree');
		if(!$this->uri->segment(1)){
			redirect('/guide/main');
		}

		// define layout
		$contents = "";
		if (trim($this->uri->segment(1)))
		{
			$contents = trim($this->uri->segment(1));
		}
		if (trim($this->uri->segment(2)))
		{
			$contents .= "/".trim($this->uri->segment(2));
		}
		else{
			$contents .= "/index";
		}


		$this->tpl->define(array(
			'index'    => '_layout.default.html',
			'top'      => '_wrap_top.html',
			'header'   => '_header.html',
			'gnb'      => '_gnb.html',
			'left'     => 'eas.left.html',
			'footer'   => '_footer.html',
			'bottom'   => '_wrap_bottom.html',
			'contents' => "{$contents}.html",
		));
	}
}

/* End of file MY_Controller.php */
/* Location: ./app/core/MY_Controller.php */
