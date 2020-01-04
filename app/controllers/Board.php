<?php if (! defined('BASEPATH')) exit('NO direct script access allowed');

class Board extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('board_model');
	}

	public function index()
	{
		$this->tpl->define(array(
			'header' => 'header.html?head',
			'body' => 'main.html?body'
		));
		$this->tpl->print_('header');
		$this->tpl->print_('body');
	}

	public function moveBoard()//게시판 이동
	{
		$data = $this->board_model->get_lists();

		$this->tpl->assign(array(
			'topics' => $data,
		));

		$this->tpl->define(array(
			'header' => 'header.html?head',
			'body' => 'body.html?body'
		));
		$this->tpl->print_('header');
		$this->tpl->print_('body');

	}

	public function detail($no='',$type='view')//해당 게시물 열람
	{
		if($no)
		{
			$data = $this->board_model->get_detail($no);
			$this->tpl->assign(array(
				'content' => $data,
				'type' => $type
			));
		}

		$this->tpl->define(array(
			'header' => 'header.html?head',
			'body' => 'addContent.html?body'
		));
		$this->tpl->print_('header');
		$this->tpl->print_('body');
	}

	public function modify($no)// 게시물 수정
	{
		$subject = $this->input->post('subject');
		$content = $this->input->post('content');

		$this->board_model->update_con($no,$subject,$content);
		redirect('board/detail/'.$no);	
	}

	public function add()
	{
		$writer = $this->input->post('writer');
		$subject = $this->input->post('subject');
		$content = $this->input->post('content');

		$data = $this->board_model->insert_con($writer,$subject,$content);
		redirect('board/moveBoard','refresh');
	}

	public function delete_con($no)
	{
		$this->board_model->delete_c($no);	
		
		redirect('board/moveBoard','refresh');
	}


}
