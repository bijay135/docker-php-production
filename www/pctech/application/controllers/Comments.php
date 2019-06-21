<?php
class Comments extends CI_Controller{
	function __construct(){
        parent::__construct();
        $this->load->model('M_Comment');
    }
	
    function addComment($postID){
        if(!$this->input->post()){
            redirect(base_url().'Blog/post'.$postID);
        }
        
        $validate_token = $this->session->userdata('user_token');
		$is_valid_token = $this->authorization_token->validateTokenPost($validate_token);
        if($is_valid_token['status'] === FALSE){
            redirect(base_url().'Users/login');
        }
        
        $data = array(
            'post_id' => $postID,
            'user_id' => $this->session->userdata('user_id'),
            'comment' => $this->input->post('comment'),
        );
        $this->M_Comment->addComment($data);
        redirect(base_url().'Blog/post/'.$postID);
    }
}
?>