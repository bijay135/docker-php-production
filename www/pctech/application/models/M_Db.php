<?php
class M_Db extends CI_Model{
    function getPosts($number = 10, $start = 0){
        $this->db->select()->from('posts')->where('active',1)->order_by('date_added','desc');
        $this->db->limit($number, $start);
        $query = $this->db->get();
        return $query->result_array();
    }
	
    function getPostCount(){
        $this->db->select()->from('posts')->where('active',1);
        $query = $this->db->get();
        return $query->num_rows();
    }
	
    function getPost($post_id){
        $this->db->select()->from('posts')->where(array('active'=>1,'post_id'=>$post_id))->order_by('date_added','desc');
        $query = $this->db->get();
        return $query->first_row('array');
    }
	
    function insertPost($data){
        $this->db->insert('posts',$data);
        return $this->db->insert_id();
    }
    
    function updatePost($post_id, $data){
        $this->db->where('post_id',$post_id)->update('posts',$data);
    }
    
    function deletePost($post_id){
        $this->db->where('post_id',$post_id)->delete('posts');
    }
}
?>