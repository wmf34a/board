<?php if (! defined('BASEPATH')) exit('NO direct script access allowed');

class Login_model extends CI_model{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function chk_login($id,$pwd)
	{
#		$sql = "SELECT * FROM member WHERE mb_id = '".$id."' AND mb_pwd = '".$pwd."'";
#		$query = $this->db->query($sql)->result();
#		$where=$this->db->where('mb_id',$id);
#		$result=$this->db->get_where('member', array('mb_id' => $id))->result_array();		
		$query = $this->db->query("SELECT mb_pwd FROM member WHERE mb_id="."'$id'");
		$row = $query->row_array();
		return $row['mb_pwd'];	
	}

}
