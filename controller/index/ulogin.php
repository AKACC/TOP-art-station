<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Ulogin extends CI_Controller{

	/**
	* 登陆默认方法
	* @return [type] [description]
	*/
	     /**
     *默认首页显示方法
     */ 
     public function index(){
          $this->load->model('article_model','art_m');
          $data = $this->art_m->check(4);

          
          $this->load->model('category_model','cate_m');
        

          $data['category'] = $this->cate_m->limit_category(4);
       
     	$this->load->view('index/index.html',$data);
          
         
     }


	/**
	 * 验证码
	 */
	public function code(){
		$config = array(
			'width'		=> 80,
			'height'	=> 25,
			'fontSize'	=> 18,
			'codeLen'	=> 1
			);
       $this->load->library('code',$config);

       $this->code->show();

	}




	/**
	 * 用户登陆
	 */
    public function ulogin_in(){
  //   	$code = $this->input->post('captcha');
    	if(!isset($_SESSION)){
			session_start();
		}
		// if(strtoupper($code) != $_SESSION['code']) error('验证码错误');
           
           $ususername = $this->input->get('ususername');
           $this->load->model('user_model','user_m');
           $userData = $this->user_m->check($ususername);
          
           
           $password = $this->input->get('password');
           
           if(!$userData || $userData[0]['password'] != md5($password)) error('用户名或密码不正确');
           

           $sessionData = array(
           	'ususername'  => $ususername,
           	'usid'      => $userData[0]['usid'],
            'aka'       => $userData[0]['aka'],
            'upic'       => $userData[0]['upic'],
            'uthumb'    =>  $userData[0]['uthumb'],
            'udate'     => time(),

           	);
           
           $this->session->set_userData($sessionData);
           // $data = $this->session->userdata('username');

           success('index/home/index','登录成功');

    }

    /**
     * 用户退出
     */
    public function ulogin_out(){
    	$this->session->sess_destroy();
    	success('index/home/index','退出成功');
    }
}