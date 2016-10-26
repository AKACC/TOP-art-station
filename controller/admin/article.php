<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends MY_Controller{
  /**
   * 查看文章
   */
  public function index(){
    //载入分页类
    $this->load->library('pagination');
    $perPage = 9;
    
    //配置项设置
    $config['base_url'] = site_url('admin/article/index');
    $config['total_rows'] = $this->db->count_all_results('article');
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
    
    $this->load->model('article_model','art_m');
    $data['article'] = $this->art_m->article_category();

    $this->load->view('admin/check_article.html',$data);
  }
	/**
	* 发表作品模版显示
	*/
	public function send_article(){
    $this->load->model('category_model','cate_m');
    $data['category'] = $this->cate_m->check();
		$this->load->helper('form');
		$this->load->view('admin/article.html',$data);
	}

     /**
     * 发表作品动作
     */
     public function send(){
      

      //文件上传－－－－－－－
      //配置
      $config['upload_path'] = './uploads/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg';
      $config['max_size'] = '20000';
      $config['file_name'] = time().mt_rand(1000,9999);
      // $config['max_width'] = '1024';
      // $config['max_height'] = '768'; 

      //载入上传类
      $this->load->library('upload', $config);

      //执行上传动作
      $status = $this->upload->do_upload('img');
      
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
      $arr['width'] = 250;
      $arr['height'] = 200;

      //载入缩略图类
      $this->load->library('image_lib',$arr);
      //执行缩放动作
      $status = $this->image_lib->resize();

      if(!$status){
        error('缩略图上传失败');
      }




     	//载入表单验证类
     	$this->load->library('form_validation');
     	//设置规则
     	// $this->form_validation->set_rules('title','文章标题','required|min_length[5]');
     	// $this->form_validation->set_rules('type','类型','required|integer');
      //     $this->form_validation->set_rules('cid','栏目','integer');
      //     $this->form_validation->set_rules('info','摘要','required|max_length[155]');
      //     $this->form_validation->set_rules('content','内容','required|max_length[10]');
          //执行验证
     	$status = $this->form_validation->run('article');

     	if($status){
     		$this->load->model('article_model','art_m');

        $data = array(
          'title' => $this->input->post('title'),
          'type'  => $this->input->post('type'),
          'cid'   => $this->input->post('cid'),
          'thumb' => $info['raw_name'] . '_thumb' . $info['file_ext'],
          'img' => $info['file_name'],
          'info'  => $this->input->post('info'),
          'content'=> $this->input->post('content'),
          'time'  => time(),
          'ususername' =>$this->input->post('admin')
          );
  

        $this->art_m->add($data);

        success('admin/article/index','发表成功');
     		
        }else{
     		$this->load->helper('form');
     		$this->load->view('admin/article.html');
     	}
     }


     /**
     * 编辑文章
     */
     public function edit_article(){
          $aid = $this->uri->segment(4);
          $this->load->model('article_model','art_m');
           $this->load->model('category_model','cate_m');
          $data['article'] = $this->art_m->check_art($aid);
          $data['cate'] = $this->art_m->aid_cate($aid);
          $data['category'] = $this->cate_m->check();
       

          $this->load->helper('form');
          $this->load->view('admin/edit_article.html',$data);
      }


     /**
     * 编辑动作
     */
     public function edit(){
       //文件上传－－－－－－－
      //配置
      $config['upload_path'] = './uploads/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg';
      $config['max_size'] = '20000';
      $config['file_name'] = time().mt_rand(1000,9999);
      // $config['max_width'] = '1024';
      // $config['max_height'] = '768'; 

      //载入上传类
      $this->load->library('upload', $config);

      //执行上传动作
      $status = $this->upload->do_upload('img');

    
      
      
      if(!$status){
        $info['file_name']=$this->input->post('imagefile');
        $info['full_path'] = "";
      }else{
         $wrong1 = $this->upload->display_errors();
           if($wrong1){
             error($wrong1);
           }
      //返回信息
      $info = $this->upload->data();
     
       }
       

      //缩略图－－－－－－－－
      //配置
      $arr['source_image'] = $info['full_path'];
      $arr['create_thumb'] = TRUE;
      $arr['maintain_ratio'] = FALSE;
      $arr['width'] = 200;
      $arr['height'] = 200;

      //载入缩略图类
      $this->load->library('image_lib',$arr);
      //执行缩放动作
      $status = $this->image_lib->resize();

      if(!$status){
        $raw_name = explode(".", $info['file_name']);

        $info['raw_name'] = $raw_name[0];
        $info['file_ext'] = '.' . $raw_name[1];
     
      }

      $this->load->library('form_validation');
      $status = $this->form_validation->run('article');


      if($status){
          $this->load->model('article_model','art_m');
          $this->load->model('category_model','cate_m');
          $aid = $this->input->post('aid');
          $title = $this->input->post('title');
          $type = $this->input->post('type');  

          // $thumb = $this->input->post('thumb');
          
          $content = $this->input->post('content');
          $data = array(
            'title' => $title,
            'type'  => $this->input->post('type'),
            'cid'  => $this->input->post('cid'),


            
            'thumb' => $info['raw_name'] . '_thumb' . $info['file_ext'],
            'img' => $info['file_name'],
            'info'  => $this->input->post('info'),
            'content' =>$content,




            );
          
          $data['article'] = $this->art_m->update_art($aid,$data);
          success('admin/article/index','修改成功');
      }else{
          $aid = $this->input->post('aid');
          $this->load->model('article_model','art_m');
          $this->load->model('category_model','cate_m');
          $data['article'] = $this->art_m->check_art($aid);
          $data['cate'] = $this->art_m->aid_cate($aid);
          $data['category'] = $this->cate_m->check();
       

          $this->load->helper('form');
          $this->load->view('admin/edit_article.html',$data);
      }

       

     }



     /**
      * 加载栏目
      */
     public function aid_cate_type(){
         $this->load->model('article_model','art_m');
          $this->load->model('category_model','cate_m');
          
          $data['cate'] = $this->art_m->aid_cate();
          $data['art'] = $this->art_m->check_art($aid);

         


           $this->load->view('admin/edit_article.html',$data);
    
          
     }  

     /**
      *  加载类型
      */
    // public function check_type($aid){
    //      $this->load->model('article_model','art_m');
    //       $this->load->model('category_model','cate_m');
          
    //       $data['type'] = $this->art_m->check_type($aid);
         

    //        $this->load->view('admin/edit_article.html',$data);
    
          
    //  }  




    //   /**
    //   * 删除文章
    //   */
     public function del_art(){
      $this->load->model('article_model','art_m');
      $aid = $this->uri->segment(4);
     
      $this->art_m->del_art($aid);
      success('admin/article/index','删除成功');
    }



}



     



