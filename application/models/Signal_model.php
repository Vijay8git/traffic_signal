<?php
class Signal_model extends CI_model{
    public function __construct(){
		parent::__construct();
	}

    public function signal_exists(){
        $query = $this->db->get('signals');
        return $query->num_rows() > 0;
    }

    public function save_signal($data){
        return $this->db->insert('signals', $data);
    }

    public function update_signal($data){
        return $this->db->update('signals', $data, ['id' => 1]);
    }

    public function get_last_signal(){
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('signals', 1);
        return $query->row_array();
    }
}
?>