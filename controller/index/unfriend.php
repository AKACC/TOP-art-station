<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unfriend extends CI_Controller{
	public function add(){
		$usid = $this->session->userData('usid');
		$ufusid = $this->input->post('ufusid');
		$data = array(
			'usid' => $usid,
			'ufusid' => $ufusid,

			);
		$this->load->model('unfriend_model','unfriend_m');
		$data['add_uf'] = $this->unfriend_m->add($data);
	}
}