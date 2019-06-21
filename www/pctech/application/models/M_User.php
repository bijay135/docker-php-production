<?php
class M_User extends CI_Model{
    function createUser($data){
        $this->db->insert('users', $data);
        return $this->db->insert_id();;
    }
	
    function login($username, $password){
        $match = array(
            'username'=>$username,
            'password' => sha1($password),
        );
        $this->db->select()->from('users')->where($match);
        $query = $this->db->get();
        return $query->first_row('array');
    }
	
	function insertToken($user_id, $user_token){
		$this->db->set('user_token',$user_token)->where('user_id',$user_id)->update('users');
	}
	
	function fetchToken($user_id){
		$this->db->select('users.user_token')->from('users')->where('user_id',$user_id);
		$query = $this->db->get();
		$result = $query->row();
		return $result->user_token;  
	}
	
	function deleteToken($user_id){
		$this->db->set('user_token','expired')->where('user_id',$user_id)->update('users');
	}
	
	function getUserDetails($user_id){
		$this->db->select()->from('users')->where('user_id',$user_id);
		$query = $this->db->get();
		return $query->first_row('array');
	}
	
	function getUserPostsCount($user_id){
		$this->db->select()->from('posts')->where('user_id',$user_id);
        $query = $this->db->get();
        return $query->num_rows();
	}
	
	function getUserCommentsCount($user_id){
		$this->db->select()->from('comments')->where('user_id',$user_id);
        $query = $this->db->get();
        return $query->num_rows();
	}
}
?>