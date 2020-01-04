<?php if (! defined('BASEPATH')) exit('NO direct script access allowed');

class Board_model extends CI_model{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_lists()  //게시판 모든 항목 가져오기
	{
		$this->db->order_by('bd_id','DESC');
		$result = $this->db->get('board')->result_array();
		return $result;
	}

	public function get_detail($no)
	{
	$this->up_cnt($no);
	 $data = $this->db->get_where('board', array('bd_id' => $no))->row_array();
	 return $data;
	}

	public function update_con($no, $subject, $content)
	{
		$array =array(
			'bd_subject' => $subject,
			'bd_content' => $content
		);
		$this->db->where('bd_id',$no);
		return $this->db->update('board', $array);
	}

	public function insert_con($writer,$subject,$content)
	{
		$time = $this->get_date();
		$array =array(
			'bd_writer' => $writer,
			'bd_subject' => $subject,
			'bd_content' => $content,
			'bd_date' => $time
		);
	return $this->db->insert('board',$array);
	}
	public function delete_c($no)
	{
		$this->db->where('bd_id',$no);
		$this->db->delete('board');

	}

	public function get_date()
	{	
		$times = time();
		$date1 = date("Y-m-d h:i", $times);
		return $date1;
	}

	public function up_cnt($no)//조회수
	{
		$this->db->set('bd_count', 'bd_count+1',FALSE);
		$this->db->where('bd_id',$no);
		return $this->db->update('board');
	}


	
}
