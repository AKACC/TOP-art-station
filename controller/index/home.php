<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*默认前台控制器
*/ 
class Home extends CI_Controller{
     public $category;
     public $title;
     


     /**
      * 自动载入模型构造函数
      */
     public function __construct(){
           parent::__construct();
          $this->load->model('article_model','art_m');
          $this->load->model('category_model','cate_m');
          $this->load->model('user_model','user_m');
          $this->load->model('advert_model','advert_m');

     }

     /**
     *默认首页显示方法
     */ 
     public function index(){
        
          $data = $this->art_m->check(4);
          $data['category'] = $this->cate_m->limit_category(4);
          $data['ususername'] = $this->session->userData('ususername');

          $data['aka'] = $this->session->userData('aka');
          $data['uthumb'] = $this->session->userData('uthumb');

          $this->load->model('carousel_model','carousel_m');
          $data['carousel'] = $this->carousel_m->check_car();

        
       
     	$this->load->view('index/content.html',$data);

     }

/**
* 分类页显示
*/
    
     public function category(){
         

                  //载入分页类
          $this->load->library('pagination');
          $perPage = 9;
          
          
          $cid = $this->uri->segment(4);
          //配置项设置
          $config['base_url'] = site_url('index/home/category/'.$cid);
          $config['total_rows'] = $this->db->count_all_results('article');
          $config['per_page'] = $perPage;
          $config['uri_segment'] = 5;
          //数字页号
          $config['num_links'] = 9;
          //第一页链接定义
          $config['first_link'] = '第一页';
          $config['first_tag_open'] = '<li>';
          $config['first_tag_close'] = '</li>';
          //上一页链接定义
          $config['prev_link'] = '&laquo;';
          $config['prev_tag_open'] = '<li class="previous">';
          $config['prev_tag_close'] = '</li>';
          //下一页链接定义
          $config['next_link'] = '&raquo;';
          $config['next_tag_open'] = '<li class="next">';
          $config['next_tag_close'] = '</li>';
          // //尾页链接定义
          $config['last_link'] = '最后一页';
          $config['last_tag_open'] = '<li>';
          $config['last_tag_close'] = '</li>';
          //当前页链接定义 
          $config['cur_tag_open'] = ' <li class="active">';
          $config['cur_tag_close'] = '</li>';
          //每一页标签定义
          $config['num_tag_open'] = '<li>';
          $config['num_tag_close'] = '</li>';
    

          $this->pagination->initialize($config);

          $data['links'] = $this->pagination->create_links();
          $offset = $this->uri->segment(5);
          $this->db->limit($perPage,$offset);
        
          
          $data['article'] = $this->art_m->category_article($cid);          
          $data['ususername'] = $this->session->userData('ususername');
          $data['aka'] = $this->session->userData('aka');
          $data['uthumb'] = $this->session->userData('uthumb');
          $data['advert'] = $this->advert_m->check_adv();
          // $this->load->model('comment_model','comment_m');
          // $aid = $this->input->get('aid');
          // $data['count'] = $this->comment_m->count_com($aid);
          $data['category'] = $this->cate_m->limit_category(4);
          $this->load->view('index/work.html',$data);
     }





     


     /**
     * 文章阅读页显示 
     */
      public function show(){
          $this->load->helper('form');
          $aid = $this->uri->segment(4);
          $data = $this->art_m->check(4);
          $data['category'] = $this->cate_m->limit_category(4);
          //文章显示
          $data['article'] = $this->art_m->aid_article($aid);
          //评论数据显示
          $this->load->model('comment_model','comment_m');
          $data['count_com'] = $this->comment_m->count_com($aid);
          //作者信息显示
          $data['ususername'] = $this->session->userData('ususername');
          $data['aka'] = $this->session->userData('aka');
          $data['uthumb'] = $this->session->userData('uthumb');
          //广告信息
          $data['advert'] = $this->advert_m->check_adv();
          //点赞信息
          $this->load->model('zan_model','zan_m');
          $data['count_zan'] = $this->zan_m->count_zan($aid);

        $this->load->view('index/showwork.html',$data);
      }

      /**
       *  添加赞动作
       */
      public function add_zan(){
          $this->load->model('zan_model','zan_m');
          $aid = $this->input->post('aid');
          $usid = $this->session->userData('usid');


          if(empty($usid)){
            error('请您先登录！');
          }else{
            $zan = $this->zan_m->user_zan($usid,$aid);
            if(empty($zan)){
            
            $data = array(
              'usid' => $usid,
              'aid' => $aid,
              'ztime' =>time(),
              );
         
          $data['add_zan'] = $this->zan_m->add_zan($data);
          success('index/home/show'.'/'.$aid,'点赞成功');
              }else{
                error('您已经赞过这篇文章了');
              }

          }


      }

      
    /**
     *  收藏夹
     */
       public function collect(){
        $this->load->model('collect_model','collect_m');

        $ususername =  $this->session->userData('ususername');
         if(empty($ususername)){
          error('请您先登录');
         }else{
        $aid = $this->uri->segment(4);
        $status = $this->collect_m->collected($ususername,$aid);
        //p($status);die;
        if($status){
         error('您已添加此收藏');
      
            }
        $data = array(
                 'ususercol' => $ususername,
                 'aid' => $aid,
          );
         $this->collect_m->add($data);
         error('收藏成功');
        
        }
      }

  /**
   *  查看收藏夹
   */

    public function check_col(){
      //载入分页类
      $this->load->library('pagination');
      $perPage = 9;
    
      //配置项设置
      $config['base_url'] = site_url('index/home/check_col');
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
    

      $this->load->model('collect_model','collect_m');
      $ususercol = $this->session->userData('ususername');
      $data['ususercol'] = $this->collect_m->check($ususercol);
     
      $this->load->view('index/collect.html',$data);
          }

    public function search(){
      //导航栏信息
      $cid = $this->uri->segment(4);         
      $data['ususername'] = $this->session->userData('ususername');
      $data['aka'] = $this->session->userData('aka');
      $data['uthumb'] = $this->session->userData('uthumb');
      $data['category'] = $this->cate_m->limit_category(4);

      //广告
      $data['advert'] = $this->advert_m->check_adv();

      //搜索
      $this->load->helper('form');
      $key = $this->input->get('key');
      $data['key'] = $this->input->get('key');
      $data['key_art'] = $this->art_m->search_title($key);
      $this->load->view('index/searchwork.html',$data);
        
  }









}
