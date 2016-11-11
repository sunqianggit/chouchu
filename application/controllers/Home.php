<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {

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
    public function __construct() {
        parent::__construct();
        $this->load->model('tasks_mdl');
    }

    public function do_list() {
        $this->load->view('pt_chouchu/index');
    }

    public function index() {
        if (isset($_POST['save'])) {
            $id = $this->tasks_mdl->insert1($_POST);
            redirect('/home/sql_view/' . $id);
        }
        $this->load->view('home');
    }

    public function sql_view($id = '') {
        if ($id == '') {
            redirect($_SERVER['HTTP_REFERER']);
        }
        $insert_arr = array();
        if (isset($_POST['query'])) {
            foreach ($_POST['query'] as $key => $sql) {
                $insert_arr[] = array(
                    'relate_id' => $id,
                    'name' => $key,
                    'sql_content' => $sql,
                    'status' => 0
                );
            }
            $this->tasks_mdl->insert2($insert_arr);
            redirect('/home/index');
        }
        $mode = $this->tasks_mdl->generate_sql($id);
        $this->load->view('sql_view', $mode);
    }

    public function exec_sql_task($id) {

        $this->tasks_mdl->exec_qtchouchu_task($id);
    }


}
