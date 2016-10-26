<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller{
	public function __construet(){
        parent::__construet();
          $this->load->model('article.model','art_m');
      
            }
	public function search(){
		
        // $this->load->helper('form');
        $key = $this->input->get('key');
        
        $data['key_art'] = $this->art_m->search_title($key);
    
        
	}
}