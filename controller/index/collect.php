<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Collect extends CI_Controller{

    public function collect(){
        
        $ususername =  $this->session->userData('ususername');
        $data = array(
                 'ususercol' => $ususername,
                 'aid' => $this->input->get('aid'),
          );
    
        $this->load->model('collect_model','collect_m');
        $this->collect_m->add($data);
        success('index/home/show','收藏成功');
    }

    public function check(){

      $this->load->model('collect_model','collect_m');
      $ususername = $this->session->userData('ususername');
      //p($ususername);die;
      $data = $this->collect_m->check_col($ususername);
      //p($data);die;
          }







  }