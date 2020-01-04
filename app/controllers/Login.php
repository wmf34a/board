<?php if (! defined('BASEPATH')) exit('NO direct script access allowed');

class Login extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
	}

	public function index()
	{
		$this->tpl->define(array(
			'header' => 'header.html?head',
			'body' => 'login.html?body'
		));
		$this->tpl->print_('header');
		$this->tpl->print_('body');
	}

	public function loginchk()
	{
		$id = $this->input->post('id');
		$pwd = $this->input->post('pwd');
		$data = $this->login_model->chk_login($id,$pwd);
		echo $data;
#		$this->tpl->assign(array(
#
#		echo $data;
#		if($data == 1){
#		redirect('/board','refresh');
#		}


	}

}
