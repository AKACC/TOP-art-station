<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  前台用户中心
 */
class Userweb extends CI_Controller{

	/**
	 * 用户中心首页
	 */
	public function index(){

           $ususername = $this->session->userData('ususername');
           $this->load->model('user_model','user_m');
           $data['user'] = $this->user_m->check($ususername);

		$this->load->view('index/userindex.html',$data);
	}


	public function userstatus(){
          $ususername = $this->session->userData('ususername');
          $this->load->model('user_model','user_m');
          $data['user'] = $this->user_m->check($ususername);
          // p($data);die;

          $this->load->model('article_model','art_m');
         
          $data['artname'] = $this->art_m->user_artname($ususername);

          $this->load->model('notice_model','notice_m');
          $data['notice'] = $this->notice_m->check();

		$this->load->view('index/userstatus.html',$data);
	}


  /**
   * 修改密码
   */
  public function change(){
        $this->load->view('index/changepassword.html');
  }

  /**
   * 修改动作
   */
  public function change_password(){
    $this->load->model('user_model','user_m');
    $ususername = $this->session->userdata('ususername');
    $userData=$this->user_m->check($ususername);
    
    $password = $this->input->post('password');

    if(md5($password) != $userData[0]['password']) error('原始密码错误');
        
    $passwordF = $this->input->post('passwordF');
    $passwordS = $this->input->post('passwordS');
    if($passwordF  != $passwordS) error('两次密码输入不相同');

        
    $usid = $this->session->userData('usid');
    $data = array(
      'password'     => md5($passwordF)
                 );
    $this->user_m->change($usid,$data);

    success('index/userweb/change','修改成功');
  }






  /**
   * 修改信息
   */
  public function change_user(){
        $this->load->model('user_model','user_m');
        $ususername = $this->session->userdata('ususername');
        $data['user']=$this->user_m->check($ususername);
        $this->load->view('index/changeuser.html',$data);
  }

  /**
   * 修改动作
   */
  public function change_us(){
    $this->load->model('user_model','user_m');
    $ususername = $this->session->userdata('ususername');
    $userData=$this->user_m->check($ususername);
    $usid=$this->session->userdata('usid');
    

      //文件上传－－－－－－－
      //配置
      $config['upload_path'] = './user/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg';
      $config['max_size'] = '10000';
      $config['file_name'] = time().mt_rand(1000,9999);
      // $config['max_width'] = '1024';
      // $config['max_height'] = '768'; 

      //载入上传类
      $this->load->library('upload', $config);

      //执行上传动作
      $status = $this->upload->do_upload('upic');
      
      if(!$status){
       $info['file_name'] = $this->input->post('upicimg');
       $info['full_path']="";
      }else{
      //返回信息
      $info = $this->upload->data();
      // p($info);
        }

      //缩略图－－－－－－－－
      //配置
      $arr['source_image'] = $info['full_path'];
      $arr['create_thumb'] = TRUE;
      $arr['maintain_ratio'] = FALSE;
      $arr['width'] = 40;
      $arr['height'] = 40;

      //载入缩略图类
      $this->load->library('image_lib',$arr);
      //执行缩放动作
      $status = $this->image_lib->resize();

      if(!$status){
          $raw_name = explode(".", $info['file_name']);

        $info['raw_name'] = $raw_name[0];
        $info['file_ext'] = '.' . $raw_name[1];
      }
      
      
      $this->load->model('user_model','user_m');

        $data = array(
        
          'aka'    =>$this->input->post('aka'),
          'uthumb' => $info['raw_name'] . '_thumb' . $info['file_ext'],
          'upic' => $info['file_name'],
          );
        $sessionData = array(
            'ususername'  => $ususername,
            'usid'      => $userData[0]['usid'],
            'aka'       => $this->input->post('aka'),
            'upic'       => $info['file_name'],
            'uthumb'    =>  $info['raw_name'] . '_thumb' . $info['file_ext'],
            'udate'     => $userData[0]['udate'],
            );
        $this->session->set_userData($sessionData);
       

        // $this->user_m->add($data);

    
    $this->user_m->change($usid,$data);

    success('index/userweb/change_user','修改成功');
  }

}