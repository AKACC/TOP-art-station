<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 聊天室控制器
 */
class Chat extends CI_Controller{
	/**
 	* 构造函数
 	*/
public function __construct(){
		parent::__construct();
		$this->load->model('chat_model','chat_m');
		
	
	}

	/**
	 *  聊天室发言 
	 */
	public function add_chat(){
	   $data['chat'] = $this->chat_m->check();
       $data['usid'] = $this->session->userData('usid');
       $this->load->helper('form');
       //p($data);die;
   	   $this->load->view('index/chat.html',$data);
	}
	/**
	 *  发言动作
	 */
	public function add(){
	   

        $data = array(
          'chcontent' => $this->input->post('chcontent'),
          'usid'  => $this->session->userData('usid'),
          'chtime' =>time(),
          
          );
       
        $this->chat_m->add_chat($data);
	}

	





}
