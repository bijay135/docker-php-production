<?php
class Users extends CI_Controller{
	function __construct(){
        parent::__construct();
        $this->load->model('M_User');
    }
	
 	function register(){
		$data['error'] = 0;
		
        $class_name = array('home_class' => '', 'login_class' => '', 'register_class' => 'current', 'profile_class' => '');
        $this->load->view('Header',$class_name);
        $this->load->view('V_Register',$data);
        $this->load->view('Footer');
    }
	
    function login(){
		$data['error'] = 0;
		
        $class_name = array('home_class' => '', 'login_class' => 'current', 'register_class' => '', 'profile_class' => '');
        $this->load->view('Header',$class_name);
        $this->load->view('V_Login',$data);
        $this->load->view('Footer');
    }
	
    function logout(){
		$this->M_User->deleteToken($this->session->userdata('user_id'));
        $this->session->sess_destroy();
        redirect(base_url().'Blog/');
    }
}
?>