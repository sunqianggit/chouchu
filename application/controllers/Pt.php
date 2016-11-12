<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Pt extends CI_Controller {

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
        $this->load->model('pt_mdl');
    }
    
    public function index(){
        $this->load->view('pt/index');
    }
    
    public function sql_view($ccbh = '') {
        if ($ccbh == '') {
            redirect($_SERVER['HTTP_REFERER']);
        }
        $insert_arr = array();
        if (isset($_POST['query'])) {
            foreach ($_POST['query'] as $key => $sql) {
                $insert_arr[] = array(
                    'ccbh' => $ccbh,
                    'name' => $key,
                    'sql_content' => $sql,
                    'status' => 0
                );
            }
            $this->pt_mdl->save($insert_arr, $ccbh);
            redirect('pt');
        }
        $mode = $this->pt_mdl->generate_sql($ccbh);
//        echo '<pre>';print_r($mode);exit;
        $this->load->view('pt/sql_view', $mode);
    }
    
    public function exec_sql_task($ccbh = '') {
        if($ccbh !== ''){
            $this->pt_mdl->exec_qtchouchu_task($ccbh);
        }else{
            $task_queue = $this->pt_mdl->get_task_queue();
            foreach ($task_queue as $queue) {
                $this->pt_mdl->exec_qtchouchu_task($queue['ccbh'], $queue['id']);
            }
        }
    }
}