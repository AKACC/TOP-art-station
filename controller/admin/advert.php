<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Advert extends MY_Controller{

	/**
	* 编辑广告
	*/
	public function edit_advert(){

		$this->load->helper('form');
		$this->load->model('advert_model','advert_m');
		$data['advert'] = $this->advert_m->check_adv();

		$this->load->view('admin/edit_advert.html',$data);
	}

	/**
	* 编辑动作
	*/
	public function edit(){

		//文件上传－－－－－－－
      //配置
      $config['upload_path'] = './advert/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg';
      $config['max_size'] = '20000';
      // $config['file_name'] = time().mt_rand(1000,9999);
      // $config['max_width'] = '1024';
      // $config['max_height'] = '768'; 

      //载入上传类
      $this->load->library('upload', $config);
      //执行上传动作
      $status = $this->upload->do_upload('adimg');
      $wrong = $this->upload->display_errors();
      if($wrong){
        error($wrong);
      }
      //返回信息
      $info = $this->upload->data();


      //缩略图－－－－－－－－
      //配置
      $arr['source_image'] = $info['full_path'];
      $arr['create_thumb'] = FALSE;
      $arr['maintain_ratio'] = TRUE;
      $arr['width'] = 1083;
      $arr['height'] = 90;

      //载入缩略图类
      $this->load->library('image_lib',$arr);
      //执行缩放动作
      $status = $this->image_lib->resize();
     
      if(!$status){
        error('缩略图上传失败');
      }
		
			$this->load->model('advert_model','advert_m');
    
		
			$data = array(
				'adid' => $this->input->post('adid'),
				'adimg' => $info['file_name'],
				'adtime' =>time(),
				);
		
		    $adid = $this->input->post('adid');
		
		    $data = $this->advert_m->update_adv($adid,$data);
		   	success('admin/advert/edit_advert','修改成功');
		
	
   	}
}