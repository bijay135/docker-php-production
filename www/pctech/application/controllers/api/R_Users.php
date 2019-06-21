<?php
use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';
 
class R_Users extends REST_Controller{
    public function __construct() {
        parent::__construct();
        // Load User Model
        $this->load->model('M_User');
    }

    public function register_post(){
        header("Access-Control-Allow-Origin: *");
		
		$data['error'] = NULL;
		$config = array(
			array(
                'field' => 'fullname',
                'label' => 'Full Name',
                'rules' => 'trim|required|min_length[3]'
            ),
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|min_length[3]|is_unique[users.username]' //is unique username in the user's table of DB
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[5]|max_length[20]'
            ),
            array(
                'field' => 'passconf',
                'label' => 'Password confirmed',
                'rules' => 'trim|required|matches[password]',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|is_unique[users.email]|valid_email', //is unique email in the user's table of DB
            ),
        );
        $this->form_validation->set_rules($config);
        if($this->form_validation->run() == FALSE){
            $data['error'] = validation_errors();
			
			$class_name = array('home_class' => '', 'login_class' => '', 'register_class' => 'current','profile_class' => '');
			$this->load->view('Header',$class_name);
			$this->load->view('V_Register',$data);
			$this->load->view('Footer');
        }
        else{
            $data = array(
				'fullname' => $this->input->post('fullname'),
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => sha1($this->input->post('password')),
            );
            $user_id = $this->M_User->createUser($data);
			$this->session->set_userdata('registered', 1);
            redirect(base_url().'Blog/');
        }    
    }

    public function login_post(){
		header("Access-Control-Allow-Origin: *");
		
		// $validate_token = $this->M_User->fetchToken($this->session->userdata('user_id'));
		$validate_token = $this->session->userdata('user_token');
		
		$is_valid_token = $this->authorization_token->validateTokenPost($validate_token);
		if($is_valid_token['status'] === TRUE){ //If already logged in
            redirect(base_url());
        }
		
        $data['error'] = 0;
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        $user = $this->M_User->login($username, $password);
        if(!$user){ //when user doesn't exist
			$data['error'] = 1;
			
			$class_name = array('home_class' => '', 'login_class' => 'current', 'register_class' => '', 'profile_class' => '');
			$this->load->view('Header',$class_name);
			$this->load->view('V_Login',$data);
			$this->load->view('Footer');
		} 
        else{ //when user exists	
			// Generate Token
            $token_data['id'] = $user['user_id'];
            $token_data['fullname'] = $user['fullname'];
            $token_data['username'] = $user['username'];
            $token_data['email'] = $user['email'];
            $token_data['created_at'] = $user['created_at'];
            $token_data['updated_at'] = $user['updated_at'];
            $token_data['time'] = time();

            $user_token = $this->authorization_token->generateToken($token_data);
			
            $this->session->set_userdata('user_id', $user['user_id']);
            $this->session->set_userdata('username', $user['username']);
			$this->session->set_userdata('user_token',$user_token);	
			
			$this->M_User->insertToken($this->session->userdata('user_id'),$user_token);
            redirect(base_url().'Blog/');
        }
    }
}