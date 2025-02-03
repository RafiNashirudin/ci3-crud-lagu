<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lagu_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_lagu() {
        return $this->db->get('lagu')->result();
    }

    public function get_lagu_by_id($id) {
        return $this->db->get_where('lagu', array('id' => $id))->row();
    }

    public function insert_lagu($data) {
        $this->db->insert('lagu', $data);
        return $this->db->insert_id();
    }

    public function update_lagu($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('lagu', $data);
    }

    public function delete_lagu($id) {
        return $this->db->delete('lagu', array('id' => $id));
    }
}
