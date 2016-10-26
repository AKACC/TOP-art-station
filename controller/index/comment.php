<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends CI_Controller{
	/**
	 *  查看评论
	 */
	public function index(){

         $aid = $this->input->post('aid');
	     $this->load->model('comment_model','comment_m');
         $data['comment'] = $this->comment_m->com_user($aid);
         
        $this->load->view('index/comment.html',$data);


	}




	/**
	 *  添加评论
	 */
	public function add_comment(){
		$this->load->helper('form');
		$ususername = $this->session->userData('ususername');
		
        $data['ususername'] = $this->session->userData('ususername');
        $this->load->model('comment_model','comment_m');
         // $data['art'] =  $this->comment_m->com_article();
        $data['aid'] = $this->input->get('aid');
        $aid = $this->input->get('aid');
        $data['comment'] = $this->comment_m->com_user($aid);

        $cmid = $this->input->post('cmid');
        $this->load->model('user_model','user_m');
         
        $data['count'] = $this->comment_m->count_com($aid);
    
       

         $this->load->view('index/comment.html',$data);
	}

	public function add_com(){
		
        $ususername = $this->session->userData('ususername');

		if(empty($ususername)){
           	error ('请您先登录');
           }else{
		$this->load->model('comment_model','comment_m');
         // $data['art'] =  $this->comment_m->com_article();
        $data['aid'] = $this->input->get('aid');
        $aid = $this->input->get('aid');

        $data['comment'] = $this->comment_m->com_user($aid);
        
        $this->load->library('form_validation');

		$status = $this->form_validation->run('comment'); 
        

		if($status){
		   $this->load->model('comment_model','comment_m');
	       $data = array(
                'aid'  => $this->input->post('aid'),
                'ususername' => $this->input->post('ususername'),
                'comment' => $this->input->post('comment'),
                'cmtime'  => time(),
                'usname'  => $this->input->post('usname'),
                'raka'  => $this->input->post('raka'),
                'rusid' => $this->input->post('rusid'),
                        );
       
  
           $this->load->model('user_model','user_m');
           $userData = $this->user_m->check($ususername);
            
           $this->comment_m->add($data);
           error('评论成功');
         
	
	     }else{
	      $this->load->helper('form'); 
	      $data['aid'] = $this->input->post('aid');
	      $data['ususername'] = $this->session->userData('ususername');
          $aid = $this->input->post('aid');

          $data['count'] = $this->comment_m->count_com($aid);
	      $this->load->view('index/comment.html',$data);
	
	     }
     }
     
	}

	/**
	  *  显示用户信息
	  */ 
	public function check_user($aid){
	 $aid = $this->input->post('aid');
	$this->load->model('comment_model','comment_m');
     $data['comment'] = $this->comment_m->com_user($aid);   
	}


}