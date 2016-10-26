<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zan extends CI_Controller{
	/**
	 *  点赞信息展示
	 */
	public function check_zan(){
		$this->load->model('zan_model','zan_m');
		$aid = $this->uri->segment(4);
		$data['zan'] = $this->zan_m->check_zan($aid);
        $this->load->view('index/zan.html',$data);
	}
}