<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 密友控制器
 */
class Friend extends CI_Controller{
/**
 * 构造函数
 */
public function __construct(){
		parent::__construct();
		$this->load->model('friend_model','friend_m');
	}
/**
 * 添加好友
 */
public function add(){
	$usid = $this->session->userData('usid');
	$fusid = $this->uri->segment(4);
	$friend = $this->friend_m->user_friend($usid,$fusid);

	if(empty($usid)){
		error('请您先登录');
	}else{
		if(!empty($friend)){
			error('您已经加过他为密友了');
		}
       $data = array(
       	'usid' => $usid,
       	'fusid' => $fusid,
    
       	);
    $this->load->model('friend_model','friend_m');
    $data['add'] = $this->friend_m->add($data);
    error('添加成功');
	}

  }


/**
 * 查看好友
 */
public function check(){
	$usid = $this->session->userData('usid');
	$this->load->model('friend_model','friend_m');
	$data['friend'] = $this->friend_m->check($usid);

    $this->load->view('index/usermail.html',$data);

}

public function user_friend(){
		$this->load->model('mail_model','mail_m');

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

        $data['unfriend'] = $this->mail_m->check_user($usid);
        $data['unfriend_u'] = $this->mail_m->check_user_f($usid);

        //p($data);die;
		$this->load->view('index/usermail.html',$data);
	}


      /**
      * 删除好友
      */
     public function del_friend(){
      $fusid = $this->uri->segment(4);
      $usid = $this->session->userData('usid');
      $this->friend_m->del_friend($usid,$fusid);
      success('index/friend/check','删除成功');
    }





}