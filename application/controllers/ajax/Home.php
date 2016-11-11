<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function search() {
        $result = array();
        $key = trim($this->input->post('key'));
        if ($key) {
            $result = $this->db->where("c_name like '%$key%' ")->get('condition')->result_array();
        }
        $html = $this->load->view('ajax/search_result', array('result' => $result), TRUE);
        echo json_encode(array('status' => 'success', 'result' => $result, 'html' => $html));
        exit;
    }

}
