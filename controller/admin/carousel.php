<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Carousel extends MY_Controller{
   /**
     * 发表轮播
     */
     public function add(){
      

      //文件上传－－－－－－－
      //配置
      $config['upload_path'] = './carousol/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg';
      $config['max_size'] = '20000';
      $config['file_name'] = time().mt_rand(1000,9999);
      // $config['max_width'] = '1024';
      // $config['max_height'] = '768'; 

      //载入上传类
      $this->load->library('upload', $config);

      //执行上传动作
      $status = $this->upload->do_upload('caimg');
      
      $wrong = $this->upload->display_errors();
      if($wrong){
        error($wrong);
      }
      //返回信息
      $info = $this->upload->data();
      // p($info);

 
        $this->load->model('carousel_model','carousel_m');

        $data = array(
        'caimg' => $info['file_name'],
        'cacontent' => $this->input->post('cacontent'),
        'cahref' => $this->input->post('cahref'),
        'caheight' =>$this->input->post('caheight'),
        );
  

        $this->carousel_m->add($data);

        success('admin/carousel/edit_carousel','发表成功');
     
    
     }


	/**
	* 编辑广告
	*/
	public function edit_carousel(){

		$this->load->helper('form');
		$this->load->model('carousel_model','carousel_m');
		$data['carousel'] = $this->carousel_m->check_car();

		$this->load->view('admin/edit_carousel.html',$data);
	}

	/**
	* 编辑动作
	*/
	public function edit(){
	

		//文件上传－－－－－－－
      //配置
      $config['upload_path'] = './carousol/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg';
      $config['max_size'] = '20000';
      $config['file_name'] = time().mt_rand(1000,9999);
      // $config['max_width'] = '1083';
      // $config['max_height'] = '300'; 

      //载入上传类
      $this->load->library('upload', $config);
      //执行上传动作
      $status = $this->upload->do_upload('caimg');

     if(!$status){
        $info['file_name']=$this->input->post('excaimg');
        $info['full_path'] = "";
      }else{
      //返回信息
        $info = $this->upload->data();
     
       }

      //缩略图－－－－－－－－
      //配置
      // $arr['source_image'] = $info['full_path'];
      // $arr['create_thumb'] = FALSE;
      // $arr['maintain_ratio'] = TRUE;
      // $arr['width'] = 1083;
      // $arr['height'] = 300;

      // //载入缩略图类
      // $this->load->library('image_lib',$arr);
      // //执行缩放动作
      // $status = $this->image_lib->resize();
     
      // if(!$status){
      //   error('缩略图上传失败');
      // }


		
			$this->load->model('carousel_model','carousel_m');
    
		
			$data = array(
				'caid' => $this->input->post('caid'),
				'caimg' => $info['file_name'],
				'cacontent' => $this->input->post('cacontent'),
				'cahref' => $this->input->post('cahref'),
        'caheight' =>$this->input->post('caheight'),
				);
		  
		    $caid = $this->input->post('caid');
		
		    $data['car'] = $this->carousel_m->update_car($caid,$data);
		   	success('admin/carousel/edit_carousel','修改成功');
	
		 
   	}
  

  /**
   *  删除轮播图
   */
  public function del_car(){
      $this->load->model('carousel_model','carousel_m');
      $caid = $this->input->post('caid');
     
      $this->art_m->del_car($caid);
      success('admin/carousel/edit_carousel','删除成功');
    }




}