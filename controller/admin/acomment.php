<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acomment extends MY_Controller{


	/**
	 *  查看评论
	 */
    public function comment(){
      //载入分页类
      $this->load->library('pagination');
      $perPage = 9;
    
      //配置项设置
      $config['base_url'] = site_url('admin/acomment/comment');
      $config['total_rows'] = $this->db->count_all_results('comment');
      $config['per_page'] = $perPage;
      $config['uri_segment'] = 4;
      $config['first_link'] = '第一页';
      $config['prev_link'] = '上一页';
      $config['next_link'] = '下一页';
      $config['last_link'] = '最后一页';

      $this->pagination->initialize($config);

      $data['links'] = $this->pagination->create_links();
      //p($data);die;
      $offset = $this->uri->segment(4);
      $this->db->limit($perPage,$offset);

    	$this->load->model('comment_model','comment_m');
    	$data['comment'] = $this->comment_m->check();
    	
    	// $cmid = $this->uri->segment(4);
    	// $data['comment'] = $this->comment_m->cmid_user($cmid);

    	$this->load->view('admin/check_comment.html',$data);
    }

    /**
	 *  删除评论
	 */
    public function del_com(){
      $this->load->model('comment_model','comment_m');
      $cmid = $this->uri->segment(4);
     
      $this->comment_m->del_com($cmid);
      success('admin/acomment/comment','删除成功');
    }

}