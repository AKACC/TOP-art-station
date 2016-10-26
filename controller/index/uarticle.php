<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Uarticle extends CI_Controller{
		/**
	* 发表作品模版显示
	*/
	public function send_article(){

    $this->load->model('category_model','cate_m');
    $data['category'] = $this->cate_m->check();
    $data['ususername'] = $this->session->userData('ususername');
  
		$this->load->helper('form');
		$this->load->view('index/addwork.html',$data);
	}

     /**
     * 发表作品动作
     */
     public function send(){
      

      //文件上传－－－－－－－
      //配置
      $config['upload_path'] = './uploads/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg';
      $config['max_size'] = '10000';
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
     $ususername = $this->session->userData('ususername');
     /**
      *  图像水印处理
      */
    
    
     $con['image_library'] = 'gd2';
     $con['source_image'] = $info['full_path'];
     $con['wm_text'] = 'Designed by '.$ususername;
     $con['wm_type'] = 'text';
     $con['new_image']='./uploads/'; 
 
     // $con['wm_font_path'] = './system/fonts/simhei.ttf';
     $con['wm_font_size'] = '5';
     $con['wm_font_color'] = 'ffffff';
     $con['wm_vrt_alignment'] = 'bottom';
     $con['wm_hor_alignment'] = 'center';
     $con['wm_padding'] = '0'; 


       //水印
     $this->load->library('image_lib');
     $this->image_lib->initialize($con); 
     //执行水印
     $status1 = $this->image_lib->watermark();
     if(!$status1){
       echo $this->image_lib->display_errors();die;
     }



      //缩略图－－－－－－－－
      //配置
      $arr['source_image'] = $info['full_path'];
      $arr['create_thumb'] = TRUE;
      $arr['maintain_ratio'] = FALSE;
      $arr['new_image']='./uploads/'; 
      $arr['width'] = 250;
      $arr['height'] = 200;

      //载入缩略图类
      $this->load->library('image_lib',$arr);
      //执行缩放动作
      $status2 = $this->image_lib->resize();

      if(!$status2){
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
        $content =  urlencode($this->input->post('content'));
        $data = array(
          'title' => $this->input->post('title'),
          'type'  => $this->input->post('type'),
          'cid'   => $this->input->post('cid'),
          'thumb' => $info['raw_name'] . '_thumb' . $info['file_ext'],
          'img' => $info['file_name'],
          'info'  => $this->input->post('info'),
          'content'=> urldecode($content),
          'time'  => time(),
          'ususername' => $this->input->post('ususername'),
          );

        $this->art_m->add($data);

        success('index/uarticle/send_article','发表成功');
     		
        }else{
        	$this->load->model('category_model','cate_m');
          $data['category'] = $this->cate_m->check();
          $data['ususername'] = $this->session->userData('ususername');
  
          $this->load->helper('form');
          $this->load->view('index/addwork.html',$data);
     	}
     }

     


     /**
      *  查看文章
      */
     public function user_art(){
    //载入分页类
    $this->load->library('pagination');
    $perPage = 9;
    
    //配置项设置
    $config['base_url'] = site_url('index/uarticle/user_art');
    $config['total_rows'] = $this->db->count_all_results('article');
    $config['per_page'] = $perPage;
    $config['uri_segment'] = 10;
    $config['first_link'] = '第一页';
    $config['prev_link'] = '上一页';
    $config['next_link'] = '下一页';
    $config['last_link'] = '最后一页';

    $this->pagination->initialize($config);

    $data['links'] = $this->pagination->create_links();
    //p($data);die;
    $offset = $this->uri->segment(10);
    $this->db->limit($perPage,$offset);
    
      $this->load->model('article_model','art_m');
      $this->load->model('category_model','cate_m');

      $ususername = $this->session->userData('ususername');
      $data['art'] = $this->art_m->user_article($ususername);

      $art = $this->art_m->user_article($ususername);

      $this->load->view('index/user_article.html',$data);
     }

}