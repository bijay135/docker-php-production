<?php
class Pages extends CI_Controller{
	function __construct(){
        parent::__construct();
        $this->load->model('M_User');
    }
	
    function myProfile(){
		$user_id = $this->session->userdata('user_id');
				
		$user = $this->M_User->getUserDetails($user_id);
		$user['posts'] = $this->M_User->getUserPostsCount($user_id);
		$user['comments'] = $this->M_User->getUserCommentsCount($user_id);
		
        $class_name = array('home_class' => '', 'login_class' => '', 'register_class' => '', 'profile_class' => 'current');
        $this->load->view('Header',$class_name);
        $this->load->view('V_Profile',$user);
        $this->load->view('Footer');
    }
}
?>