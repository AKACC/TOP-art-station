<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*默认前台控制器
*/ 
class User extends CI_Controller{
     public $category;
     public $title;
     


     /**
      * 自动载入模型构造函数
      */
     public function __construct(){
           parent::__construct();
          $this->load->model('article_model','art_m');
          $this->load->model('category_model','cate_m');
          $this->category = $this->cate_m->limit_category(4);
     }

     /**
     *默认首页显示方法
     */ 
     public function index(){
          // $this->output->enable_profiler(TRUE);
     	// echo base_url() . 'style/index/';
     	// echo site_url().'/index/home/category/';
          $this->load->model('article_model','art_m');
          $data = $this->art_m->check(4);

          
          $this->load->model('category_model','cate_m');
        

          $data['category'] = $this->cate_m->limit_category(4);

       
     	$this->load->view('index/content.html',$data);
          
          //$this->load->view('index/内容.html',$data);
     }


     /**
       * 用户登录状态显示
       */
      public function uindex(){
           $this->load->model('article_model','art_m');
          $data = $this->art_m->check(4);

          $this->load->model('category_model','cate_m');

          $data['category'] = $this->cate_m->limit_category(4);

           $ususername = $this->input->get('ususername');
           $this->load->model('user_model','user_m');
           $data['aka'] = $this->session->userData('aka');
           $data['uthumb'] = $this->session->userData('uthumb');

       
           // $data['aka'] = $this->user_m->check_aka($ususername);
           // p($data);die;
          
            $this->load->view('index/navbar.html',$data);
            

            
      }

        

      /**
       * 用户注册
       */
      public function enroll(){
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
      
      $wrong = $this->upload->display_errors();
      if($wrong){
        error($wrong);
      }
      //返回信息
      $info = $this->upload->data();
      // p($info);



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
        error('图片上传失败');
      }
      
      
      $password = $this->input->post('password');
      $this->load->model('user_model','user_m');

        $data = array(
          'ususername' => $this->input->post('ususername'),
          'password'  => md5($password),
          'usid'   => $this->input->post('usid'),
          'aka'    =>$this->input->post('aka'),
          'uthumb' => $info['raw_name'] . '_thumb' . $info['file_ext'],
          'upic' => $info['file_name'],
          'udate'  => time()
          );
       

        $this->user_m->add($data);

        success('index/home/index','注册成功');


      }

      /**
       * 用户修改
       */
      public function edit_user(){
      $this->load->helper('form');
     $this->load->view('index/edit_user.html');

      }

 

    /**
     * 用户修改动作
     */
    public function edit_u(){
      $this->load->helper('form');
      $this->load->library('form_validation');
      // $this->form_validation->set_rules('ususername','用户名','required|min_length[5]');
      $status = $this->form_validation->run('edit_user');
      
      if($status){
        echo '数据库';

      }else{
      $this->load->helper('form');
      $this->load->view('index/edit_user.html');
      }
    }

  }
