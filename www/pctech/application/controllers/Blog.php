<?php
class Blog extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('M_Db');
    }
	
    function index($start = 0){ // index page
        $data['posts'] = $this->M_Db->getPosts(5, $start);
        // pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'Blog/index/'; // url to set pagination
        $config['total_rows'] = $this->M_Db->getPostCount();
        $config['per_page'] = 5;
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links(); // links of pages
		
        $class_name = array('home_class' => 'current', 'login_class' => '', 'register_class' => '', 'profile_class' => '');
        $this->load->view('Header', $class_name);
        $this->load->view('V_Home', $data);
        $this->load->view('Footer');
    }
	
    function post($post_id){ //single post page
        $this->load->model('M_Comment');
        $data['comments'] = $this->M_Comment->getComment($post_id);
        $data['post'] = $this->M_Db->getPost($post_id);
		
        $class_name = ['home_class' => 'current', 'login_class' => '', 'register_class' => '', 'profile_class' => ''];
        $this->load->view('Header', $class_name);
        $this->load->view('V_Single_Post', $data);
        $this->load->view('Footer');
    }
	
    function newPost(){ // creating new post page
        if (!$this->validateLogin()){
            redirect(base_url() . 'Users/Login');
        }
		
        $class_name = ['home_class' => 'current', 'login_class' => '', 'register_class' => '', 'profile_class' => ''];
        $this->load->view('Header', $class_name);
        $this->load->view('V_New_Post');
        $this->load->view('Footer');
    }
	
    function editpost($post_id){ // edit post page
		if (!$this->validateLogin()){
            redirect(base_url() . 'Users/login');
        }
		
        $data['success'] = 0;
		$data['post'] = $this->M_Db->getPost($post_id);

        $class_name = ['home_class' => 'current', 'login_class' => '', 'register_class' => '', 'profile_class' => ''];
        $this->load->view('Header', $class_name);
        $this->load->view('V_Edit_Post', $data);
        $this->load->view('Footer');
    }
	
    function deletepost($post_id){ // delete post page
        if (!$this->validateLogin()){
            redirect(base_url() . 'Users/login');
        }
		
        $this->M_Db->deletePost($post_id);
        redirect(base_url() . 'Blog/');
    }
	
	function validateLogin(){ // check if user token is valid
		$validate_token = $this->session->userdata('user_token');
		$is_valid_token = $this->authorization_token->validateTokenPost($validate_token);
        if($is_valid_token['status'] === TRUE){
            return TRUE;
        } 
		else{
			return FALSE;
		}
    }
}
?>