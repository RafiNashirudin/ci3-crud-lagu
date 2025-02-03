<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lagu extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Lagu_model');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['lagu'] = $this->Lagu_model->get_all_lagu();
        $this->load->view('lagu_view', $data);
    }

    public function add() {
        $this->form_validation->set_rules('JudulLagu', 'Judul Lagu', 'required');
        $this->form_validation->set_rules('Penyanyi', 'Penyanyi', 'required');
        $this->form_validation->set_rules('Pencipta', 'Pencipta', 'required');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => 'error', 'message' => validation_errors()));
        } else {
            // Handle file upload
            $upload_result = $this->_handle_upload('Gambar');
            if ($upload_result['status'] === 'error') {
                echo json_encode($upload_result);
                return;
            }

            // Prepare data for insertion
            $data = array(
                'JudulLagu' => $this->input->post('JudulLagu'),
                'Penyanyi' => $this->input->post('Penyanyi'),
                'Pencipta' => $this->input->post('Pencipta'),
                'Gambar' => $upload_result['file_name']
            );

            // Insert data into database
            $insert_id = $this->Lagu_model->insert_lagu($data);
            if ($insert_id) {
                echo json_encode(array('status' => 'success', 'message' => 'Data berhasil ditambahkan', 'id' => $insert_id));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Gagal menambahkan data'));
            }
        }
    }

    public function edit($id) {
        $data = $this->Lagu_model->get_lagu_by_id($id);
        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
        }
    }

    public function update($id) {
        $this->form_validation->set_rules('JudulLagu', 'Judul Lagu', 'required');
        $this->form_validation->set_rules('Penyanyi', 'Penyanyi', 'required');
        $this->form_validation->set_rules('Pencipta', 'Pencipta', 'required');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(array('status' => 'error', 'message' => validation_errors()));
        } else {
            $data = array(
                'JudulLagu' => $this->input->post('JudulLagu'),
                'Penyanyi' => $this->input->post('Penyanyi'),
                'Pencipta' => $this->input->post('Pencipta')
            );

            // Handle file upload if a new file is provided
            if (!empty($_FILES['Gambar']['name'])) {
                $upload_result = $this->_handle_upload('Gambar');
                if ($upload_result['status'] === 'error') {
                    echo json_encode($upload_result);
                    return;
                }
                $data['Gambar'] = $upload_result['file_name'];

                // Delete old image file
                $old_lagu = $this->Lagu_model->get_lagu_by_id($id);
                if ($old_lagu && !empty($old_lagu->Gambar)) {
                    $old_image_path = './uploads/' . $old_lagu->Gambar;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
            }

            // Update data in database
            $update_result = $this->Lagu_model->update_lagu($id, $data);
            if ($update_result) {
                echo json_encode(array('status' => 'success', 'message' => 'Data berhasil diupdate'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Gagal mengupdate data'));
            }
        }
    }

    public function delete($id) {
        // Get lagu data before deleting
        $lagu = $this->Lagu_model->get_lagu_by_id($id);
        if ($lagu) {
            // Delete image file
            if (!empty($lagu->Gambar)) {
                $image_path = './uploads/' . $lagu->Gambar;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            // Delete data from database
            $delete_result = $this->Lagu_model->delete_lagu($id);
            if ($delete_result) {
                echo json_encode(array('status' => 'success', 'message' => 'Data berhasil dihapus'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Gagal menghapus data'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
        }
    }

    /**
     * Handle file upload
     *
     * @param string $field_name Name of the file input field
     * @return array Upload result
     */
    private function _handle_upload($field_name) {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE; // Encrypt file name for security

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field_name)) {
            return array('status' => 'error', 'message' => $this->upload->display_errors());
        } else {
            $upload_data = $this->upload->data();
            return array('status' => 'success', 'file_name' => $upload_data['file_name']);
        }
    }
}