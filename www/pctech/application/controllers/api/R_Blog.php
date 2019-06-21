<?php
use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';
 
class R_Blog extends REST_Controller{
	public function __construct() {
        parent::__construct();
        // Load User Model
        $this->load->model('M_Db');
    }
	
	public function newPost_post(){
		header("Access-Control-Allow-Origin: *");
		
        $data = array(
			'post_title' => $this->input->post('post_title'), 
			'post' => $this->input->post('post'), 
			'active' => 1, 
			'user_id' => $this->session->userdata('user_id')
		);
        $this->M_Db->insertPost($data);
        redirect(base_url() . 'Blog/');
	}
	
	public function editPost_post($post_id){
		header("Access-Control-Allow-Origin: *");
		
		$data = array(
			'post_title' => $this->input->post('post_title'), 
			'post' => $this->input->post('post'), 
			'active' => 1
		);
        $this->M_Db->updatePost($post_id, $data);
		$data['success'] = 1;
		$data['post'] = $this->M_Db->getPost($post_id);
		
		$class_name = ['home_class' => 'current', 'login_class' => '', 'register_class' => '', 'profile_class' => ''];
        $this->load->view('Header', $class_name);
        $this->load->view('V_Edit_Post', $data);
        $this->load->view('Footer');
	}
}
?>