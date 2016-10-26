<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice extends CI_Controller{
	/**
	 *  发布公告
	 */
	public function send_notice(){
    $this->load->helper('form');
		$this->load->view('admin/add_notice.html');
	}
	/**
	 *  发布动作
	 */
	public function send_no(){
		$this->load->library('form_validation');
     
     	$status = $this->form_validation->run('notice');

     	if($status){
     		$this->load->model('notice_model','notice_m');

        $data = array(
          'ntitle' => $this->input->post('ntitle'),
          'ncontent'=> $this->input->post('ncontent'),
          'ntime'  => time(),
          'username' =>$this->session->userData('username'),
          );
       

        $this->notice_m->add($data);

        success('admin/notice/send_notice','发表成功');
     		
        }else{
     		$this->load->helper('form');
     		$this->load->view('admin/add_notice.html');
     	}
     }

     /**
      *  查看公告
      */
      public function check(){
       //载入分页类
       $this->load->library('pagination');
       $perPage = 9;
    
       //配置项设置
       $config['base_url'] = site_url('admin/notice/check');
       $config['total_rows'] = $this->db->count_all_results('notice');
       $config['per_page'] = $perPage;
       $config['uri_segment'] = 4;
       $config['first_link'] = '第一页';
       $config['prev_link'] = '上一页';
       $config['next_link'] = '下一页';
       $config['last_link'] = '最后一页';

       $this->pagination->initialize($config);

       $data['links'] = $this->pagination->create_links();
       $offset = $this->uri->segment(4);
       $this->db->limit($perPage,$offset);
     
       $this->load->model('notice_model','notice_m');
       
       $data['notice'] = $this->notice_m->check();

       $this->load->view('admin/check_notice.html',$data);
     }

      /**
      *  删除公告
      */
    public function del_notice(){
      $this->load->model('notice_model','notice_m');
      $nid = $this->uri->segment(4);
     
      $this->notice_m->del_notice($nid);
      success('admin/notice/check','删除成功');
    }

    /**
     *  编辑公告
     */
    public function edit_notice(){
      $nid = $this->uri->segment(4);
      $this->load->model('notice_model','notice_m');
          
      $data['notice'] = $this->notice_m->check_nid($nid);

      $this->load->helper('form');
      $this->load->view('admin/edit_notice.html',$data);

      }

      /**
       *  编辑动作
       */
      public function edit_no(){
          $this->load->model('notice_model','notice_m');
          $this->load->library('form_validation');
          $status = $this->form_validation->run('notice');


      if($status){
          $nid = $this->input->post('nid');
        
          $data = array(
            'nid'  => $this->input->post('nid'),
            'ntitle'  => $this->input->post('ntitle'),
            'ncontent' => $this->input->post('ncontent'),
            'ntime'   =>time(),
            );
          $data['notice'] = $this->notice_m->update_no($nid,$data);
        
      }else{
          $this->load->helper('form');
          $this->load->view('admin/edit_notice.html');
      }
         success('admin/notice/check','修改成功');
       
      }





}
