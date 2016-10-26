<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 私信控制器
 */
class Mail extends CI_Controller{
    /**
 * 构造函数
 */
public function __construct(){
		parent::__construct();
		$this->load->model('friend_model','friend_m');
		$this->load->model('mail_model','mail_m');
	
	}
    /**
     * 发送信息
     */
	public function add_mail(){
       $this->load->helper('form');
	   $this->load->view('index/mail.html',$data);
	}
	/**
	 * 发送动作
	 */
	public function add_m(){
	

        $data = array(
          'mcontent' => $this->input->post('mcontent'),
          'usid'  => $this->session->userData('usid'),
          'musid'   => $this->input->post('musid'),
          'mtime' =>time(),
          'ususername' => $this->session->userData('ususername'),
          
          );
       
        $this->mail_m->add($data);
	}
	
	public function check_mail(){
		$usid = $this->session->userData('usid');
		if(!$usid){
			error('要登录了才能聊天哦');
		}else{
		$ususername = $this->session->userData('ususername');
		$data['user'] = array(
			'usid' => $usid,
			'ususername' => $ususername,
			'upic' => $this->session->userData('upic'),
			'aka' => $this->session->userData('aka'),
			);
        
		$musid = $this->uri->segment(4);


		$data['muser'] = $this->mail_m->check_usid($musid);
  
		$data['mail'] = $this->mail_m->check($usid,$musid);
		$data['mail_all'] = $this->mail_m->check_all($usid);

        //p($data);die;
		$this->load->view('index/mail.html',$data);
	    }
	}
	
	public function user_mail(){

		$usid = $this->session->userData('usid');
		$ususername = $this->session->userData('ususername');
		$data['user'] = array(
			'usid' => $usid,
			'ususername' => $ususername,
			'upic' => $this->session->userData('upic'),
			'aka' => $this->session->userData('aka'),
			);

		$musid = $this->uri->segment(4);

		$data['muser'] = $this->mail_m->check_usid($musid);
  
		$data['mail'] = $this->mail_m->check($usid,$musid);

		/**
        * 查看好友
        */
    	$data['friend'] = $this->friend_m->check($usid);
        
        
		$this->load->view('index/usermail.html',$data);
	}
}