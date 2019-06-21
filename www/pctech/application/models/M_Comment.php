<?php
class M_Comment extends CI_Model{
    function addComment($data){
        $this->db->insert('comments',$data);
        return $this->db->insert_id();
    }
    
    function getComment($post_id){
        $this->db->select('comments.*,users.username')->from('comments')->join('users','users.user_id = comments.user_id','left');
        $this->db->where('post_id',$post_id)->order_by('date_added','asc');
        $query = $this->db->get();
        return $query->result_array();
    }
}
?>